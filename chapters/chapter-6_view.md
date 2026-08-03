# Chapter 6 — Views & Layouts 

## 1. View kya hota hai?

**View = UI / HTML**

Views ki location:

`app/Views/`

Example:

`app/Views/products/index.php`

<code><pre>
&lt;h1&gt;Product List&lt;/h1&gt;

&lt;p&gt;Welcome to Products Page&lt;/p&gt;
</pre></code>

Controller se load:

<code><pre>
public function index()
{
    return view('products/index');
}
</pre></code>

Notice: `view('products/index')`

Means: `app/Views/products/index.php`

`.php` manually nahi likhna.

## 2. Views ko folders me organize karna ⭐

❌ Aise bahut saari files rakhna:

<code><pre>
Views/
├── product-list.php
├── product-add.php
├── product-edit.php
├── user-list.php
├── user-add.php
└── user-edit.php
</pre></code>

Better:

<code><pre>
Views/
│
├── products/
│   ├── index.php
│   ├── create.php
│   └── edit.php
│
└── users/
    ├── index.php
    ├── create.php
    └── edit.php
</pre></code>

- Controller: `return view('products/index');`

- Add page: `return view('products/create');`
- Edit page: `return view('products/edit');`

- Ye structure real projects me zyada clean rahega.

## 3. Controller se View me Data ⭐

Controller:

<code><pre>
public function index()
{
    $data = [
        'title' => 'Product List',
        'name'  => 'Laptop',
        'price' => 50000
    ];

    return view('products/index', $data);
}
</pre></code>

View:

<code><pre>
&lt;h1&gt;<?= esc($title) ?>&lt;/h1&gt;

&lt;p&gt;Product: <?= esc($name) ?>&lt;/p&gt;

&lt;p&gt;Price: ₹<?= esc($price) ?>&lt;/p&gt;
</pre></code>

## 4. Multiple Records Display Karna

Controller:

<code><pre>
public function index()
{
    $data['products'] = [
        [
            'name' => 'Laptop',
            'price' => 50000
        ],
        [
            'name' => 'Mobile',
            'price' => 20000
        ],
        [
            'name' => 'Keyboard',
            'price' => 1500
        ]
    ];

    return view('products/index', $data);
}
</pre></code>

View:

<h1>Products</h1>

<code><pre>
&lt;?php foreach ($products as $product): ?&gt;
    &lt;p&gt;
        &lt;?= esc($product['name']) ?&gt;
        -
        ₹&lt;?= esc($product['price']) ?&gt;
    &lt;/p&gt;
&lt;?php endforeach; ?&gt;
</pre></code>

## 5. Layout ki Problem Samjho ⭐
- Suppose hamare paas 3 pages hain:
<code><pre>
Home
Products
Users
</pre></code>

- Har page me same:

<code><pre>
&lt;html&gt;

&lt;head&gt;
    &lt;title&gt;My Website&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;

&lt;header&gt;
    Navbar
&lt;/header&gt;

PAGE CONTENT

&lt;footer&gt;
    Copyright
&lt;/footer&gt;

&lt;/body&gt;

&lt;/html&gt;
</pre></code>

- Agar 20 pages hain to header/footer 20 baar repeat karna padega. ❌
- Is problem ko Layout solve karta hai.

## 6. Master Layout Banana ⭐

Folder create karein: `app/Views/layouts/`

File: `app/Views/layouts/main.php`

Code:

<code><pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;

&lt;head&gt;
    &lt;title&gt;&lt;?= esc($title ?? 'My Website') ?&gt;&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;header&gt;
        &lt;h2&gt;My Website&lt;/h2&gt;
        &lt;nav&gt;
            &lt;a href="/"&gt;Home&lt;/a&gt;
            &lt;a href="/products"&gt;Products&lt;/a&gt;
            &lt;a href="/users"&gt;Users&lt;/a&gt;
        &lt;/nav&gt;
    &lt;/header&gt;
    &lt;hr&gt;
    &lt;?= $this->renderSection('content') ?&gt;
    &lt;hr&gt;
    &lt;footer&gt;
        &lt;p&gt;Copyright 2026&lt;/p&gt;
    &lt;/footer&gt;
