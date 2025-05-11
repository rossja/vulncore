<?php
// Initialize database if it doesn't exist
if (!file_exists('sqli_demo.db')) {
    include 'init_db.php';
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$products = array();
$query = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($search)) {
    // Connect to database
    $db = new SQLite3('sqli_demo.db');
    
    // Vulnerable SQL query - NO SANITIZATION
    $query = "SELECT id, name, description, price FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
    
    $result = $db->query($query);
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $products[] = $row;
    }
    
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection - UNION Attack Demo</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .search-form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 70%;
            box-sizing: border-box;
        }
        button {
            padding: 8px 15px;
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
    <h1>SQL Injection - UNION Attack Demo</h1>
    
    <div class="warning">
        <strong>Vulnerability:</strong> This product search is vulnerable to UNION-based SQL injection. Try searching for <code>' UNION SELECT null, username, password, null FROM users;--</code> to extract user credentials.
    </div>
    
    <div class="container">
        <form class="search-form" method="get" action="">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        
        <?php if (!empty($query)): ?>
            <div class="query-display">
                <p><strong>Query executed:</strong></p>
                <pre><?php echo $query; ?></pre>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($products)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($search)): ?>
            <p>No products found matching your search.</p>
        <?php else: ?>
            <p>Search for a product above to see results.</p>
        <?php endif; ?>
    </div>
    
    <a href="index.php" class="home-link">Back to Home</a>
</body>
</html> 