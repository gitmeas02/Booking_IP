<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        // Validate request
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Generate dummy QR string (replace with KHQR builder or SDK output)
        $accountId = 'merchant_account@devb';
        $amount = number_format($request->amount, 2, '.', '');
        $timestamp = now()->timestamp;
        $qrString = "000201010212...{$timestamp}"; // This should follow KHQR format
        $md5 = md5($qrString);

        $transaction = Transaction::create([
            'transaction_id' => uniqid('txn_'),
            'bakong_account_id' => $accountId,
            'qr_string' => $qrString,
            'qr_md5' => $md5,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        return response()->json([
            'qr_string' => $qrString,
            'transaction_id' => $transaction->transaction_id,
        ]);
    }

    public function checkStatus($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        if ($transaction->status !== 'pending') {
            return response()->json(['status' => $transaction->status]);
        }

        $accessToken = env('BAKONG_ACCESS_TOKEN');
        $url = env('BAKONG_API_URL', 'https://api-bakong.nbc.gov.kh') . '/v1/check_transaction_by_md5';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'md5' => $transaction->qr_md5,
        ]);

        $data = $response->json();

        if ($data['responseCode'] == 0) {
            $transaction->update(['status' => 'success']);
            // TODO: Notify merchant and client here (email, broadcast, etc.)
        } elseif ($data['responseMessage'] === 'Transaction failed.') {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json(['status' => $transaction->status]);
    }
}
