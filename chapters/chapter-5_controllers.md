# Chapter 5 — Controllers in CodeIgniter 4

- Controller ka main kaam hai request handle karna, required logic chalana aur response/view return karna.

## 1. Controller kahan hota hai?

**Location:**

`app/Controllers/`

**Example:**

`app/Controllers/Product.php`

**Basic Controller:**

<code><pre>
&lt;?php
namespace App\Controllers;

class Product extends BaseController
{
    public function index()
    {
        return "Product Page";
    }
}
</pre></code>

**Route:**

`$routes->get('/products', 'Product::index');`

**Flow:**

<code><pre>
/products
    ↓
Product Controller
    ↓
index()
    ↓
Product Page
</pre></code>

## 2. Controller Method

- Ek Controller me multiple methods ho sakte hain.

<code><pre>
class Product extends BaseController
{
    public function index()
    {
        return "Product List";
    }

    public function create()
    {
        return "Add Product";
    }

    public function edit($id)
    {
        return "Edit Product: " . $id;
    }
}
</pre></code>

**Routes:**

<code><pre>
$routes->get('/products', 'Product::index');

$routes->get('/product/create', 'Product::create');

$routes->get('/product/edit/(:num)', 'Product::edit/$1');
</pre></code>

**So:**

<code><pre>
/products
       → index()

/product/create
       → create()

/product/edit/10
       → edit(10)
</pre></code>

## 3. Controller se View Load Karna ⭐⭐⭐

**Controller:**

<code><pre>
public function index()
{
    return view('products/index');
}
</pre></code>

**View:**

`app/Views/products/index.php`

<code><pre>
&lt;h1&gt;Product List&lt;/h1&gt;
&lt;p&gt;All products will appear here.&lt;/p&gt;
</pre></code>

**Structure:**

<code><pre>
Product Controller
       ↓
view('products/index')
       ↓
app/Views/products/index.php
</pre></code>

## 4. Controller → View Data ⭐

- Real application me Controller View ko data bhejega.

**Controller:**

<code><pre>
public function index()
{
    $data = [
        'title' => 'Product List',
        'product' => 'Laptop',
        'price' => 50000
    ];

    return view('products/index', $data);
}
</pre></code>

**View:**

<code><pre>
&lt;h1&gt;<?= esc($title) ?>&lt;/h1&gt;

&lt;p&gt;Product: <?= esc($product) ?>&lt;/p&gt;

&lt;p&gt;Price: ₹<?= esc($price) ?>&lt;/p&gt;
</pre></code>

## 5. Request Object ⭐

- Controller ko browser/form se jo data milta hai, use handle karne ke liye CI4 me Request object bahut important hai.

- Controller me available hota hai:

`$this->request`

**For example:**

`$name = $this->request->getPost('name');`

**Ye form ke:**

`<input type="text" name="name">`

ki value lega.

## 6. POST Data Lena ⭐

**Form:**

<code><pre>
&lt;form action="/product/save" method="post"&gt;
    &lt;input type="text" name="name"&gt;
    &lt;input type="number" name="price"&gt;
    &lt;button type="submit">Save&lt;/button&gt;
&lt;/form&gt;
</pre></code>

**Route:**

`$routes->post('/product/save', 'Product::save');`

**Controller:**

<code><pre>
public function save()
{
    $name = $this->request->getPost('name');
    $price = $this->request->getPost('price');

    return $name . ' - ' . $price;
}
</pre></code>

## 7. GET Data Lena

**Suppose URL:**

`/products?category=laptop`

**Yahan:**

`category=laptop`

- query string hai.

**Controller:**

<code><pre>
public function index()
{
    $category = $this->request->getGet('category');

    return $category;
}
</pre></code>

**Output:** `laptop`

**Remember:** `$this->request->getGet('category');`

- GET/query-string data ke liye.

`$this->request->getPost('name');`

- POST data ke liye.

## 8. Route Parameter vs GET Parameter

**Route Parameter**

URL: `/product/10`

Route: `$routes->get('/product/(:num)', 'Product::show/$1');`

Controller:

<code><pre>
public function show($id)
{
    return $id;
}
</pre></code>

Here: `$id = 10`

