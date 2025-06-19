# KHQR Hash Transaction Status - Understanding Why Checks Fail

## 🔍 The Issue You're Experiencing

When you try to check transaction status using MD5, Full Hash, or Short Hash with both:
- `https://sit-api-bakong.nbc.gov.kh/` (Testing environment)
- `https://api-bakong.nbc.gov.kh/` (Production environment)

The API returns "transaction not found" because **you're checking for transactions that were never actually paid**.

## 📊 What's Happening vs What Should Happen

### Current Workflow (❌ Won't Work):
```
1. Generate KHQR → Get hashes → Store in database
2. Immediately check status via API → NOT FOUND
```

### Correct Workflow (✅ Will Work):
```
1. Generate KHQR → Get hashes → Store in database
2. Customer scans QR → Completes payment → Bakong processes
3. Check status via API → FOUND with payment details
```

## 🧪 Testing Strategy

### Option 1: Real Payment Test (Recommended)
```bash
# Generate a QR code for actual testing
php artisan khqr:test --generate-live-test=100

# This will:
# 1. Create a QR code you can actually scan
# 2. Give you the hashes to test with
# 3. Provide instructions for real payment testing
```

### Option 2: Simulate Payment Flow
```bash
# 1. Generate test QR
php artisan khqr:test --generate=500

# 2. Show all hashes for reference
php artisan khqr:test --show-hashes=TEST_20250527115253

# 3. Try checking (will fail until payment is made)
php artisan khqr:test --check-md5=your_md5_hash
```

## 🔄 Hash Types and Their Purpose

| Hash Type | Length | When Available | Use Case |
|-----------|--------|----------------|----------|
| **MD5** | 32 chars | Immediately after QR generation | Primary status checking |
| **Full Hash** | 64 chars | Immediately after QR generation | Alternative status checking |
| **Short Hash** | 8 chars | Immediately after QR generation | Status checking with amount verification |

## ⚠️ Important Notes

1. **Hashes are generated locally** - They exist as soon as you create the QR code
2. **Bakong only knows about completed payments** - Until someone pays, the transaction doesn't exist in their system
3. **API token is valid** - Your authentication is working correctly
4. **Both environments behave the same** - Testing and production will both return "not found" for unpaid transactions

## 🔧 Troubleshooting Steps

### Step 1: Verify Your Setup
```bash
# Check if your API token is working
php artisan khqr:test --check-token
```

### Step 2: Generate a Live Test Payment
```bash
# Create a QR you can actually pay
php artisan khqr:test --generate-live-test=50
```

### Step 3: Complete the Payment
1. Use a Bakong-compatible app (like ABA mobile banking)
2. Scan the QR code displayed
3. Complete the payment
4. Wait 1-2 minutes for processing

### Step 4: Check Status After Payment
```bash
# Use the hashes provided from step 2
php artisan khqr:test --check-md5=your_actual_md5
php artisan khqr:test --check-full-hash=your_actual_full_hash
php artisan khqr:test --check-short-hash=your_short_hash --amount=50
```

## 💡 Development Best Practices

### For Testing:
- Use small amounts (50-100 KHR) for real payment tests
- Always use the testing environment first
- Keep track of which QR codes have been actually paid

### For Production:
- Only check status after sufficient time has passed for payment completion
- Implement proper error handling for "transaction not found" scenarios
- Consider webhook integration for real-time payment notifications

## 🚀 Next Steps

1. **Try the live test**: `php artisan khqr:test --generate-live-test=50`
2. **Complete a real payment** using the generated QR
3. **Check status after payment** to see the system working correctly
4. **Implement proper payment flow** in your application

This explains why your hash checks aren't finding transactions - they haven't been paid yet!