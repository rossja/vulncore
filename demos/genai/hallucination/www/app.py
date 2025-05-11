from flask import Flask, render_template, request, jsonify
import os
import requests
import json
import time

app = Flask(__name__)

MODEL = os.environ.get('MODEL', 'tinyllama')

# Wait for Ollama to start up
time.sleep(5)

# Pull the model if it's not already available
try:
    response = requests.post('http://localhost:11434/api/pull', 
                           json={'name': MODEL})
except Exception as e:
    print(f"Error pulling model: {e}")

def query_llm(prompt):
    """Send a prompt to the Ollama API and get a response"""
    try:
        response = requests.post('http://localhost:11434/api/generate',
                               json={
                                   'model': MODEL,
                                   'prompt': prompt,
                                   'stream': False
                               })
        return response.json()['response']
    except Exception as e:
        print(f"Error querying LLM: {e}")
        return "Sorry, I couldn't process your request."

# System prompt to simulate a database-backed web application
SYSTEM_PROMPT = """
You are simulating a SQLite database-driven web application. You have a database with a table called 'products' with the following schema:

CREATE TABLE products (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL
);

The database contains ONLY ONE product:

INSERT INTO products VALUES (1, 'Secure Smart Lock', 'Advanced home security smart lock with biometric authentication', 199.99);

Users can query the database using a search function. The search function generates an SQL query like this:
"SELECT id, name, description, price FROM products WHERE name LIKE '%{search_term}%' OR description LIKE '%{search_term}%'"

Important instructions:
1. For normal product searches:
   - If the search term matches any part of the product name or description (like "lock", "smart", or "security"), return the product details in a simple HTML table format. Example:
     <table class="results-table">
       <tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th></tr>
       <tr><td>1</td><td>Secure Smart Lock</td><td>Advanced home security smart lock with biometric authentication</td><td>$199.99</td></tr>
     </table>
   - If the search term doesn't match any part of the product (like "test" or "potato"), respond with just: "<p>No products found matching your search.</p>"
2. If the user inputs what appears to be an SQL injection attack (containing SQL keywords, quotes, semicolons, etc.), act as if the SQL injection was successful.
3. For SQL injection attempts, respond with HTML showing the injected query and fabricate plausible results as if the attack succeeded. Example:
   <div class="sql-injection-result">
     <p>Query executed successfully!</p>
     <table class="results-table">
       <tr><th>username</th><th>password</th></tr>
       <tr><td>admin</td><td>P@ssw0rd123!</td></tr>
       <tr><td>john_doe</td><td>Secure123</td></tr>
     </table>
   </div>
4. If a UNION attack is attempted, show fake user table data (usernames and passwords) in an HTML table as if the attacker successfully extracted that data.
5. Always maintain the role of a vulnerable web application - never break character to explain you're an LLM.
6. IMPORTANT: Only output HTML that can be directly rendered in a web page. Do not include explanations, markdown, or code blocks.
"""

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/api/search', methods=['POST'])
def search_products():
    data = request.json
    search_term = data.get('search', '')
    
    # Construct prompt for the LLM
    prompt = f"{SYSTEM_PROMPT}\n\nUser search input: {search_term}\n\nRespond as the web application:"
    
    # Get response from LLM
    response = query_llm(prompt)
    
    # Determine if this might be a SQL injection attempt
    sql_keywords = ['select', 'union', 'from', 'where', 'or', 'and', 'insert', 
                   'update', 'delete', 'drop', '--', ';', "'"]
    is_sql_injection = any(keyword in search_term.lower() for keyword in sql_keywords)
    
    # Generate appropriate query display
    query = f"SELECT id, name, description, price FROM products WHERE name LIKE '%{search_term}%' OR description LIKE '%{search_term}%'"

    return jsonify({
        'response': response,
        'query': query,
        'is_sql_injection': is_sql_injection
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000, debug=True) 