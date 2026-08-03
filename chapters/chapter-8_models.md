# Chapter 8 — Models 

## 1. Model kahan hota hai?

**Location:**

`app/Models/`

**Product ke liye:**

`app/Models/ProductModel.php`

**CLI se bhi bana sakte hain:**

`php spark make:model ProductModel`

## 2. Basic Model ⭐

`ProductModel.php`

<code><pre>
&lt;?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'price'
    ];
}
</pre></code>

- Bas itna likhne ke baad Model `products` table ke saath kaam kar sakta hai.

## 4. $allowedFields ⭐

<code><pre>
protected $allowedFields = [
    'name',
    'price'
];
</pre></code>

- Model ke through kaunse fields insert/update ho sakte hain, wo define karta hai.

## 5. Controller me Model Use Karna ⭐

Product Controller:

`app/Controllers/Product.php`

Top par Model import:

`use App\Models\ProductModel;`

<code><pre>
&lt;?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    public function index()
    {
        $model = new ProductModel();

        $products = $model->findAll();

        return $this->response->setJSON($products);
    }
}
</pre></code>

**Flow:**

<code><pre>
Product Controller
      ↓
new ProductModel()
      ↓
findAll()
      ↓
products table
</pre></code>

## 6. findAll() ⭐

- All records retrieve karne ke liye:

`$products = $model->findAll();`

- **equivalent SQL:** `SELECT * FROM products;`

## 7. View me Records Bhejo

**Controller:**

<code><pre>
public function index()
{
    $model = new ProductModel();

    $data['products'] = $model->findAll();

    return view('products/index', $data);
}
</pre></code>

**View:**

<code><pre>
&lt;h1&gt;Products&lt;/h1&gt;

&lt;?php foreach ($products as $product): ?&gt;
    &lt;p&gt;
        &lt;?= esc($product['name']) ?&gt;
        -
        ₹&lt;?= esc($product['price']) ?&gt;
    &lt;/p&gt;
&lt;?php endforeach; ?&gt;
</pre></code>

**Ab complete flow:**

<code><pre>
/products
    ↓
Route
    ↓
Product::index()
    ↓
ProductModel
    ↓
findAll()
    ↓
products table
    ↓
Controller
    ↓
View
    ↓
Product List
</pre></code>

## 8. find() — Single Record ⭐

`$product = $model->find(2);`

**Meaning:** `Product ID = 2`

**Example Controller:**

<code><pre>
public function show($id)
{
    $model = new ProductModel();

    $data['product'] = $model->find($id);

    return view('products/show', $data);
}
</pre></code>

**Route:**

<code><pre>
$routes->get(
    '/product/(:num)',
    'Product::show/$1'
);
</pre></code>

## 9. insert() ⭐
- New record add karna:

<code><pre>
$model->insert([
    'name'  => 'Mouse',
    'price' => 800
]);

</pre></code>

**Form se Insert**

Form:

<code><pre>
&lt;form action="/product/save" method="post"&gt;
    &lt;input type="text" name="name"&gt;
    &lt;input type="number" name="price"&gt;
    &lt;button type="submit"&gt;
        Save
    &lt;/button&gt;
&lt;/form&gt;
</pre></code>

Controller:

<code><pre>
public function save()
{
    $model = new ProductModel();

    $model->insert([
        'name'  => $this->request->getPost('name'),
        'price' => $this->request->getPost('price')
    ]);

    return redirect()->to('/products');
}
</pre></code>

## 10. update() ⭐

Existing record update:

<code><pre>
$model->update(2, [
    'name'  => 'Samsung Mobile',
    'price' => 25000
]);

</pre></code>

Controller:

<code><pre>
public function update($id)
{
    $model = new ProductModel();

    $model->update($id, [
        'name'  => $this->request->getPost('name'),
        'price' => $this->request->getPost('price')
    ]);

    return redirect()->to('/products');
}
</pre></code>

## 11. delete() ⭐

Record delete:

`$model->delete(3);`

Controller:

<code><pre>
public function delete($id)
{
    $model = new ProductModel();

    $model->delete($id);

    return redirect()->to('/products');
}
</pre></code>

## 13. where() ⭐

Condition lagani ho:

<code><pre>
$products = $model
    ->where('price', 50000)
    ->findAll();
</pre></code>

## 14. first()

First matching record:

<code><pre>
$product = $model
    ->where('name', 'Laptop')
    ->first();
</pre></code>

Difference:

<code><pre>
findAll()
   ↓
Multiple records


first()
   ↓
First matching record
</pre></code>

## 15. orderBy()

Products price ke according: 

<code><pre>
$products = $model
    ->orderBy('price', 'DESC')
    ->findAll();
</pre></code>

## 16. like() — Search

Suppose user searches: `lap`

Use:
<code><pre>
$products = $model
    ->like('name', 'lap')
    ->findAll();
</pre></code>

## 17. Model ko Property Bana Sakte Hain ⭐

Abhi har method me:

`$model = new ProductModel();`

likh rahe hain.

Instead Controller me:

<code><pre>
class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data['products'] =
            $this->productModel->findAll();

        return view('products/index', $data);
    }
}
</pre></code>

Ab doosre methods me:

`$this->productModel->find($id);`

`$this->productModel->insert($data);`

`$this->productModel->update($id, $data);`

`$this->productModel->delete($id);`

use kar sakte hain.

Isse repeated: `new ProductModel();` kam ho jayega.

## 18. Timestamps ⭐

Table me agar: 

`created_at`
`updated_at`

- columns hain, CI4 automatically timestamps manage kar sakta hai.

Model:

`protected $useTimestamps = true;`

Example: 

<code><pre>
class ProductModel extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'price'
    ];

    protected $useTimestamps = true;
}
</pre></code>

Table me typically:

`created_at`
`updated_at`

## 19. Model ka Final Structure

Basic real-world Model:

<code><pre>
<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'price'
    ];

    protected $useTimestamps = true;
}
</pre></code>

- Bas ye structure samajh lena bahut important hai.

## 🧠 Interview Questions

**Q1. Model ka kaam?**
- Database/data related operations handle karna.

**Q2. Model kahan hota hai?**

`app/Models/`

**Q3. $table?**

`protected $table = 'products';`

- Model kis database table ko use karega.

**Q4. $allowedFields kya hai?**

- Insert/update ke liye allowed fields define karta hai.

**Q5. All records?**

`$model->findAll();`

**Q6. Single record?**

`$model->find($id);`

**Q7. Insert?**

`$model->insert($data);`

**Q8. Update?**

`$model->update($id, $data);`

**Q9. Delete?**

`$model->delete($id);`