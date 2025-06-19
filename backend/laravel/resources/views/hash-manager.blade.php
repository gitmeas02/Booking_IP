<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHQR Hash Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .hash-display { background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; word-break: break-all; margin: 10px 0; }
        .btn { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #000; }
        .btn-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .hash-type { font-weight: bold; color: #495057; }
        .copy-btn { font-size: 12px; padding: 5px 10px; }
    </style>
</head>
<body>
    <h1>🔐 KHQR Hash Manager</h1>
    
    <div class="card">
        <h2>🔍 Find Transaction by Hash</h2>
        <div class="form-group">
            <label for="hashInput">Enter any hash (MD5, Full Hash, or Short Hash):</label>
            <input type="text" id="hashInput" placeholder="Enter hash here..." />
        </div>
        <button onclick="findByHash()" class="btn">🔍 Find Transaction</button>
        
        <div id="findResult" style="margin-top: 20px;"></div>
    </div>

    <div class="card">
        <h2>📊 Show All Hashes for Transaction</h2>
        <div class="form-group">
            <label for="transactionInput">Transaction ID:</label>
            <input type="text" id="transactionInput" placeholder="Enter transaction ID..." />
        </div>
        <button onclick="showHashes()" class="btn">📋 Show All Hashes</button>
        
        <div id="hashResult" style="margin-top: 20px;"></div>
    </div>

    <div class="card">
        <h2>🔄 Batch Update Missing Hashes</h2>
        <p>Update full hash and short hash for all transactions that are missing them.</p>
        <button onclick="updateMissingHashes()" class="btn btn-warning">🔄 Update Missing Hashes</button>
        
        <div id="updateResult" style="margin-top: 20px;"></div>
    </div>

    <div class="card">
        <h2>📋 Recent Transactions</h2>
        <button onclick="loadRecentTransactions()" class="btn">📋 Load Recent Transactions</button>
        
        <div id="transactionsList" style="margin-top: 20px;"></div>
    </div>

    <div class="card info">
        <h3>ℹ️ Hash Types Explained</h3>
        <table>
            <tr>
                <th>Hash Type</th>
                <th>Length</th>
                <th>Description</th>
                <th>Usage</th>
            </tr>
            <tr>
                <td class="hash-type">MD5</td>
                <td>32 characters</td>
                <td>Original hash provided by KHQR library</td>
                <td>Primary method for checking transaction status</td>
            </tr>
            <tr>
                <td class="hash-type">Full Hash (SHA256)</td>
                <td>64 characters</td>
                <td>SHA256 hash of the QR string</td>
                <td>Alternative method for checking transactions</td>
            </tr>
            <tr>
                <td class="hash-type">Short Hash</td>
                <td>8 characters</td>
                <td>First 8 characters of SHA256</td>
                <td>Requires amount for verification</td>
            </tr>
        </table>

        <h4>🧪 Command Line Examples:</h4>
        <div class="hash-display">
            # Check by MD5 (32 chars)
            php artisan khqr:test --check-md5=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6

            # Check by Full Hash (64 chars)  
            php artisan khqr:test --check-full-hash=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2

            # Check by Short Hash (8 chars + amount)
            php artisan khqr:test --check-short-hash=a1b2c3d4 --amount=500

            # Show all hashes for a transaction
            php artisan khqr:test --show-hashes=TXN_ABC123

            # Update missing hashes
            php artisan khqr:test --update-hashes
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function findByHash() {
            const hash = document.getElementById('hashInput').value.trim();
            if (!hash) {
                alert('Please enter a hash');
                return;
            }

            const resultDiv = document.getElementById('findResult');
            resultDiv.innerHTML = '<p>🔍 Searching...</p>';

            try {
                const response = await fetch('/hash-manager/find', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ hash })
                });

                const data = await response.json();
                
                if (data.success && data.transaction) {
                    const transaction = data.transaction;
                    resultDiv.innerHTML = `
                        <div class="card success">
                            <h3>✅ Transaction Found</h3>
                            <table>
                                <tr><th>Transaction ID</th><td>${transaction.transaction_id}</td></tr>
                                <tr><th>Amount</th><td>${transaction.amount} ${transaction.currency}</td></tr>
                                <tr><th>Status</th><td>${transaction.status}</td></tr>
                                <tr><th>Created</th><td>${new Date(transaction.created_at).toLocaleString()}</td></tr>
                                <tr><th>Merchant</th><td>${transaction.merchant_name || 'N/A'}</td></tr>
                                <tr><th>Booking Ref</th><td>${transaction.booking_reference || 'N/A'}</td></tr>
                            </table>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="card error">
                            <h3>❌ Not Found</h3>
                            <p>${data.message || 'No transaction found with this hash'}</p>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="card error">
                        <h3>❌ Error</h3>
                        <p>Error searching for transaction: ${error.message}</p>
                    </div>
                `;
            }
        }

        async function showHashes() {
            const transactionId = document.getElementById('transactionInput').value.trim();
            if (!transactionId) {
                alert('Please enter a transaction ID');
                return;
            }

            const resultDiv = document.getElementById('hashResult');
            resultDiv.innerHTML = '<p>🔍 Loading hashes...</p>';

            try {
                const response = await fetch('/hash-manager/show', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ transaction_id: transactionId })
                });

                const data = await response.json();
                
                if (data.success && data.hashes) {
                    const hashes = data.hashes;
                    resultDiv.innerHTML = `
                        <div class="card success">
                            <h3>✅ All Hash Types for ${hashes.transaction_id}</h3>
                            <table>
                                <tr>
                                    <th>Hash Type</th>
                                    <th>Value</th>
                                    <th>Length</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td class="hash-type">MD5</td>
                                    <td class="hash-display" style="font-size: 12px;">${hashes.md5}</td>
                                    <td>${hashes.md5.length}</td>
                                    <td><button class="btn copy-btn" onclick="copyToClipboard('${hashes.md5}')">📋 Copy</button></td>
                                </tr>
                                <tr>
                                    <td class="hash-type">Full Hash</td>
                                    <td class="hash-display" style="font-size: 12px;">${hashes.full_hash || 'Not generated'}</td>
                                    <td>${hashes.full_hash ? hashes.full_hash.length : 0}</td>
                                    <td><button class="btn copy-btn" onclick="copyToClipboard('${hashes.full_hash || ''}')" ${!hashes.full_hash ? 'disabled' : ''}>📋 Copy</button></td>
                                </tr>
                                <tr>
                                    <td class="hash-type">Short Hash</td>
                                    <td class="hash-display" style="font-size: 12px;">${hashes.short_hash || 'Not generated'}</td>
                                    <td>${hashes.short_hash ? hashes.short_hash.length : 0}</td>
                                    <td><button class="btn copy-btn" onclick="copyToClipboard('${hashes.short_hash || ''}')" ${!hashes.short_hash ? 'disabled' : ''}>📋 Copy</button></td>
                                </tr>
                            </table>
                            
                            <h4>💰 Transaction Details:</h4>
                            <table>
                                <tr><th>Amount</th><td>${hashes.amount} ${hashes.currency}</td></tr>
                                <tr><th>QR String Length</th><td>${hashes.qr_string.length} characters</td></tr>
                            </table>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="card error">
                            <h3>❌ Not Found</h3>
                            <p>${data.message || 'Transaction not found'}</p>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="card error">
                        <h3>❌ Error</h3>
                        <p>Error loading hashes: ${error.message}</p>
                    </div>
                `;
            }
        }

        async function updateMissingHashes() {
            const resultDiv = document.getElementById('updateResult');
            resultDiv.innerHTML = '<p>🔄 Updating missing hashes...</p>';

            try {
                const response = await fetch('/hash-manager/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    let html = `
                        <div class="card success">
                            <h3>✅ Update Complete</h3>
                            <p>Updated ${data.updated_count} out of ${data.total_transactions} transactions</p>
                    `;
                    
                    if (data.results && data.results.length > 0) {
                        html += `
                            <h4>📊 Updated Transactions:</h4>
                            <table>
                                <tr><th>Transaction ID</th><th>MD5</th><th>Full Hash</th><th>Short Hash</th></tr>
                        `;
                        
                        data.results.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.transaction_id}</td>
                                    <td>${item.hashes.md5.substring(0, 8)}...</td>
                                    <td>${item.hashes.full_hash.substring(0, 8)}...</td>
                                    <td>${item.hashes.short_hash}</td>
                                </tr>
                            `;
                        });
                        
                        html += '</table>';
                    }
                    
                    html += '</div>';
                    resultDiv.innerHTML = html;
                } else {
                    resultDiv.innerHTML = `
                        <div class="card error">
                            <h3>❌ Update Failed</h3>
                            <p>${data.message || 'Failed to update hashes'}</p>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="card error">
                        <h3>❌ Error</h3>
                        <p>Error updating hashes: ${error.message}</p>
                    </div>
                `;
            }
        }

        async function loadRecentTransactions() {
            const resultDiv = document.getElementById('transactionsList');
            resultDiv.innerHTML = '<p>📋 Loading transactions...</p>';

            try {
                const response = await fetch('/payments');
                const data = await response.json();
                
                if (data.success && data.data.data.length > 0) {
                    let html = `
                        <table>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Hashes Available</th>
                                <th>Actions</th>
                            </tr>
                    `;
                    
                    data.data.data.forEach(transaction => {
                        const hasFullHash = transaction.qr_full_hash ? '✅' : '❌';
                        const hasShortHash = transaction.qr_short_hash ? '✅' : '❌';
                        
                        html += `
                            <tr>
                                <td>${transaction.transaction_id}</td>
                                <td>${transaction.amount} ${transaction.currency}</td>
                                <td>${transaction.status}</td>
                                <td>${new Date(transaction.created_at).toLocaleString()}</td>
                                <td>MD5: ✅ | Full: ${hasFullHash} | Short: ${hasShortHash}</td>
                                <td>
                                    <button class="btn copy-btn" onclick="showTransactionHashes('${transaction.transaction_id}')">
                                        📋 View Hashes
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += '</table>';
                    resultDiv.innerHTML = html;
                } else {
                    resultDiv.innerHTML = '<p>No transactions found.</p>';
                }
            } catch (error) {
                resultDiv.innerHTML = `<p>Error loading transactions: ${error.message}</p>`;
            }
        }

        function showTransactionHashes(transactionId) {
            document.getElementById('transactionInput').value = transactionId;
            showHashes();
        }

        function copyToClipboard(text) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Copied to clipboard!');
            }).catch(() => {
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
    </script>
</body>
</html>