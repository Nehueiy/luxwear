import sys
import mysql.connector
import json

# Get product_id from PHP argument
product_id = int(sys.argv[1]) if len(sys.argv) > 1 else 1

# Connect to MySQL (adjust to match db.php)
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="luxwear"
)
cursor = conn.cursor(dictionary=True)

# Simple recommendation: fetch 2 products from same category
cursor.execute("""
    SELECT c.id as category_id 
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = %s
""", (product_id,))
row = cursor.fetchone()

recommendations = []
if row:
    cursor.execute("""
        SELECT id, title, price, image 
        FROM products 
        WHERE category_id = %s AND id != %s 
        LIMIT 2
    """, (row['category_id'], product_id))
    recommendations = cursor.fetchall()

conn.close()

# Print as JSON (PHP will decode this)
print(json.dumps(recommendations))

