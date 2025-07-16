// Test QR Code Payment Flow
const baseUrl = 'http://localhost:8100/api';

async function testQRPaymentFlow() {
    console.log('🧪 Testing QR Code Payment Flow...');
    
    try {
        // 1. Create a QR code payment
        console.log('1. Creating QR code payment...');
        const createResponse = await fetch(`${baseUrl}/payments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                amount: 50000,
                merchant_name: 'Test Hotel',
                booking_reference: 'TEST_BOOKING_' + Date.now()
            })
        });
        
        const createResult = await createResponse.json();
        console.log('✅ QR Code created:', createResult.success ? 'Success' : 'Failed');
        
        if (!createResult.success) {
            console.error('❌ Failed to create QR code:', createResult.message);
            return;
        }
        
        const transactionId = createResult.transaction_id;
        console.log('📄 Transaction ID:', transactionId);
        console.log('💳 QR String:', createResult.qr_string?.substring(0, 50) + '...');
        console.log('🔑 Account ID:', createResult.account_id);
        
        // 2. Check payment status (should be pending)
        console.log('\n2. Checking payment status...');
        const statusResponse = await fetch(`${baseUrl}/payments/${transactionId}/status`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const statusResult = await statusResponse.json();
        console.log('📊 Payment Status:', statusResult.status || 'Unknown');
        console.log('✅ Status check:', statusResult.success ? 'Success' : 'Failed');
        
        if (statusResult.message) {
            console.log('💬 Message:', statusResult.message);
        }
        
        // 3. Test status checking with booking creation
        console.log('\n3. Testing status check with booking creation...');
        const bookingStatusResponse = await fetch(`${baseUrl}/payments/${transactionId}/status-with-booking`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const bookingStatusResult = await bookingStatusResponse.json();
        console.log('📊 Booking Status Check:', bookingStatusResult.success ? 'Success' : 'Failed');
        console.log('🏨 Booking Created:', bookingStatusResult.booking_created ? 'Yes' : 'No');
        
        console.log('\n🎉 QR Payment Flow Test Completed!');
        console.log('📝 Summary:');
        console.log(`   - QR Code Generated: ${createResult.success ? '✅' : '❌'}`);
        console.log(`   - Status Check: ${statusResult.success ? '✅' : '❌'}`);
        console.log(`   - Booking Flow: ${bookingStatusResult.success ? '✅' : '❌'}`);
        console.log(`   - Transaction ID: ${transactionId}`);
        
    } catch (error) {
        console.error('❌ Test failed with error:', error.message);
    }
}

// Run the test
testQRPaymentFlow();
