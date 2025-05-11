<?php
session_start();
$error = "";
$success = "";

// Initialize database if it doesn't exist
if (!file_exists('sqli_demo.db')) {
    include 'init_db.php';
}

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $success = "You are already logged in as " . $_SESSION['username'];
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Vulnerable SQL query - NO SANITIZATION
    $db = new SQLite3('sqli_demo.db');
    $query = "SELECT id, username, is_admin FROM users WHERE username = '$username' AND password = '$password'";
    
    $result = $db->query($query);
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($user) {
        // Login successful
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        $success = "Login successful! Welcome " . $user['username'];
        
        if ($user['is_admin']) {
            $success .= " (Admin)";
        }
    } else {
        // Login failed
        $error = "Invalid username or password";
    }
    
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection - Login Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .warning {
            background-color: #ffe6e6;
            border-left: 5px solid #ff6666;
            padding: 10px;
            margin-bottom: 20px;
        }
        .query-display {
            background-color: #f5f5f5;
            padding: 10px;
            border-left: 5px solid #333;
            font-family: monospace;
            margin: 20px 0;
            overflow-x: auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            padding: 8px;
            width: 100%;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .home-link {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>SQL Injection - Login Bypass Demo</h1>
    
    <div class="warning">
        <strong>Vulnerability:</strong> This login form is vulnerable to SQL injection. Try entering <code>' OR 1=1;--</code> in the password field to bypass authentication.
    </div>
    
    <div class="container">
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
            <form method="post" action="logout.php">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
            </form>
        <?php endif; ?>
        
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="query-display">
                <p><strong>Query executed:</strong></p>
                <pre><?php echo $query; ?></pre>
            </div>
        <?php endif; ?>
    </div>
    
    <a href="index.php" class="home-link">Back to Home</a>
</body>
</html> 