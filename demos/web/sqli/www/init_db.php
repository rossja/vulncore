<?php
// Initialize the SQLite database
$db_file = 'sqli_demo.db';

// Remove existing database if it exists
if (file_exists($db_file)) {
    unlink($db_file);
}

// Create new database
$db = new SQLite3($db_file);

// Create users table
$db->exec('
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        is_admin INTEGER DEFAULT 0
    )
');

// Create products table
$db->exec('
    CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        price REAL NOT NULL
    )
');

// Insert sample users
$db->exec("
    INSERT INTO users (username, password, is_admin) VALUES 
    ('admin', 'secretpassword123', 1),
    ('john', 'password123', 0),
    ('alice', 'alicepass', 0),
    ('bob', 'bobsecure', 0),
    ('claire', 'clairep@ss', 0)
");

// Insert sample products
$db->exec("
    INSERT INTO products (name, description, price) VALUES 
    ('Smartphone', 'Latest model with great features', 999.99),
    ('Laptop', 'Powerful laptop for work and gaming', 1299.99),
    ('Headphones', 'Noise cancelling wireless headphones', 199.99),
    ('Smart Watch', 'Track your fitness and stay connected', 249.99),
    ('Tablet', 'Lightweight and powerful tablet', 499.99)
");

$db->close();

echo "Database initialized successfully!";
?> 