&lt;/body&gt;

&lt;/html&gt;
</pre></code>

Sabse important line:

`<?= $this->renderSection('content') ?>`

Is jagah individual page ka content aayega.

## 7. extend() ⭐

Ab Product View: `app/Views/products/index.php`

<code><pre>
&lt;?= $this->extend('layouts/main') ?&gt;

&lt;?= $this->section('content') ?&gt;

&lt;h1&gt;Product List&lt;/h1&gt;

&lt;p&gt;Welcome to product page.&lt;/p&gt;

&lt;?= $this->endSection() ?&gt;
</pre></code>

**`extend()` ka meaning**

`<?= $this->extend('layouts/main') ?>`

means: Ye page `layouts/main.php` ko parent/master layout ke roop me use karega.

## 8. section() kya hai?

`<?= $this->section('content') ?>`

means: Yahan se `content` section start hai.

Content:
<code><pre>
&lt;h1&gt;Product List&lt;/h1&gt;

&lt;p&gt;Welcome to product page.&lt;/p&gt;
</pre></code>

End: `<?= $this->endSection() ?>`

## 9. renderSection() kya hai? ⭐

Master layout: `<?= $this->renderSection('content') ?>`

Child View:

<code><pre>
&lt;?= $this->section('content') ?&gt;

&lt;h1&gt;Products&lt;/h1&gt;

&lt;?= $this->endSection() ?&gt;
</pre></code>

Result:

<code><pre>
Master Layout
     ↓

Header

Products        ← Child View Content

Footer
</pre></code>

Simple connection:

<code><pre>
Child View

section('content')
       ↓
       ↓
Master Layout

renderSection('content')
</pre></code>

**⭐ Easy Rule**
<code><pre>
extend()
    → Kaunsa layout use karna hai

section()
    → Content define karna

renderSection()
    → Content dikhana

endSection()
    → Section close karna
</pre></code>

## 10. Complete Example ⭐

**Master Layout**

`app/Views/layouts/main.php`

<code><pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;

&lt;head&gt;
    &lt;title&gt;<?= esc($title ?? 'CI4 App') ?>&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;

&lt;header&gt;
    &lt;h2&gt;CI4 Store&lt;/h2&gt;
    &lt;nav&gt;
        &lt;a href="/">Home&lt;/a&gt;
        &lt;a href="/products">Products&lt;/a&gt;
    &lt;/nav&gt;
&lt;/header&gt;

&lt;hr&gt;

&lt;?= $this->renderSection('content') ?&gt;

&lt;hr&gt;

&lt;footer&gt;
    &lt;p&gt;CI4 Store&lt;/p&gt;
&lt;/footer&gt;

&lt;/body&gt;

&lt;/html&gt;
</pre></code>

**Product View**

`app/Views/products/index.php`

<code><pre>
&lt;?= $this->extend('layouts/main') ?&gt;

&lt;?= $this->section('content') ?&gt;

&lt;h1&gt;&lt;?= esc($title) ?&gt;&lt;/h1&gt;

&lt;p&gt;Our Products&lt;/p&gt;

&lt;?= $this->endSection() ?&gt;
</pre></code>

**Controller**

<code><pre>
public function index()
{
    $data = [
        'title' => 'Product List'
    ];

    return view('products/index', $data);
}
</pre></code>

## 11. Multiple Sections ⭐

- Sirf `content` hi nahi, multiple sections bana sakte hain.

**Master:**

<code><pre>
&lt;head&gt;
    &lt;?= $this->renderSection('styles') ?&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;?= $this->renderSection('content') ?&gt; <br/>
    &lt;?= $this->renderSection('scripts') ?&gt;
&lt;/body&gt;
</pre></code>

Child View:

<code><pre>
&lt;?= $this->extend('layouts/main') ?&gt;

&lt;?= $this->section('styles') ?&gt;

&lt;style&gt;
    h1 {
        color: green;
    }
&lt;/style&gt;

&lt;?= $this->endSection() ?&gt;


&lt;?= $this->section('content') ?&gt;

&lt;h1>Products&lt;/h1&gt;

&lt;?= $this->endSection() ?&gt;


