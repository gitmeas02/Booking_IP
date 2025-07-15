// Simple test script to verify payment flow
const testPaymentFlow = async () => {
  const testBookingData = {
    amount: 150,
    merchant_name: "Khun Hotel - Room Booking",
    user_id: 1,
    room_ids: [1, 2],
    hotel_id: 1,
    check_in_date: "2025-07-20",
    check_out_date: "2025-07-22",
    number_of_guests: 2,
    total_price: 150,
    special_request: "Test booking request",
    payment_method: "khqr",
  };

  try {
    console.log("Testing payment with booking flow...");
    
    // 1. Create payment with booking
    const paymentResponse = await fetch("http://localhost:8100/api/payments/with-booking", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(testBookingData),
    });

    const paymentData = await paymentResponse.json();
    console.log("Payment response:", paymentData);

    if (paymentData.success) {
      console.log("✅ Payment created successfully!");
      console.log("Transaction ID:", paymentData.transaction_id);
      console.log("QR String:", paymentData.qr_string);
      
      // 2. Check payment status
      const transactionId = paymentData.transaction_id;
      console.log("Checking payment status...");
      
      const statusResponse = await fetch(
        `http://localhost:8100/api/payments/${transactionId}/status-with-booking`,
        {
          method: "GET",
          headers: {
            Accept: "application/json",
          },
        }
      );

      const statusData = await statusResponse.json();
      console.log("Status response:", statusData);

      if (statusData.success) {
        console.log("✅ Status check successful!");
        console.log("Status:", statusData.status);
      } else {
        console.log("❌ Status check failed:", statusData.message);
      }
    } else {
      console.log("❌ Payment creation failed:", paymentData.message);
    }
  } catch (error) {
    console.error("Test failed:", error);
  }
};

// Run the test
testPaymentFlow();
