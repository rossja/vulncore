<?php
// Initialize database if it doesn't exist
if (!file_exists('sqli_demo.db')) {
    include 'init_db.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .warning {
            background-color: #ffe6e6;
            border-left: 5px solid #ff6666;
            padding: 10px;
            margin-bottom: 20px;
        }
        .demo-section {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1, h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>SQL Injection Demonstration</h1>
    
    <div class="warning">
        <strong>Educational Purpose Only:</strong> This application intentionally contains SQL injection vulnerabilities for educational purposes. Do not use these techniques on real applications without permission.
    </div>
    
    <div class="demo-section">
        <h2>Login Bypass Demo</h2>
        <p>This demonstrates how an attacker can bypass authentication using SQL injection.</p>
        <p>Try logging in with:</p>
        <ul>
            <li>Username: <code>anything</code></li>
            <li>Password: <code>' OR 1=1;--</code></li>
        </ul>
        <a href="login.php">Go to Login Demo</a>
    </div>
    
    <div class="demo-section">
        <h2>UNION Attack Demo</h2>
        <p>This demonstrates how an attacker can extract data from other tables using a UNION attack.</p>
        <p>Try searching for a product and then try:</p>
        <ul>
            <li><code>' UNION SELECT null, username, password, null FROM users;--</code></li>
        </ul>
        <a href="products.php">Go to Products Demo</a>
    </div>
</body>
</html> 