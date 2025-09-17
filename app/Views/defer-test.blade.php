<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Defer::terminate() Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px;
        }

        .status-card {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .log-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .log-output {
            background: #1a1a1a;
            color: #00ff00;
            font-family: 'Monaco', 'Menlo', monospace;
            padding: 20px;
            border-radius: 6px;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-line;
            font-size: 14px;
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 8px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .controls {
            text-align: center;
            margin: 20px 0;
        }

        code {
            background: #f1f3f4;
            padding: 3px 6px;
            border-radius: 3px;
            font-family: 'Monaco', monospace;
            font-size: 14px;
        }

        .highlight {
            background: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 600;
        }

        .timing {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Defer::terminate() Test</h1>
            <p>Testing post-response execution</p>
        </div>

        <div class="content">
            <div class="status-card">
                <h3>✅ Response Sent Successfully!</h3>
                <p><strong>Test ID:</strong> <code>{{ $testId }}</code></p>
                <p><strong>Page Load Time:</strong> <span class="timing" id="loadTime">Calculating...</span></p>
                <p><strong>Status:</strong> If Defer::terminate() works, background tasks should be running now.</p>
            </div>

            <div class="info-card">
                <h3>🔍 What This Test Does:</h3>
                <ul>
                    <li>Schedules 3 tasks using <code class="highlight">Defer::terminate()</code></li>
                    <li>Task 1: Heavy work simulation (2 second sleep)</li>
                    <li>Task 2: Cleanup simulation (0.5 second)</li>
                    <li>Task 3: Final completion task</li>
                    <li>All tasks should execute <strong>after</strong> this page loads</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>📊 Success Indicators:</h3>
                <ul>
                    <li>Page loads quickly (under 100ms)</li>
                    <li><code>RESPONSE:</code> appears before <code>TERMINATE_*</code> in log</li>
                    <li>Background tasks take ~2.5 seconds total</li>
                    <li>Tasks execute in the order they were scheduled</li>
                </ul>
            </div>

            <div class="log-section">
                <h3>📝 Execution Log</h3>
                <p>Click "Refresh Log" to see the terminate tasks executing:</p>

                <div class="controls">
                    <button class="btn btn-success" onclick="refreshLog()">🔄 Refresh Log</button>
                    <a href="/defer-test" class="btn btn-primary">🆕 New Test</a>
                    <a href="/" class="btn btn-secondary">🏠 Home</a>
                </div>

                <div class="log-output" id="logDisplay">
                    Click "Refresh Log" to view execution timeline...
                </div>
            </div>

            <div class="info-card" style="margin-top: 30px;">
                <h4>🎯 Expected Log Sequence:</h4>
                <ol>
                    <li><code>START:</code> Response preparation</li>
                    <li><code>RESPONSE:</code> About to send response</li>
                    <li><code>TERMINATE_1:</code> First task starts (after response sent)</li>
                    <li><code>TERMINATE_2:</code> Second task starts</li>
                    <li><code>TERMINATE_3:</code> Third task starts</li>
                    <li><code>TERMINATE_2:</code> Second task completes</li>
                    <li><code>TERMINATE_1:</code> First task completes (after 2s)</li>
                    <li><code>COMPLETE:</code> All tasks finished</li>
                </ol>
            </div>
        </div>
    </div>

    <script>
        const startTime = performance.now();

        window.addEventListener('load', function() {
            const loadTime = Math.round(performance.now() - startTime);
            document.getElementById('loadTime').textContent = loadTime + 'ms';

            console.log('Page loaded in:', loadTime + 'ms');

            setTimeout(refreshLog, 3000);
        });

        async function refreshLog() {
            try {
                const response = await fetch('/defer-status');
                const data = await response.json();

                const logDisplay = document.getElementById('logDisplay');

                if (data.exists) {
                    logDisplay.textContent = data.content;
                } else {
                    logDisplay.textContent = 'No log file found - terminate tasks may not have executed yet.';
                }

                logDisplay.scrollTop = logDisplay.scrollHeight;

            } catch (error) {
                document.getElementById('logDisplay').textContent = 'Error loading log: ' + error.message;
            }
        }

        console.log('Defer::terminate() test page loaded at:', new Date().toISOString());
    </script>
</body>

</html>
