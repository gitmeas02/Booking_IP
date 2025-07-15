<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\Transactions;
use App\Services\KHQRService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductPaymentController extends Controller
{
    private KHQRService $khqrService;

    public function __construct(KHQRService $khqrService)
    {
        $this->khqrService = $khqrService;
    }

    /**
     * Create a payment for product orders
     */
    public function createProductPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'customer' => 'required|array',
            'customer.firstName' => 'required|string|max:255',
            'customer.lastName' => 'required|string|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:card,qr,cash',
            'total_amount' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        try {
            DB::beginTransaction();

            // Create a booking record for the product order
            $booking = Booking::create([
                'user_id' => auth()->id() ?? 1, // Use authenticated user or default
                'room_id' => $data['items'][0]['product_id'], // Use first product as room reference
                'owner_application_id' => 1, // Default application
                'user_commission' => $data['total_amount'] * 0.05,
                'hotel_commission' => $data['total_amount'] * 0.05,
                'check_in_date' => now()->format('Y-m-d'),
                'check_out_date' => now()->addDay()->format('Y-m-d'),
                'number_of_guests' => 1,
                'total_price' => $data['total_amount'],
                'special_request' => $data['special_requests'],
                'status' => 'pending',
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $data['total_amount'],
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_transaction_id' => null,
            ]);

            // Handle different payment methods
            $paymentResult = null;
            switch ($data['payment_method']) {
                case 'qr':
                    $paymentResult = $this->processQRPayment($data, $booking, $payment);
                    break;
                case 'card':
                    $paymentResult = $this->processCardPayment($data, $booking, $payment);
                    break;
                case 'cash':
                    $paymentResult = $this->processCashPayment($data, $booking, $payment);
                    break;
            }

            if (!$paymentResult['success']) {
                DB::rollBack();
                return response()->json($paymentResult, 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'order_id' => 'ORD-' . $booking->id,
                    'total_amount' => $data['total_amount'],
                    'payment_method' => $data['payment_method'],
                    'payment_data' => $paymentResult['data'] ?? null,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product payment creation failed', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process QR payment
     */
    private function processQRPayment($data, $booking, $payment)
    {
        try {
            $result = $this->khqrService->generateIndividual(
                $data['total_amount'],
                'Pteas Khmer Product Store'
            );

            if (!$result['success']) {
                return [
                    'success' => false,
                    'message' => 'Failed to generate QR code: ' . $result['message'],
                ];
            }

            // Create transaction record
            $transaction = Transactions::create([
                'transaction_id' => 'PROD_' . Str::upper(Str::random(12)),
                'bakong_account_id' => 'sok_chanmakara@aclb',
                'qr_string' => $result['qr_string'],
                'qr_md5' => $result['md5'],
                'qr_full_hash' => $result['full_hash'],
                'qr_short_hash' => $result['short_hash'],
                'amount' => $data['total_amount'],
                'currency' => 'KHR',
                'merchant_name' => 'Pteas Khmer Product Store',
                'status' => 'pending',
                'expires_at' => null,
                'booking_reference' => 'ORD-' . $booking->id,
            ]);

            // Update payment record
            $payment->update([
                'payment_transaction_id' => $transaction->transaction_id,
            ]);

            return [
                'success' => true,
                'data' => [
                    'transaction_id' => $transaction->transaction_id,
                    'qr_string' => $result['qr_string'],
                    'qr_md5' => $result['md5'],
                    'amount' => $data['total_amount'],
                    'merchant_name' => 'Pteas Khmer Product Store',
                ],
            ];

        } catch (\Exception $e) {
            Log::error('QR payment processing failed', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => false,
                'message' => 'QR payment processing failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process card payment
     */
    private function processCardPayment($data, $booking, $payment)
    {
        // Simulate card processing
        // In production, integrate with actual payment processors like Stripe
        
        $transactionId = 'CARD_' . Str::upper(Str::random(12));
        
        // Update payment record
        $payment->update([
            'payment_transaction_id' => $transactionId,
            'payment_status' => 'completed', // Simulate success
        ]);

        // Update booking status
        $booking->update([
            'status' => 'confirmed',
        ]);

        return [
            'success' => true,
            'data' => [
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'amount' => $data['total_amount'],
            ],
        ];
    }

    /**
     * Process cash payment
     */
    private function processCashPayment($data, $booking, $payment)
    {
        $transactionId = 'CASH_' . Str::upper(Str::random(12));
        
        // Update payment record
        $payment->update([
            'payment_transaction_id' => $transactionId,
            'payment_status' => 'at_property',
        ]);

        return [
            'success' => true,
            'data' => [
                'transaction_id' => $transactionId,
                'status' => 'at_property',
                'amount' => $data['total_amount'],
            ],
        ];
    }

    /**
     * Get order details
     */
    public function getOrder($orderId)
    {
        try {
            $booking = Booking::with(['payment'])->findOrFail($orderId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => 'ORD-' . $booking->id,
                    'booking' => $booking,
                    'payment' => $booking->payment,
                    'status' => $booking->status,
                ],
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }
    }

    /**
     * Get order history for a user
     */
    public function getOrderHistory(Request $request)
    {
        try {
            $userId = auth()->id() ?? 1;
            
            $orders = Booking::with(['payment'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get order history',
            ], 500);
        }
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            $booking = Booking::findOrFail($orderId);
            $booking->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated',
                'data' => $booking,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status',
            ], 500);
        }
    }
}