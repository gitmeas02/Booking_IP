<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHQR Ecommerce Payment Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        button:disabled { background: #6c757d; cursor: not-allowed; }
        input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 200px; }
        .qr-display { background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace; word-break: break-all; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .qr-section { text-align: center; margin: 20px 0; }
        .qr-code-img { border: 2px solid #ddd; border-radius: 10px; padding: 10px; background: white; }
    </style>
</head>
<body>
    <h1>🏨 KHQR Ecommerce Payment System</h1>
    
    {{-- Display error messages --}}
    @if(session('error'))
        <div class="card error">
            <h3>❌ Error</h3>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Display validation errors --}}
    @if($errors->any())
        <div class="card error">
            <h3>❌ Validation Errors</h3>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="card">
        <h2>💳 Create New Payment</h2>
        <form action="/payment" method="POST">
            @csrf
            <div class="form-group">
                <label for="amount">Amount (KHR):</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount', 500) }}" min="0.01" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="booking_reference">Booking Reference:</label>
                <input type="text" id="booking_reference" name="booking_reference" placeholder="HOTEL_001" value="{{ old('booking_reference', 'HOTEL_' . date('His')) }}">
            </div>
            <div class="form-group">
                <label for="merchant_name">Merchant Name:</label>
                <input type="text" id="merchant_name" name="merchant_name" value="{{ old('merchant_name', 'Hotel Booking Payment') }}">
            </div>
            <div class="form-group">
                <button type="submit">Generate Payment QR</button>
            </div>
        </form>
    </div>

    @if(session('payment_created'))
        <div class="card success">
            <h3>✅ Payment QR Generated Successfully!</h3>
            <table>
                <tr><th>Transaction ID</th><td>{{ session('payment_created')['transaction_id'] ?? 'N/A' }}</td></tr>
                <tr><th>Amount</th><td>{{ session('payment_created')['amount'] ?? 'N/A' }} KHR</td></tr>
                <tr><th>MD5 Hash</th><td>{{ session('payment_created')['md5'] ?? 'N/A' }}</td></tr>
                <tr><th>Expires At</th><td>{{ session('payment_created')['expires_at'] ?? 'N/A' }}</td></tr>
            </table>
            
            @if(session('payment_created')['qr_string'] ?? false)
                <div class="qr-section">
                    <h4>📱 QR Code</h4>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(session('payment_created')['qr_string']) }}" 
                            alt="KHQR Code" 
                            class="qr-code-img" />
                    
                    <div style="margin-top: 15px;">
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&format=png&data={{ urlencode(session('payment_created')['qr_string']) }}" 
                            download="khqr-payment.png" 
                            class="btn">
                            📥 Download QR Code
                        </a>
                    </div>
                </div>

                <h4>📱 QR Code String:</h4>
                <div class="qr-display">{{ session('payment_created')['qr_string'] }}</div>
                
                <p>
                    <button onclick="checkPaymentStatus(@if(session('payment_created')['transaction_id'] ?? false)'{{ session('payment_created')['transaction_id'] }}'@else null @endif)">
                        Check Payment Status
                    </button>
                    <button onclick="copyToClipboard(`{{ session('payment_created')['qr_string'] ?? '' }}`)">
                        Copy QR String
                    </button>
                </p>
            @endif
        </div>
    @endif

    <div class="card">
        <h2>🔍 Check Payment Status</h2>
        <div class="form-group">
            <label for="transactionIdInput">Transaction ID:</label>
            <input type="text" id="transactionIdInput" placeholder="Enter Transaction ID">
            <button onclick="checkPaymentStatus()">Check Status</button>
        </div>
        <div id="statusResult"></div>
    </div>

    <div class="card">
        <h2>📊 Recent Transactions</h2>
        <div id="transactionsList">
            <button onclick="loadTransactions()">Load Recent Transactions</button>
        </div>
    </div>

    <div class="card info">
        <h3>ℹ️ Instructions for Testing</h3>
        <ol>
            <li><strong>Generate Payment QR:</strong> Fill in the amount and click "Generate Payment QR"</li>
            <li><strong>Test with Banking App:</strong> Use ABA, Wing, or any Bakong-compatible app to scan the QR</li>
            <li><strong>Check Status:</strong> Use the "Check Payment Status" button to verify payment completion</li>
            <li><strong>API Integration:</strong> Use the endpoints below for your frontend integration</li>
        </ol>
        
        <h4>🔌 API Endpoints</h4>
        <ul>
            <li><code>POST /payment</code> - Create new payment QR</li>
            <li><code>GET /payment/status/{transactionId}</code> - Check payment status</li>
            <li><code>GET /payments</code> - List all transactions</li>
        </ul>

        <h4>🧪 Testing Commands</h4>
        <ul>
            <li><code>php artisan khqr:test --check-token</code> - Check API token status</li>
            <li><code>php artisan khqr:test --generate=1000</code> - Generate test payment</li>
            <li><code>php artisan khqr:test --list-transactions</code> - List transactions</li>
        </ul>
    </div>

    <script>
        // Get CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function checkPaymentStatus(transactionId = null) {
            const id = transactionId || document.getElementById('transactionIdInput').value;
            if (!id) {
                alert('Please enter a transaction ID');
                return;
            }

            const statusBtn = event?.target;
            if (statusBtn) {
                statusBtn.disabled = true;
                statusBtn.textContent = 'Checking...';
            }

            fetch(`/payment/status/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const resultDiv = document.getElementById('statusResult');
                if (data.success) {
                    let statusClass = 'info';
                    if (data.status === 'success') statusClass = 'success';
                    else if (data.status === 'failed' || data.status === 'expired') statusClass = 'error';

                    resultDiv.innerHTML = `
                        <div class="card ${statusClass}">
                            <h4>Payment Status: ${data.status.toUpperCase()}</h4>
                            <p><strong>Message:</strong> ${data.message}</p>
                            ${data.transaction ? `
                                <table>
                                    <tr><th>Transaction ID</th><td>${data.transaction.transaction_id}</td></tr>
                                    <tr><th>Amount</th><td>${data.transaction.amount} ${data.transaction.currency}</td></tr>
                                    <tr><th>Status</th><td>${data.transaction.status}</td></tr>
                                    <tr><th>Created</th><td>${new Date(data.transaction.created_at).toLocaleString()}</td></tr>
                                    ${data.transaction.expires_at ? `<tr><th>Expires</th><td>${new Date(data.transaction.expires_at).toLocaleString()}</td></tr>` : ''}
                                    ${data.transaction.booking_reference ? `<tr><th>Booking Ref</th><td>${data.transaction.booking_reference}</td></tr>` : ''}
                                </table>
                            ` : ''}
                            ${data.api_response ? `
                                <details>
                                    <summary>API Response Details</summary>
                                    <pre class="qr-display">${JSON.stringify(data.api_response, null, 2)}</pre>
                                </details>
                            ` : ''}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="card error">
                            <h4>Error</h4>
                            <p>${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('statusResult').innerHTML = `
                    <div class="card error">
                        <h4>Error</h4>
                        <p>Failed to check status: ${error.message}</p>
                    </div>
                `;
                console.error('Error:', error);
            })
            .finally(() => {
                if (statusBtn) {
                    statusBtn.disabled = false;
                    statusBtn.textContent = 'Check Payment Status';
                }
            });
        }

        function loadTransactions() {
            const button = event.target;
            button.disabled = true;
            button.textContent = 'Loading...';

            fetch('/payments', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const listDiv = document.getElementById('transactionsList');
                if (data.success && data.data.data && data.data.data.length > 0) {
                    let html = '<table><tr><th>ID</th><th>Amount</th><th>Status</th><th>Created</th><th>Booking Ref</th><th>Actions</th></tr>';
                    data.data.data.forEach(transaction => {
                        html += `
                            <tr>
                                <td>${transaction.transaction_id}</td>
                                <td>${transaction.amount} ${transaction.currency}</td>
                                <td><span style="color: ${getStatusColor(transaction.status)}">${transaction.status.toUpperCase()}</span></td>
                                <td>${new Date(transaction.created_at).toLocaleString()}</td>
                                <td>${transaction.booking_reference || 'N/A'}</td>
                                <td>
                                    <button onclick="checkPaymentStatus('${transaction.transaction_id}')" style="font-size: 12px; padding: 5px 10px;">
                                        Check Status
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    html += '</table>';
                    
                    if (data.data.total > data.data.per_page) {
                        html += `<p><small>Showing ${data.data.per_page} of ${data.data.total} transactions</small></p>`;
                    }
                    
                    listDiv.innerHTML = html;
                } else {
                    listDiv.innerHTML = '<p>No transactions found.</p>';
                }
            })
            .catch(error => {
                document.getElementById('transactionsList').innerHTML = `<p>Error loading transactions: ${error.message}</p>`;
                console.error('Error:', error);
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = 'Load Recent Transactions';
            });
        }

        function getStatusColor(status) {
            switch(status) {
                case 'success': return '#28a745';
                case 'pending': return '#ffc107';
                case 'failed': case 'expired': return '#dc3545';
                default: return '#6c757d';
            }
        }

        function copyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    alert('✅ QR string copied to clipboard!');
                }).catch(() => {
                    fallbackCopyToClipboard(text);
                });
            } else {
                fallbackCopyToClipboard(text);
            }
        }

        function fallbackCopyToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                alert('✅ QR string copied to clipboard!');
            } catch (err) {
                alert('❌ Failed to copy to clipboard');
            }
            
            document.body.removeChild(textArea);
        }

        // Auto-load transactions on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-check if we have a transaction ID from session
            @if(session('payment_created') && isset(session('payment_created')['transaction_id']))
                setTimeout(() => {
                    checkPaymentStatus('{{ session('payment_created')['transaction_id'] }}');
                }, 2000); // Check after 2 seconds
            @endif
        });
    </script>
</body>
</html>