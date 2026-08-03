# Chapter 11 — Sessions & Flash Messages 

## 1. Session kya hai?

- HTTP normally stateless hota hai. Server ko automatically yaad nahi rehta ki previous request kis user ki thi.

<code><pre>
User Login
    ↓
Session me user_id save
    ↓
Dashboard
    ↓
Profile
    ↓
Orders

Session batata hai:
"Ye user logged-in hai"
</pre></code>

## 2. Session Data Set Karna ⭐

Single value:

`session()->set('name', 'jonh');`

Multiple values:

<code><pre>
session()->set([
    'user_id'   => 10,
    'name'      => 'jonh',
    'email'     => 'jonh@gmail.com',
    'logged_in' => true
]);
</pre></code>

## 3. Session Data Get Karna ⭐

`$name = session()->get('name');`

**or:**

`$userId = session()->get('user_id');`

## 4. Check Session Value

Login check karna ho:

<code><pre>
if (session()->get('logged_in')) {
    return "User Logged In";
}

return "Please Login";
</pre></code>

## 5. has() — Session Exists?

<code><pre>
if (session()->has('user_id')) {
    return "User session exists";
}
</pre></code>

Simple difference:

`session()->get('user_id');`

→ value leta hai.

`session()->has('user_id');`

→ check karta hai key exist karti hai ya nahi.

## 6. Session Remove Karna ⭐

Single session value remove:

`session()->remove('name');`

Multiple:

<code><pre>
session()->remove([
    'user_id',
    'name',
    'email'
]);
</pre></code>

- Login/logout me useful.

## 7. Session Destroy

- Entire session destroy karni ho:

`session()->destroy();`

**Example logout:**

<code><pre>
public function logout()
{
    session()->destroy();

    return redirect()->to('/login');
}
</pre></code>

**Flow:**

<code><pre>
Logout
  ↓
Session Destroy
  ↓
user_id removed
logged_in removed
  ↓
Login Page
</pre></code>

## 8. Normal Session vs Flashdata ⭐

**Normal Session**

`session()->set('name', 'jonh');`

- Multiple requests tak available reh sakta hai, jab tak remove/destroy/expire na ho.

**Flashdata**

<code><pre>
session()->setFlashdata(
    'success',
    'Product added successfully'
);
</pre></code>

- Flashdata temporary hota hai aur normally **next request** tak available rehta hai.
- CRUD success messages ke liye perfect.

## 9. Flash Message Example ⭐

<code><pre>
return redirect()
    ->to('/products')
    ->with('success', 'Product added successfully');
</pre></code>

- Ye basically redirect ke saath flashdata set karne ka convenient way hai.

**Product View:**

<code><pre>
&lt;?php if (session()->getFlashdata('success')): ?&gt;
    &lt;p&gt;
        &lt;?= esc(session()->getFlashdata('success')) ?&gt;
    &lt;/p&gt;
&lt;?php endif; ?&gt;
</pre></code>

Output: `Product added successfully`

- Page ko next request/refresh par message disappear ho jayega.

## 10. setFlashdata() Method

- Directly bhi set kar sakte hain:

<code><pre>
session()->setFlashdata(
    'success',
    'Product added successfully'
);

return redirect()->to('/products');
</pre></code>

- Aur shorter redirect syntax:

<code><pre>
return redirect()
    ->to('/products')
    ->with('success', 'Product added successfully');
</pre></code>

- Dono ka use flash message ke liye ho sakta hai.
- `CRUD` me shorter version convenient hai.

## 11. Success + Error Messages

Product create:

<code><pre>
return redirect()
    ->to('/products')
    ->with('success', 'Product added successfully');
</pre></code>

Delete error:

<code><pre>
return redirect()
    ->to('/products')
    ->with('error', 'Unable to delete product');
</pre></code>

View:

<code><pre>
&lt;?php if ($message = session()->getFlashdata('success')): ?&gt;
    &lt;div class="alert alert-success"&gt;
        &lt;?= esc($message) ?&gt;
    &lt;/div&gt;
&lt;?php endif; ?&gt;


