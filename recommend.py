import sys
import mysql.connector
import json
from decimal import Decimal

# Get product_id from PHP argument
product_id = int(sys.argv[1]) if len(sys.argv) > 1 else 1

# Connect to MySQL (adjust if needed)
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="luxwear"
)
cursor = conn.cursor(dictionary=True)

# Fetch category of current product
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

# Convert Decimal → float
def convert(o):
    if isinstance(o, Decimal):
        return float(o)
    raise TypeError

print(json.dumps(recommendations, default=convert))