**Query String**

URL: `/products?category=laptop`

Controller: `$category = $this->request->getGet('category');`

So:

<code><pre>
/product/10
         ↓
Method parameter


?category=laptop
          ↓
getGet()
</pre></code>

## 9. Redirect ⭐

Form save hone ke baad usually:

<code><pre>
Save
 ↓
Database
 ↓
Redirect
 ↓
Product List
</pre></code>

`return redirect()->to('/products');`

Example:

<code><pre>
public function save()
{
    // product save code

    return redirect()->to('/products');
}
</pre></code>

Browser automatically `/products` par chala jayega.

## 10. Redirect Back

Previous page par bhejna ho:

`return redirect()->back();`

Useful example:

<code><pre>
Form
 ↓
Validation Error
 ↓
Previous Form
</pre></code>

## 11. Flash Message ⭐

- Suppose product save hua.
- Hum redirect ke saath success message bhej sakte hain:

<code><pre>
return redirect()
    ->to('/products')
    ->with('success', 'Product added successfully');
</pre></code>

View:

<code><pre>
&lt;?php if (session()->getFlashdata('success')): ?&gt;
    &lt;p&gt;
        &lt;?= esc(session()->getFlashdata('success')) ?&gt;
    &lt;/p&gt;
&lt;?php endif; ?&gt;
</pre></code>

Output:

`Product added successfully`

## 12. Controller Generate Karna — Spark

Controller manually create kar sakte hain.

Ya command:

`php spark make:controller Product`

CI4 automatically banayega:

`app/Controllers/Product.php`

Result roughly:

<code><pre>
&lt;?php

namespace App\Controllers;

class Product extends BaseController
{
    public function index()
    {
        //
    }
}
</pre></code>

## 13. Controller Folder Organization

Small project:

<code><pre>
Controllers/
├── Home.php
├── Product.php
├── User.php
└── Auth.php
</pre></code>

Large project me organize kar sakte hain:

<code><pre>
Controllers/
│
├── Admin/
│   ├── Dashboard.php
│   ├── Product.php
│   └── User.php
│
├── Api/
│   └── Product.php
│
├── Home.php
└── Auth.php
</pre></code>

Admin Controller:

<code><pre>
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Product extends BaseController
{
    public function index()
    {
        return "Admin Products";
    }
}
</pre></code>

Route:

<code><pre>
$routes->get(
    '/admin/products',
    'Admin\Product::index'
);
</pre></code>

- Ye bade projects me useful hota hai.

## 14. Controller me kya hona chahiye?

Controller ka kaam mainly:

<code><pre>
Request lena
      ↓
Validate karna
      ↓
Model ko call karna
      ↓
Result lena
      ↓
View / Redirect return karna
</pre></code>

Example:

<code><pre>
public function store()
{
    $name = $this->request->getPost('name');

    // validation

    // model → insert

    return redirect()->to('/products');
}
</pre></code>

## 15. Real CRUD Controller Structure ⭐

Aage Product CRUD banega to Controller roughly:

<code><pre>
class Product extends BaseController
{
    public function index()
    {
        // List
    }

    public function create()
    {
        // Add form
    }

    public function store()
    {
        // Save
    }

    public function edit($id)
    {
        // Edit form
    }

    public function update($id)
    {
        // Update
    }

    public function delete($id)
    {
        // Delete
    }
}
</pre></code>

Ye pattern **bahut important** hai:

<code><pre>
index()  → List

create() → Add Form

store()  → Save

edit()   → Edit Form

update() → Update

delete() → Delete
</pre></code>

## 🧠 Interview Questions

**Q1. Controller ka kaam kya hai?**

- Request handle karta hai aur Model/View ke beech application flow manage karta hai.

**Q2. POST data kaise lete hain?**

`$this->request->getPost('name');`

**Q3. GET query parameter?**

`$this->request->getGet('name');`

**Q4. View kaise load karte hain?**

`return view('products/index');`

**Q5. Redirect kaise karte hain?**

`return redirect()->to('/products');`

**Q6. Previous page par redirect?**

`return redirect()->back();`

**Q7. Controller CLI se kaise banega?**

`php spark make:controller Product`
