<?php

namespace App\Http\Controllers\BlockRoom;

use App\Http\Controllers\Controller;
use App\Models\Room\BlockedRoom;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BlockRoomController extends Controller
{
   use AuthorizesRequests;

    public function blockRoom(Request $request, $id)
{
    $roomType = RoomType::with('property')->findOrFail($id);

    $this->authorize('block', $roomType);

    if ($roomType->property->status !== 'approved') {
        return response()->json(['error' => 'This hotel is not approved.'], 403);
    }
    $today = Carbon::today()->toDateString();
    $validated = $request->validate([
        'start_date' => ['required', 'date', 'after_or_equal:' . $today],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        'reason' => 'nullable|string',
    ]);

    $overlapping = BlockedRoom::where('room_type_id', $roomType->id)
        ->where(function ($query) use ($validated) {
            $query->where('start_date', '<=', $validated['end_date'])
                  ->where('end_date', '>=', $validated['start_date']);
        })
        ->exists();

    if ($overlapping) {
        return response()->json(['error' => 'Room is already blocked during the selected date range.'], 409);
    }

    $roomType->blockDates()->create($validated);
    $roomType->is_available = false;
    $roomType->save();
    return response()->json(['message' => 'Room blocked successfully']);
}
// unblock
public function unblock(Request $request, $roomTypeId)
{
    $today = Carbon::today()->toDateString();

    $validated = $request->validate([
        'start_date' => ['required', 'date', 'after_or_equal:' . $today],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
    ]);

    $unblockStart = Carbon::parse($validated['start_date']);
    $unblockEnd = Carbon::parse($validated['end_date']);

    $blocks = BlockedRoom::where('room_type_id', $roomTypeId)
        ->where(function ($query) use ($unblockStart, $unblockEnd) {
            $query->where('start_date', '<=', $unblockEnd->toDateString())
                  ->where('end_date', '>=', $unblockStart->toDateString());
        })
        ->get();

    foreach ($blocks as $block) {
        $blockStart = Carbon::parse($block->start_date);
        $blockEnd = Carbon::parse($block->end_date);

        // Case 1: Unblock covers entire block
        if ($unblockStart->lessThanOrEqualTo($blockStart) && $unblockEnd->greaterThanOrEqualTo($blockEnd)) {
            $block->delete();
            continue;
        }

        // Case 2: Unblock overlaps block start
        if ($unblockStart->lessThanOrEqualTo($blockStart) && $unblockEnd->between($blockStart, $blockEnd)) {
            $newStart = $unblockEnd->copy()->addDay();
            $block->start_date = $newStart->toDateString();
            $block->save();
            continue;
        }

        // Case 3: Unblock overlaps block end
        if ($unblockEnd->greaterThanOrEqualTo($blockEnd) && $unblockStart->between($blockStart, $blockEnd)) {
            $newEnd = $unblockStart->copy()->subDay();
            $block->end_date = $newEnd->toDateString();
            $block->save();
            continue;
        }

        // Case 4: Unblock in middle (split block)
        if ($unblockStart->between($blockStart, $blockEnd) && $unblockEnd->between($blockStart, $blockEnd)) {
            // Left block: blockStart -> unblockStart - 1 day
            $block->end_date = $unblockStart->copy()->subDay()->toDateString();
            $block->save();

            // Right block: unblockEnd + 1 day -> blockEnd
            $rightBlock = $block->replicate();
            $rightBlock->start_date = $unblockEnd->copy()->addDay()->toDateString();
            $rightBlock->end_date = $blockEnd->toDateString();
            $rightBlock->save();

            continue;
        }
    }
    $roomType = RoomType::findOrFail($roomTypeId);
    if (!$roomType->blockDates()->exists()) {
        $roomType->is_available = true;
        $roomType->save();
    }

    return response()->json(['message' => 'Room unblock processed successfully']);
}

}