&lt;?php if ($message = session()->getFlashdata('error')): ?&gt;
    &lt;div class="alert alert-danger"&gt;
        &lt;?= esc($message) ?&gt;
    &lt;/div&gt;
&lt;?php endif; ?&gt;
</pre></code>

## 12. Bootstrap Alert Example
- Agar Bootstrap use kar rahe ho:

<code><pre>
&lt;?php if ($message = session()->getFlashdata('success')): ?&gt;
    &lt;div class="alert alert-success"&gt;
        &lt;?= esc($message) ?&gt;
    &lt;/div&gt;
&lt;?php endif; ?&gt;
</pre></code>

## 13. Reusable Flash Partial ⭐
- Har page me success/error code repeat nahi karna.

Create:

`app/Views/partials/flash.php`

Code:

<code><pre>
&lt;?php if ($message = session()->getFlashdata('success')): ?&gt;
    &lt;div class="alert alert-success"&gt;
        &lt;?= esc($message) ?&gt;
    &lt;/div&gt;
&lt;?php endif; ?&gt;


&lt;?php if ($message = session()->getFlashdata('error')): ?&gt;
    &lt;div class="alert alert-danger"&gt;
        &lt;?= esc($message) ?&gt;
    &lt;/div&gt;
&lt;?php endif; ?&gt;
</pre></code>

Master Layout:

<code><pre>
&lt;?= view('partials/flash') ?&gt;
&lt;?= $this->renderSection('content') ?&gt;
</pre></code>

Ab flash message:

<code><pre>
Products
Users
Categories
Orders
</pre></code>

- sab pages par automatically display ho sakta hai.
- Ye **Partial ka real practical** use bhi hai.

## 14. `withInput()` aur Session
<code><pre>
return redirect()
    ->back()
    ->withInput();
</pre></code>

- withInput() submitted input ko temporarily preserve karta hai, jisse:

`old('name')`

- use kar sakte hain.

**Example:**

<code><pre>
&lt;input
    type="text"
    name="name"
    value="&lt;?= esc(old('name')) ?&gt;"
&gt;
</pre></code>

Flow:

<code><pre>
Form Submit
    ↓
Validation Fail
    ↓
redirect()->back()->withInput()
    ↓
Old Input temporarily available
    ↓
old('name')
</pre></code>

- So yahan bhi session-based temporary data ka concept involved hai.

## 15. Practical Login Session Example
Suppose login successful:

<code><pre>
public function login()
{
    session()->set([
        'user_id'   => 5,
        'name'      => 'Rahul',
        'logged_in' => true
    ]);

    return redirect()->to('/dashboard');
}
</pre></code>

Dashboard:

<code><pre>
public function dashboard()
{
    if (! session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    return view('dashboard');
}
</pre></code>

View:

<code><pre>
&lt;h1&gt;
    Welcome &lt;?= esc(session()->get('name')) ?&gt;
&lt;/h1&gt;
</pre></code>

Logout:
<code><pre>
public function logout()
{
    session()->destroy();

    return redirect()->to('/login');
}
</pre></code>

Complete:
<code><pre>
LOGIN
  ↓
Credentials correct
  ↓
session()->set()
  ↓
Dashboard
  ↓
Session identifies logged-in user


LOGOUT
  ↓
session()->destroy()
  ↓
Login Page
</pre></code>


## 🧠 Interview Questions

**Q1. Session kya hai?**

- Multiple requests ke across user/application data maintain karne ka mechanism.

**Q2. Session set?**

`session()->set('name', 'Rahul');`

**Q3. Session get?**

`session()->get('name');`

**Q4. Session key exists?**

`session()->has('user_id');`

**Q5. Remove?**

``session()->remove('name');``

**Q6. Destroy?**
`session()->destroy();`

**Q7. Flashdata kya hai?**

- Temporary session data jo short-lived messages/data, commonly next request, ke liye use hota hai.

**Q8. Flash message with redirect?**

<code><pre>
return redirect()
    ->to('/products')
    ->with('success', 'Product added');
</pre></code>