&lt;?= $this->section('scripts') ?&gt;

&lt;script&gt;
    console.log('Product page loaded');
&lt;/script&gt;

&lt;?= $this->endSection() ?&gt;

</pre></code>

Useful structure:

<code><pre>
Layout
├── styles
├── content
└── scripts
</pre></code>

## 12. Reusable Partial Views

Kabhi chhota reusable component chahiye.

Example:

`app/Views/partials/navbar.php`

navbar.php:

<code><pre>
&lt;nav&gt;
    &lt;a href="/">Home&lt;/a&gt;
    &lt;a href="/products">Products&lt;/a&gt;
    &lt;a href="/users">Users&lt;/a&gt;
&lt;/nav&gt;
</pre></code>

Layout me: `<?= view('partials/navbar') ?>`

- Ab navbar reusable ho gaya.

## 13. Layout vs Partial — Difference ⭐

| Layout                       | Partial                            |
| ---------------------------- | ---------------------------------- |
| Complete page structure      | Small reusable UI                  |
| Header/body/footer structure | Navbar, sidebar, alert etc.        |
| `extend()` use hota hai      | `view()` se include kar sakte hain |
| Sections contain karta hai   | Reusable component                 |


## Layout — Complete Example

**Situation**

Hamari website me 2 pages hain:

<code><pre>
/products
/users
</pre></code>

- Dono pages par same Header + Navbar + Footer chahiye, sirf beech ka content change hoga.

**Folder Structure**

<code><pre>
app/Views/
│
├── layouts/
│   └── main.php
│
├── products/
│   └── index.php
│
└── users/
    └── index.php
</pre></code>

**Step 1 — Master Layout**

`app/Views/layouts/main.php`

<code><pre>
&lt;!DOCTYPE html&gt;
&lt;html>
&lt;head>
    &lt;title&gt;&lt;?= esc($title ?? 'My Website') ?&gt;&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;header&gt;
        &lt;h2&gt;My CI4 Website&lt;/h2&gt;
        &lt;nav&gt;
            &lt;a href="/products">Products&lt;/a&gt;
            &lt;a href="/users">Users&lt;/a&gt;
        &lt;/nav&gt;
    &lt;/header&gt;
    &lt;hr&gt;
    &lt;!-- Child page ka content yahan aayega --&gt;
    &lt;?= $this->renderSection('content') ?&gt;
    &lt;hr&gt;
    &lt;footer&gt;
        &lt;p>Copyright 2026&lt;/p&gt;
    &lt;/footer&gt;
</body>
</html>
</pre></code>

- Yahan sabse important:
`<?= $this->renderSection('content') ?>`

- **Matlab:** Child page ka `content` section yahan display karo.

## Step 2 — Product View

`app/Views/products/index.php`

<code><pre>
&lt;$?= $this->extend('layouts/main') ?&gt;

&lt;?= $this->section('content') ?&gt;

&lt;h1&gt;Product List&lt;/h1&gt;

&lt;ul&gt;
    &lt;li&gt;Laptop - ₹50,000&lt;/li&gt;
    &lt;li&gt;Mobile - ₹20,000&lt;/li&gt;
    &lt;li&gt;Keyboard - ₹1,500&lt;/li&gt;
&lt;/ul&gt;

&lt;?= $this->endSection() ?&gt;
</pre></code>

- **Yahan:** `$this->extend('layouts/main')`
- **means:** `main.php` ko master layout banao.
- **Aur:**  `$this->section('content')`
- means: Ye content master layout ke `renderSection('content')` me jayega.

## Step 3 — Controller

&lt;?php
public function index()
{
    $data['title'] = 'Products';

    return view('products/index', $data);
}
?&gt;

## Layout ka flow

<code><pre>
products/index.php
       │
       │ extend()
       ↓
layouts/main.php
       │
       │
Header
Navbar
       │
renderSection('content')
       ↑
Product List
       │
Footer
</pre></code>

- **Layout = poore page ka common skeleton/template.**

## Partial — Complete Example

- Ab maan lo Navbar ko alag reusable file me rakhna hai.
- Navbar ko:

<code><pre>
Layout
Product page
User page
Admin page
</pre></code>

