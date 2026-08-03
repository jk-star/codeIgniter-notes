# Chapter 18 — REST API Development

- **Goal:** CodeIgniter 4 me REST API banana jise React, React Native, Flutter, Android, Vue ya kisi bhi frontend se use kiya ja sake.

## REST API Kya Hai?

- REST API ek aisa interface hai jisse frontend aur backend aapas me JSON format me data exchange karte hain.

**Example:**

<code><pre>
React App
      │
      │ HTTP Request
      ▼
CodeIgniter 4 API
      │
      ▼
MySQL
      │
      ▼
JSON Response
      │
      ▼
React UI
</pre></code>

## REST API — Complete Product CRUD Example

## 1. Database Table

- Pehle products table:

<code><pre>
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);
</pre></code>

Sample: 

<code><pre>
INSERT INTO products (name, price)
VALUES
('Laptop', 50000),
('Mobile', 20000),
('Mouse', 800);
</pre></code>

## 2. Product Model

Create:

`php spark make:model ProductModel`

File:

`app/Models/ProductModel.php`

Code:

<code><pre>
&lt;?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'name',
        'price'
    ];
}
</pre></code>

- `allowedFields` important hai:

<code><pre>
protected $allowedFields = [
    'name',
    'price'
];
</pre></code>

- Iske bina mass insert/update me fields save nahi hongi.

## 3. API Controller Create Karo

Command: 

`php spark make:controller Api/ProductController --restful`

## 4. Complete ProductController

<code><pre>
<?php

namespace App\Controllers\Api;

use App\Models\ProductModel;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    protected $modelName = ProductModel::class;

    protected $format = 'json';


    // GET /api/products
    public function index()
    {
        $products = $this->model->findAll();

        return $this->respond([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' => $products
        ]);
    }


    // GET /api/products/1
    public function show($id = null)
    {
        $product = $this->model->find($id);

        if (! $product) {

            return $this->failNotFound(
                'Product not found'
            );
        }

        return $this->respond([
            'status' => true,
            'message' => 'Product fetched successfully',
            'data' => $product
        ]);
    }


    // POST /api/products
    public function create()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'name'  => 'required|min_length[3]',
            'price' => 'required|numeric'
        ];

        if (! $this->validateData($data ?? [], $rules)) {

            return $this->failValidationErrors(
                $this->validator->getErrors()
            );
        }

        $this->model->insert([
            'name'  => $data['name'],
            'price' => $data['price']
        ]);

        $id = $this->model->getInsertID();

        $product = $this->model->find($id);

        return $this->respondCreated([
            'status' => true,
            'message' => 'Product added successfully',
            'data' => $product
        ]);
    }


    // PUT /api/products/1
    public function update($id = null)
    {
        $product = $this->model->find($id);

        if (! $product) {

            return $this->failNotFound(
                'Product not found'
            );
        }

        $data = $this->request->getJSON(true);

        $rules = [
            'name'  => 'required|min_length[3]',
            'price' => 'required|numeric'
        ];

        if (! $this->validateData($data ?? [], $rules)) {

            return $this->failValidationErrors(
                $this->validator->getErrors()
            );
        }

        $this->model->update($id, [
            'name'  => $data['name'],
            'price' => $data['price']
        ]);

        $updatedProduct = $this->model->find($id);

        return $this->respond([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $updatedProduct
        ]);
    }


    // DELETE /api/products/1
    public function delete($id = null)
    {
        $product = $this->model->find($id);

        if (! $product) {

            return $this->failNotFound(
                'Product not found'
            );
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
</pre></code>