# Chapter 9 — Complete CRUD Application 

## Complete CRUD Controller

<code><pre>
&lt;?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }


    // READ
    public function index()
    {
        $data = [
            'title' => 'Product List',
            'products' => $this->productModel->findAll()
        ];

        return view('products/index', $data);
    }


    // CREATE FORM
    public function create()
    {
        $data['title'] = 'Add Product';

        return view('products/create', $data);
    }


    // INSERT
    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price')
        ];

        $this->productModel->insert($data);

        return redirect()
            ->to('/products')
            ->with('success', 'Product added successfully');
    }


    // EDIT FORM
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Product',
            'product' => $this->productModel->find($id)
        ];

        return view('products/edit', $data);
    }


    // UPDATE
    public function update($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price')
        ];

        $this->productModel->update($id, $data);

        return redirect()
            ->to('/products')
            ->with('success', 'Product updated successfully');
    }


    // DELETE
    public function delete($id)
    {
        $this->productModel->delete($id);

        return redirect()
            ->to('/products')
            ->with('success', 'Product deleted successfully');
    }
}

</pre></code>

## CRUD ko Ek Diagram me Samjho

<code><pre>
                 PRODUCT CRUD

                     │
       ┌─────────────┼─────────────┐
       ↓             ↓             ↓

     READ          CREATE        UPDATE
       │             │             │
    index()       create()       edit($id)
       │             │             │
   findAll()        Form         find($id)
       │             │             │
      View         store()       Edit Form
                     │             │
                  insert()      update($id)


                     DELETE
                        │
                   delete($id)
                        │
                     Model
                        │
                     Database
</pre></code>