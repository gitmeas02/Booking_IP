<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHQR Code with Transaction Tracking</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code-img {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }
        .qr-string {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            word-break: break-all;
            border: 1px solid #e9ecef;
            margin: 10px 0;
        }
        .info-box {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
        }
        .success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .status-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-pending { background: #ffc107; }
        .status-success { background: #28a745; }
        .status-failed { background: #dc3545; }
        .status-checking { 
            background: #007bff; 
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 KHQR Code with Transaction Tracking</h1>
        
        @if(isset($message))
            <div class="info-box success">
                <strong>✅ {{ $message }}</strong>
            </div>
        @endif

        @if(isset($qrString) && $qrString)
            <div class="qr-section">
                <h2>📱 Scan this QR Code</h2>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrString) }}" 
                        alt="KHQR Code" 
                        class="qr-code-img" />
                
                <div style="margin-top: 20px;">
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&format=png&data={{ urlencode($qrString) }}" 
                        download="khqr-code.png" 
                        class="btn">
                        📥 Download QR Code
                    </a>
                </div>
            </div>

            @if(isset($hasApiToken) && $hasApiToken)
                <div class="status-section">
                    <h3>🔍 Transaction Status</h3>
                    <div id="transaction-status">
                        <span class="status-indicator status-pending"></span>
                        <span>Waiting for payment...</span>
                    </div>
                    <div style="margin-top: 15px;">
                        <button onclick="checkTransactionStatus()" class="btn" id="check-btn">
                            🔄 Check Status
                        </button>
                        <button onclick="toggleAutoCheck()" class="btn" id="auto-check-btn">
                            ⏰ Auto Check (OFF)
                        </button>
                    </div>
                    <div id="transaction-details" style="margin-top: 15px; display: none;">
                        <h4>Transaction Details:</h4>
                        <pre id="transaction-data" class="qr-string"></pre>
                    </div>
                </div>
            @else
                <div class="info-box warning">
                    <strong>⚠️ API Token Required:</strong>
                    <p>To enable transaction tracking, you need to:</p>
                    <ol>
                        <li>Register at <a href="https://api-bakong.nbc.gov.kh/register" target="_blank">Bakong API</a></li>
                        <li>Add your API token to the .env file: <code>BAKONG_API_TOKEN=your_token</code></li>
                        <li>Restart your Laravel application</li>
                    </ol>
                </div>
            @endif

            <div>
                <h3>🔢 KHQR String</h3>
                <div class="qr-string">{{ $qrString }}</div>
                
                <button onclick="copyToClipboard('{{ $qrString }}')" class="btn">
                    📋 Copy KHQR String
                </button>
            </div>

            @if(isset($md5) && $md5)
                <div>
                    <h3>🔐 Transaction ID (MD5)</h3>
                    <div class="qr-string">{{ $md5 }}</div>
                    <button onclick="copyToClipboard('{{ $md5 }}')" class="btn">
                        📋 Copy MD5
                    </button>
                </div>
            @endif

            <div class="info-box">
                <strong>ℹ️ Instructions:</strong>
                <ul>
                    <li>Open your banking app (ABA, Wing, etc.)</li>
                    <li>Look for "Scan QR" or "KHQR" option</li>
                    <li>Scan the QR code above</li>
                    <li>Confirm the payment details</li>
                    @if(isset($hasApiToken) && $hasApiToken)
                        <li>The transaction status will update automatically</li>
                    @endif
                </ul>
            </div>
        @endif

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/generate-trackable-khqr') }}" class="btn">🔄 Generate New Trackable QR</a>
            <a href="{{ url('/generate-khqr') }}" class="btn">📱 Simple QR</a>
        </div>
    </div>

    <script>
        let autoCheckInterval = null;
        let isAutoChecking = false;

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('✅ Copied to clipboard!');
            }, function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('✅ Copied to clipboard!');
            });
        }

        function checkTransactionStatus() {
            const statusElement = document.getElementById('transaction-status');
            const checkBtn = document.getElementById('check-btn');
            const detailsElement = document.getElementById('transaction-details');
            
            // Show checking status
            statusElement.innerHTML = '<span class="status-indicator status-checking"></span><span>Checking transaction status...</span>';
            checkBtn.disabled = true;
            
            fetch('/check-transaction', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusElement.innerHTML = '<span class="status-indicator status-success"></span><span>✅ Transaction data retrieved</span>';
                    
                    // Show transaction details
                    document.getElementById('transaction-data').textContent = JSON.stringify(data.data, null, 2);
                    detailsElement.style.display = 'block';
                } else {
                    statusElement.innerHTML = '<span class="status-indicator status-pending"></span><span>⏳ ' + data.message + '</span>';
                }
            })
            .catch(error => {
                statusElement.innerHTML = '<span class="status-indicator status-failed"></span><span>❌ Error checking status</span>';
                console.error('Error:', error);
            })
            .finally(() => {
                checkBtn.disabled = false;
            });
        }

        function toggleAutoCheck() {
            const autoBtn = document.getElementById('auto-check-btn');
            
            if (isAutoChecking) {
                // Stop auto checking
                clearInterval(autoCheckInterval);
                autoCheckInterval = null;
                isAutoChecking = false;
                autoBtn.textContent = '⏰ Auto Check (OFF)';
                autoBtn.style.background = '#007bff';
            } else {
                // Start auto checking
                autoCheckInterval = setInterval(checkTransactionStatus, 10000); // Check every 10 seconds
                isAutoChecking = true;
                autoBtn.textContent = '⏸️ Auto Check (ON)';
                autoBtn.style.background = '#28a745';
                
                // Check immediately
                checkTransactionStatus();
            }
        }

        // Clean up interval when page is closed
        window.addEventListener('beforeunload', function() {
            if (autoCheckInterval) {
                clearInterval(autoCheckInterval);
            }
        });
    </script>
</body>
</html>