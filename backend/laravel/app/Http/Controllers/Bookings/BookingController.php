<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
  public function store(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'room_ids' => 'required|array',
                'room_ids.*' => 'required|integer|exists:room_types,id',
                'hotel_id' => 'required|integer',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'number_of_guests' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
                'special_request' => 'nullable|string',
                'payment_method' => 'nullable|string',
            ]);

            // Calculate commissions (example: 10% for user, 15% for hotel)
            $userCommission = $validated['total_price'] * 0.10;
            $hotelCommission = $validated['total_price'] * 0.15;

            // Create bookings for each room
            $bookingIds = [];
            foreach ($validated['room_ids'] as $roomId) {
                $booking = Booking::create([
                    'user_id' => Auth::id() ?? $request->user_id,
                    'room_id' => $roomId,
                    'owner_application_id' => $validated['hotel_id'],
                    'user_commission' => $userCommission,
                    'hotel_commission' => $hotelCommission,
                    'check_in_date' => $validated['check_in_date'],
                    'check_out_date' => $validated['check_out_date'],
                    'number_of_guests' => $validated['number_of_guests'],
                    'total_price' => $validated['total_price'],
                    'special_request' => $validated['special_request'] ?? null,
                    'payment_method' => $validated['payment_method'] ?? 'credit_card',
                    'status' => 'pending'
                ]);

                $bookingIds[] = $booking->id;
            }

            return response()->json([
                'success' => true,
                'booking_id' => $bookingIds[0], // First booking ID
                'booking_ids' => $bookingIds,
                'message' => 'Booking created successfully!'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Booking creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
