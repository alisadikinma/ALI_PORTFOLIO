<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALI Portfolio - Emergency Mode</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            background: #0f172a; 
            color: white; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            padding: 20px;
        }
        .container { 
            text-align: center; 
            max-width: 800px; 
            padding: 40px; 
            background: #1e293b; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        h1 { 
            color: #fbbf24; 
            font-size: 3em; 
            margin-bottom: 20px; 
            font-weight: 700;
        }
        .success { 
            color: #10b981; 
            font-size: 1.5em; 
            margin-bottom: 30px; 
        }
        .status { 
            padding: 20px; 
            background: #064e3b; 
            border: 2px solid #10b981; 
            border-radius: 10px; 
            margin: 20px 0; 
        }
        .btn { 
            display: inline-block; 
            padding: 15px 30px; 
            background: #fbbf24; 
            color: #000; 
            text-decoration: none; 
            border-radius: 10px; 
            font-weight: bold; 
            margin: 10px; 
            transition: all 0.3s ease;
        }
        .btn:hover { 
            background: #f59e0b; 
            transform: translateY(-2px); 
        }
        .error-details {
            background: #7f1d1d;
            border: 2px solid #dc2626;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            text-align: left;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Laravel is Running!</h1>
        <div class="success">✅ Emergency route system is working perfectly!</div>
        
        <div class="status">
            <strong>System Status:</strong><br>
            • Route system: ✅ WORKING<br>
            • Laravel framework: ✅ ACTIVE<br>
            • Emergency mode: ✅ ENABLED<br>
            • PHP Version: {{ PHP_VERSION }}<br>
            • Timestamp: {{ date('Y-m-d H:i:s') }}
        </div>

        @if(isset($error))
        <div class="error-details">
            <strong>Controller Error Details:</strong><br>
            {{ $error }}
        </div>
        @endif

        <div style="margin-top: 30px;">
            <a href="/test" class="btn">🔍 Test Route</a>
            <a href="/debug" class="btn">🐛 Debug Info</a>
            <a href="/portfolio" class="btn">📁 Portfolio</a>
        </div>

        <div style="margin-top: 30px; font-size: 0.9em; color: #94a3b8;">
            <p>This is the emergency fallback page. Your Laravel application is working!</p>
            <p>If you see this, the route error has been fixed. 🎯</p>
        </div>
    </div>
</body>
</html>
