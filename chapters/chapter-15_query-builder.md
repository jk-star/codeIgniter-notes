# Chapter 15 — Query Builder (Advanced Database Operations)

- **Goal**: Database ko efficiently query karna bina raw SQL likhe.

## Query Builder kya hai?

- Query Builder ek class hai jo SQL queries ko PHP methods se likhne deti hai.

**Example**

- ❌ Raw SQL

`SELECT * FROM products WHERE price > 1000;`

✅ Query Builder

<code><pre>
$db = db_connect();
$builder = $db->table('products');
$builder->where('price >', 1000);
$result = $builder->get()->getResultArray();
</pre></code>

- Output dono ka same hai.

## Query Builder Flow
<code><pre>
Controller
      │
      ▼
Query Builder
      │
      ▼
MySQL
      │
      ▼
Result Array
      │
      ▼
    View
</pre></code>

## 1. Builder Object
<code><pre>
$db = db_connect();
$builder = $db->table('products');
</pre></code>

**Ab**
- `$builder` products table represent karta hai.

## 2. SELECT ⭐

Sab records

<code><pre>
$products = $builder ->get() ->getResultArray();
</pre></code>
**SQL** : `SELECT * FROM products;`

**Specific Columns**

<code><pre>
$products = $builder ->select('id,name,price') ->get() ->getResultArray();
</pre></code>

**SQL** : `SELECT id,name,price FROM products;`

## 3. WHERE ⭐

`$builder ->where('id',1);`

**SQL** : `WHERE id = 1`

Multiple : 
<code><pre>
$builder
->where('price >',1000)
->where('price <',50000);

</pre></code>

**SQL** : ` WHERE price>1000 AND price<50000 `

## 4. OR WHERE ⭐

<code><pre>
$builder
->where('price',50000)
->orWhere('price',20000);

</code></pre>

**SQL** : `WHERE price=50000 OR price=20000`

## 5. LIKE ⭐

`$builder ->like('name','lap');`

**SQL** : `WHERE name LIKE '%lap%'`

**Before**
`$builder ->like('name','lap','before');`

**SQL** : `LIKE '%lap'`

**After**
`$builder ->like('name','lap','after');`

SQL : `LIKE 'lap%'`

## 6. ORDER BY ⭐
**Ascending** : `$builder ->orderBy('price','ASC');`
 
**SQL** : `ORDER BY price ASC`

**Descending** `$builder ->orderBy('price','DESC');`

- Highest price first.

**Multiple**
<code><pre>
$builder
->orderBy('price','DESC')
->orderBy('name','ASC');

</pre></code>

## 7. LIMIT ⭐

**Top 5** `$builder ->limit(5);`

**SQL** `LIMIT 5`

**Offset**  `$builder ->limit(5,10);`

**SQL** `LIMIT 10,5`

- Useful Pagination.

## 8. FIRST ROW

`$product = $builder ->get() ->getFirstRow();`

**Single Array**

`$product = $builder ->get() ->getRowArray();`

## 9. COUNT ⭐

**Total Records**

`$total = $builder ->countAll();`

**Condition**

`$total = $builder ->where('price >',1000) ->countAllResults();`

**SQL** `SELECT COUNT(*) FROM products WHERE price>1000`

## 10. INSERT ⭐

<code><pre>
$data=[ 'name'=>'Mouse', 'price'=>800 ];
$builder ->insert($data);
</pre></code>

**SQL** `INSERT INTO products`

**Multiple Insert**

<code><pre>
$builder ->insertBatch([
    [
    'name'=>'A'
    ],
    [
    'name'=>'B'
    ]
]);

</pre></code>

## 11. UPDATE ⭐

<code><pre>
$builder
->where('id',1)
->update([
'price'=>60000
]);

</pre></code>

**SQL** : `UPDATE products SET price=60000 WHERE id=1`

## 12. DELETE ⭐

`$builder ->where('id',1) ->delete();`

**SQL** `DELETE FROM products WHERE id=1`

## 13. JOIN ⭐

- Most Important Interview Topic

Tables : `products`
<code><pre>

id
category_id
name
</pre></code>

Table `Category`

<code><pre>
id
category_name
</pre></code>

**Query**

<code><pre>
$builder ->select(' products.*, categories.category_name ');
$builder ->join( 'categories', 'categories.id = products.category_id' );
$result = $builder ->get() ->getResultArray();
</pre></code>

<code><pre>
SELECT products.*, categories.category_name FROM products
JOIN categories ON categories.id = products.category_id
</pre></code>

**Flow**

<code><pre>
Products Table
      │
    JOIN
      │
Categories Table
      │
Single Result
</pre></code>

## 14. LEFT JOIN

`$builder ->join( 'categories', 'categories.id=products.category_id', 'left' );`

- Returns all products.

## 15. RIGHT JOIN
`$builder ->join( 'categories', 'categories.id=products.category_id', 'right' );`
- Less common.

## 16. GROUP BY ⭐
Example

Need

<code><pre>
Category
Total Products
</pre></code>

Query

`$builder ->select(' category_id, COUNT(*) total ') ->groupBy('category_id');`

**SQL** :  `GROUP BY category_id`

## 17. HAVING

`$builder ->having('total >',5);`

**SQL** `HAVING total>5`

## 18. WHERE IN ⭐

`$builder ->whereIn( 'id', [1,2,5] );`

**SQL** `WHERE id IN ( 1, 2, 5 )`

## 19. WHERE NOT IN

`$builder ->whereNotIn( 'id', [1,2] );`

## 20. Pagination ⭐

Instead of `findAll();`

Use

`$products = $model ->paginate(10);`

Links

`<?= $model ->pager ->links() ?>`

Automatic

Previous 1 2 3 Next

## 21. Query Builder Chaining ⭐



<code><pre>
$products=  $builder

->select('id,name')
->where('price >',1000)
->like('name','lap')
->orderBy('price','DESC')
->limit(10)
->get()
->getResultArray();

</pre></code>

**Flow**

<code><pre>
SELECT
↓
WHERE
↓
LIKE
↓
ORDER BY
↓
LIMIT
↓
RESULT
</pre></code>
