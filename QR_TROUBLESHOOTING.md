# QR Code "Cannot Connect to Server" Troubleshooting Guide

## Issue Summary
When users scan the generated QR code, they receive the error: "Cannot Connect to server, Please check ur connection"

## Root Cause Analysis

### 1. **Network Connectivity**
- The mobile device scanning the QR code must have internet access
- The Bakong API servers must be accessible from the user's location

### 2. **App Compatibility**
- QR codes must be scanned with the **official Bakong app**
- Other QR code scanners may not work properly with KHQR format

### 3. **Account Status**
- The account `khun_meas@aclb` must be active and verified
- The account must have proper permissions for receiving payments

### 4. **QR Code Format**
- Our tests show the QR code format is correct (EMVCo standard)
- Contains proper Bakong account ID and amount information

## Solutions Applied

### ✅ Environment Configuration Fixed
```env
BAKONG_API_TOKEN=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiMzZhYzE3MWM0YTcwNDA0YSJ9LCJpYXQiOjE3NDc0MTQwMTMsImV4cCI6MTc1NTE5MDAxM30.tOiwZ7dwJdl-r0S5Neft0WNsLBZBp8vCvTjhIrGPahw
BAKONG_API_URL=https://api-bakong.nbc.gov.kh
BAKONG_API_ENV=production
```

### ✅ QR Code Generation Verified
- Created diagnostic tools to verify QR code generation
- Confirmed QR codes contain correct format and data
- API token is valid and not expired

### ✅ Payment System Corrections
- Fixed payment status checking logic
- Prevented false success messages
- Enhanced error handling and logging

## Testing Instructions

### 1. **For Developers**
```bash
# Test QR code generation
php artisan test:khqr

# Check environment configuration
php artisan config:clear && php artisan config:cache
```

### 2. **For End Users**
1. Open the test page: `http://localhost:8100/test-qr.html`
2. Click "Generate QR Code"
3. Scan with the official Bakong app
4. If error occurs, check:
   - Internet connection
   - Using official Bakong app
   - Account `khun_meas@aclb` is active

## Account Verification

The account `khun_meas@aclb` should be verified:
1. **Account Status**: Active and verified
2. **Permissions**: Can receive payments
3. **Balance**: Not required for receiving payments
4. **Verification**: KYC completed if required

## Alternative Solutions

### 1. **Test with Different Account**
If `khun_meas@aclb` has issues, try with a verified account:
```php
// In KHQRService.php
public function generateIndividual(
    float $amount = 500.0, 
    string $merchantName = 'Hotel Booking Payment',
    string $bakongAccountID = 'your_verified_account@aclb'  // Change this
): array
```

### 2. **Enable Test Mode**
For testing purposes, you can enable test mode:
```env
BAKONG_API_ENV=testing
```

### 3. **Network Diagnostics**
Check if the Bakong API is accessible:
```bash
curl -I https://api-bakong.nbc.gov.kh
```

## Expected Behavior

### ✅ Successful Flow
1. User scans QR code with Bakong app
2. App connects to Bakong servers
3. Payment interface appears
4. User completes payment
5. Status changes to "success"

### ❌ Error Scenarios
1. **No internet**: "Cannot Connect to server"
2. **Wrong app**: QR code not recognized
3. **Invalid account**: Payment rejected
4. **Expired QR**: "Transaction expired"

## Monitoring

Use the test page to monitor:
- QR code generation success rate
- Payment completion rate
- Error patterns and frequency

## Support

If the issue persists:
1. Verify the Bakong account status
2. Contact Bakong support for account verification
3. Test with a different verified account
4. Check network connectivity from user's location