- kahin bhi include kar sakte hain.

**Folder Structure**

<code><pre>
app/Views/
│
├── partials/
│   └── navbar.php
│
└── products/
    └── index.php
</pre></code>

## Partial Banao

`app/Views/partials/navbar.php`

<code><pre>
&lt;nav&gt;
    &lt;a href="/">Home&lt;/a&gt;
    &lt;a href="/products">Products&lt;/a&gt;
    &lt;a href="/users">Users&lt;/a&gt;
    &lt;a href="/contact">Contact&lt;/a&gt;
&lt;/nav&gt;
</pre></code>

- Ye sirf chhota reusable UI component hai.
- Isme:

<code><pre>
&lt;html&gt;
&lt;body&gt;
&lt;head&gt;
</pre></code>

- ki zarurat nahi.

## Partial ko View me Include Karo

`app/Views/products/index.php`

<code><pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;

&lt;head&gt;
    &lt;title&gt;Products&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;h2&gt;My Website&lt;/h2&gt;
    &lt;?= view('partials/navbar') ?&gt;
    &lt;hr&gt;
    &lt;h1>Product List&lt;/h1&gt;
    &lt;ul&gt;
        &lt;li&gt;Laptop - ₹50,000&lt;/li&gt;
        &lt;li&gt;Mobile - ₹20,000&lt;/li&gt;
    &lt;/ul&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre></code>

**Important:**

&lt;?= view('partials/navbar') ?&gt;

**CI4 ko bol raha hai:**

- `partials/navbar.php` ka content yahan include karo.

## Real Project me Layout + Partial Dono Saath

<code><pre>
Views/
│
├── layouts/
│   └── main.php
│
├── partials/
│   ├── navbar.php
│   └── footer.php
│
└── products/
    └── index.php
</pre></code>

`layouts/main.php`

<code><pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;<?= esc($title ?? 'My App') ?>&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;?= view('partials/navbar') ?&gt;
    &lt;main&gt;
        &lt;?= $this->renderSection('content') ?&gt;
    &lt;/main&gt;
    &lt;?= view('partials/footer') ?&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre></code>

`partials/navbar.php`

<code><pre>
&lt;nav&gt;
    &lt;a href="/">Home&lt;/a&gt;
    &lt;a href="/products">Products&lt;/a&gt;
    &lt;a href="/users">Users&lt;/a&gt;
&lt;/nav&gt;
</code></pre>

`partials/footer.php`

<code><pre>
&lt;footer&gt;
    &lt;p&gt;Copyright 2026&lt;/p&gt;
&lt;/footer&gt;
</code></pre>

`products/index.php`

<code><pre>
&lt;?= $this->extend('layouts/main') ?&gt;

&lt;?= $this->section('content') ?&gt;

&lt;h1>Products&lt;/h1&gt;

&lt;p>Product list will appear here.&lt;/p&gt;

&lt;?= $this->endSection() ?&gt;
</pre></code>

Ab relationship:

<code><pre>
            main.php
               (LAYOUT)
                  │
        ┌─────────┼─────────┐
        ↓         ↓         ↓
    navbar    CONTENT     footer
   (Partial)      ↑       (Partial)
                  │
          products/index.php
</pre></code>

**Bas ek line me yaad rakho:**

- Layout = poore page ka common structure
- Partial = page ka chhota reusable piece

- For example, Layout = ghar ka structure, jabki Partial = door, window, navbar, sidebar jaise reusable parts.

## 🧠 Interview Questions

**Q1. View ka kaam kya hai?**
- Application ka UI/presentation display karna.

**Q2. CI4 Views kahan hoti hain?**

`app/Views/`

**Q3. View kaise load karte hain?**

`return view('products/index');`

**Q4. View me data kaise bhejte hain?**

`$data['title'] = 'Products';` <br/>
`return view('products/index', $data);`

**Q5. extend() kya karta hai?**

- Parent/master layout ko inherit/use karta hai.

`<?= $this->extend('layouts/main') ?>`

**Q6. `section()` ?**

- Child View me ek content section define karta hai.

**Q7. `renderSection()` ?**

- Master layout me defined section ka content render karta hai.
