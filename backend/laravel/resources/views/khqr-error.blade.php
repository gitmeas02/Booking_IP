<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHQR Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
            text-align: center;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            margin: 20px 0;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            font-size: 16px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1>KHQR Generation Failed</h1>
        
        <div class="error-message">
            <strong>Error:</strong> {{ $error }}
        </div>
        
        <p>This could be due to:</p>
        <ul style="text-align: left; display: inline-block;">
            <li>Invalid Bakong account ID</li>
            <li>Network connectivity issues</li>
            <li>Missing required parameters</li>
            <li>Server configuration problems</li>
        </ul>
        
        <div style="margin-top: 30px;">
            <a href="{{ url('/generate-khqr') }}" class="btn">🔄 Try Again</a>
            <a href="{{ url('/') }}" class="btn">🏠 Go Home</a>
        </div>
    </div>
</body>
</html>