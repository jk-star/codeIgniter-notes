# Chapter 10 — Forms & Validation 

- Validation = Data save/update karne se pehle check karna ki user ka input valid hai ya nahi.

## Complete Create Form ⭐

`app/Views/products/create.php`

<code><pre>
&lt;?= $this->extend('layouts/main') ?&gt;

&lt;?= $this->section('content') ?&gt;

&lt;h1&gt;Add Product&lt;/h1&gt;


&lt;?php if (session()->getFlashdata('errors')): ?&gt;
    &lt;?php foreach (session()->getFlashdata('errors') as $error): ?&gt;
        &lt;p&gt;
            &lt;?= esc($error) ?&gt;
        &lt;/p&gt;
    &lt;?php endforeach; ?&gt;
&lt;?php endif; ?&gt;


&lt;form action="/products/store" method="post"&gt;
    &lt;div&gt;
        &lt;label&gt;Product Name&lt;/label&gt;
        &lt;input
            type="text"
            name="name"
            value="&lt;?= esc(old('name')) ?&gt;"
        &gt;
    &lt;/div&gt;
    &lt;br&gt;
    &lt;div&gt;
        &lt;label&gt;Price&lt;/label&gt;
        &lt;input
            type="number"
            name="price"
            value="&lt;?= esc(old('price')) ?&gt;"
        &gt;
    &lt;/div&gt;
    &lt;br&gt;
    &lt;button type="submit"&gt;
        Save Product
    &lt;/button&gt;
&lt;/form&gt;

&lt;?= $this->endSection() ?&gt;
</pre></code>

## 2. Complete store()

Ab final method:

<code><pre>
public function store()
{
    $rules = [
        'name'  => 'required|min_length[3]|max_length[100]',
        'price' => 'required|decimal'
    ];

    if (!$this->validate($rules)) {

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $data = [
        'name'  => $this->request->getPost('name'),
        'price' => $this->request->getPost('price')
    ];

    $this->productModel->insert($data);

    return redirect()
        ->to('/products')
        ->with('success', 'Product added successfully');
}
</pre></code>

Flow:

<code><pre>
Submit Form
    ↓
store()
    ↓
validate()
    ↓
 ┌───────────────┐
 │               │
Fail            Pass
 ↓               ↓
Errors         $data
 ↓               ↓
withInput()    Model
 ↓               ↓
Form           insert()
                 ↓
              Database
                 ↓
              Redirect
</pre></code>

## 3. Custom Error Messages ⭐

<code><pre>
$rules = [

    'name' => [
        'rules' => 'required|min_length[3]',
        'errors' => [
            'required' => 'Product name is required.',
            'min_length' => 'Product name must be at least 3 characters.'
        ]
    ],

    'price' => [
        'rules' => 'required|decimal',
        'errors' => [
            'required' => 'Product price is required.',
            'decimal' => 'Please enter a valid price.'
        ]
    ]

];
</pre></code>

## 4. Field ke Neeche Error Dikhana

<code><pre>
&lt;input
    type="text"
    name="name"
    value="&lt;?= esc(old('name')) ?&gt;"
&gt;

&lt;?php if (isset($errors['name'])): ?&gt;
    &lt;small&gt;
        &lt;?= esc($errors['name']) ?&gt;
    &lt;/small&gt;
&lt;?php endif; ?&gt;

&lt;input
    type="number"
    name="price"
    value="&lt;?= esc(old('price')) ?&gt;"
&gt;

&lt;?php if (isset($errors['price'])): ?&gt;
    &lt;small&gt;
        &lt;?= esc($errors['price']) ?&gt;
    &lt;/small&gt;
&lt;?php endif; ?&gt;
</pre></code>

## Update Form me Validation ⭐

- Update me bhi validation zaroori hai.

<code><pre>
public function update($id)
{
    $rules = [
        'name'  => 'required|min_length[3]|max_length[100]',
        'price' => 'required|decimal'
    ];

    if (!$this->validate($rules)) {

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $data = [
        'name'  => $this->request->getPost('name'),
        'price' => $this->request->getPost('price')
    ];

    $this->productModel->update($id, $data);

    return redirect()
        ->to('/products')
        ->with('success', 'Product updated successfully');
}
</pre></code>

## Edit Form me Old Value Problem ⭐

<code><pre>
&lt;form action="/products/store" method="post"&gt;

&lt;label>Name&lt;/label&gt;

&lt;input
type="text"
name="name"
value="&lt;?= old('name') ?&gt;"
&gt;

&lt;p&gt; &lt;?= validation_show_error('name') ?&gt; &lt;/p&gt;

&lt;br&gt;

&lt;label&gt;Price&lt;/label&gt;

&lt;input
type="number"
name="price"
value="&lt;?= old('price') ?&gt;"
&gt;

&lt;p&gt; &lt;?= validation_show_error('price') ?&gt; &lt;/p&gt;

&lt;br&gt;

&lt;button&gt; Save &lt;/button&gt;

&lt;/form&gt;
</pre></code>


## Interview Questions

**Validation kya hai?**

- Database me save karne se pehle input check karna.

**Validation Run**

- `$this->validate($rules)`

**Old Input**

- `old('name')`

**Name Error**

- `validation_show_error('name')`

**All Errors**

- `validation_list_errors()`

**Required Rule**

- `required`

**Numeric Rule**

- `numeric`

**Email Rule**

- `valid_email`