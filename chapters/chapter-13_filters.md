# Chapter 13 — Filters (Auth Middleware)

- Chapter 12 me humne Authentication banaya tha. Dashboard protect karne ke liye hum ye check kar rahe the:

<code><pre>
if (! session()->get('logged_in')) {
    return redirect()->to('/login');
}
</pre></code>

- Problem ye hai ki agar 20 protected pages hain, to ye code har Controller me repeat karna padega. ❌

- **Filter = Request ko Controller tak pahunchne se pehle ya response ke baad process/check karna.**

## 1. Auth Filter ka Real Use ⭐

**Suppose:**

<code><pre>
/dashboard
/products
/users
/orders
/profile
</pre></code>

- Sirf logged-in users access kar sakte hain.

**Without Filter:**

<code><pre>
Dashboard Controller → Login Check
Product Controller   → Login Check
User Controller      → Login Check
Order Controller     → Login Check
Profile Controller   → Login Check
</pre></code>

- ❌ Repeated code.

**With Filter:**

<code><pre>
            Request
                ↓
           Auth Filter
                ↓
         User Logged In?
          ↙          ↘
        YES           NO
         ↓             ↓
    Controller       /login
</pre></code>

- Ek jagah authentication logic. ✅

## 2. Filter Kahan Hota Hai?

- **Location:** `app/Filters/`

- **Hum banayenge:** `app/Filters/AuthFilter.php`

- **CLI se:** `php spark make:filter AuthFilter`

## 3. Basic Filter Structure

`app/Filters/AuthFilter.php`

<code><pre>
&lt;?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        //
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        //
    }
}
</pre></code>

- Isme do important methods hain:
`before()`
`after()`

## 4. before() Kya Hai? ⭐
- Controller execute hone se pehle chalega.

<code><pre>
Request
   ↓
before()
   ↓
Controller
</pre></code>

- Authentication ke liye mainly `before()` use karenge.

**Example:**

<code><pre>
public function before(
    RequestInterface $request,
    $arguments = null
) {
    if (! session()->get('logged_in')) {
        return redirect()->to('/login');
    }
}

</pre></code>

Ab:

<code><pre>
/dashboard
     ↓
AuthFilter::before()
     ↓
logged_in?
</pre></code>

**Agar:**
`session()->get('logged_in')`

**false hai:**

``/login`` par redirect.

## 5. Complete AuthFilter ⭐

<code><pre>
&lt;?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        if (! session()->get('logged_in')) {
            return redirect()
                ->to('/login')
                ->with('error', 'Please login first.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        //
    }
}
</pre></code>

- Auth Filter ready. ✅
- Lekin abhi CI4 ko nahi pata ki:
- `auth`
- naam ka filter AuthFilter class hai.
- Uske liye Alias banana padega.

## 6. Filter Alias Register Karna ⭐
Open:

`app/Config/Filters.php`

Aapko `$aliases` milega.

Usme add:

`'auth' => \App\Filters\AuthFilter::class,`

Example:
<code><pre>
public array $aliases = [
    // existing aliases...
    'auth' => \App\Filters\AuthFilter::class,
];
</pre></code>

Ab:

<code><pre>
auth
 ↓
AuthFilter
</pre></code>

- `auth` ek short name/alias hai.
- Iske baad hume routes me poora:

`\App\Filters\AuthFilter::class`

- likhne ki zarurat nahi.

Sirf:

- `auth` use kar sakte hain.

## 7. Route Par Filter Lagana ⭐
Dashboard route:
<code><pre>
$routes->get(
    '/dashboard',
    'Auth::dashboard',
    ['filter' => 'auth']
);

</pre></code>

Ab flow:

<code><pre>
/dashboard
     ↓
auth filter
     ↓
AuthFilter
     ↓
logged_in?
  ↙       ↘
YES       NO
 ↓         ↓
dashboard  /login
</pre></code>

## 8. Ab Controller Clean Ho Gaya

<code><pre>
public function dashboard()
{
    if (! session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    return view('dashboard');
}
</pre></code>

Ab Filter lagne ke baad:

<code><pre>
public function dashboard()
{
    return view('dashboard');
}
</pre></code>

- Authentication check Controller se remove. ✅
- Ye Filters ka main benefit hai.

## 9. Multiple Routes Protect Karna

individually:

<code><pre>
$routes->get(
    '/dashboard',
    'Dashboard::index',
    ['filter' => 'auth']
);

$routes->get(
    '/products',
    'Product::index',
    ['filter' => 'auth']
);

$routes->get(
    '/users',
    'User::index',
    ['filter' => 'auth']
);
</pre></code>

- Better → Route Group + Filter

## 10. Route Group + Filter ⭐
<code><pre>
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('products', 'Product::index');

    $routes->get('products/create', 'Product::create');

    $routes->post('products/store', 'Product::store');

    $routes->get('users', 'User::index');

});
</pre></code>

- Ab group ke andar sab routes protected hain.
<code><pre>
      Auth Filter
                   │
       ┌───────────┼───────────┐
       ↓           ↓           ↓
  /dashboard   /products    /users
</pre></code>

## 11. Public vs Protected Routes
- Authentication project me routes roughly aise organize kar sakte hain:
<code><pre>
// PUBLIC ROUTES

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::store');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');


// PROTECTED ROUTES

$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('/dashboard', 'Dashboard::index');

    $routes->get('/products', 'Product::index');

    $routes->get('/products/create', 'Product::create');

    $routes->post('/products/store', 'Product::store');

    $routes->get(
        '/products/edit/(:num)',
        'Product::edit/$1'
    );

    $routes->post(
        '/products/update/(:num)',
        'Product::update/$1'
    );

    $routes->post(
        '/products/delete/(:num)',
        'Product::delete/$1'
    );

    $routes->post('/logout', 'Auth::logout');

});
</pre></code>

## 12. after() Filter Kya Hai?

- after() Controller execute hone ke baad run hota hai.

<code><pre>
Request
   ↓
before()
   ↓
Controller
   ↓
after()
   ↓
Response
</pre></code>
Example use cases:

<code><pre>
Response headers
Logging
Response modification
</pre></code>

Authentication check ke liye normally:

`before()` important hai.

## 13. Guest Filter — Useful Concept ⭐

- Ek opposite problem bhi hai.

- User already logged-in hai aur open karta hai:

`/login`

- Kya login page dubara dikhana chahiye?

- Normally nahi.

- Hum GuestFilter bana sakte hain.

`php spark make:filter GuestFilter`

`app/Filters/GuestFilter.php`

<code><pre>
&lt;?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        //
    }
}
</pre></code>

## 14. Guest Alias
`app/Config/Filters.php`

<code><pre>
'auth'  => \App\Filters\AuthFilter::class,
'guest' => \App\Filters\GuestFilter::class,
</pre></code>

Ab:

<code><pre>
auth
→ Sirf logged-in users

guest
→ Sirf logged-out users
</pre></code>

## 15. Guest Routes

<code><pre>
$routes->group('', ['filter' => 'guest'], function ($routes) {

    $routes->get('/register', 'Auth::register');

    $routes->post('/register', 'Auth::store');

    $routes->get('/login', 'Auth::login');

    $routes->post('/login', 'Auth::authenticate');

});
</pre></code>

Protected:

<code><pre>
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('/dashboard', 'Dashboard::index');

    $routes->get('/products', 'Product::index');

    $routes->post('/logout', 'Auth::logout');

});
</pre></code>

Ab flow:

<code><pre>
Logged OUT User
     ↓
/login
     ↓
guest filter
     ↓
Login Page ✅
</pre></code>

But:
<code><pre>
Logged IN User
     ↓
/login
     ↓
guest filter
     ↓
/dashboard
</pre></code>

## 16. Auth + Guest ko Ek Diagram me Samjho

<code><pre>
 USER
                      │
            ┌─────────┴─────────┐
            ↓                   ↓
         /login             /dashboard
            ↓                   ↓
      Guest Filter          Auth Filter
            ↓                   ↓
      Logged in?            Logged in?
        ↙    ↘                ↙    ↘
      NO     YES             YES    NO
      ↓       ↓               ↓      ↓
    Login  Dashboard      Dashboard Login
</pre></code>

## 🧠 Interview Questions

**Q1. Filter kya hai?**

- Request ko Controller se pehle ya response ke baad process/check karne ka mechanism.

**Q2. Filters kahan hote hain?**

`app/Filters/`

**Q3. Filter alias kahan register karte hain?**

`app/Config/Filters.php`

**Q4. Authentication ke liye kaunsa method important hai?**

`before()`

**Q5. Single route par Filter?**
<code><pre>
$routes->get(
    '/dashboard',
    'Dashboard::index',
    ['filter' => 'auth']
);

</pre></code>

**Q6. Multiple routes?**
<code><pre>
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // protected routes

});

</pre></code>

**Q7. `before()` vs `after()` ?**
<code><pre>
before()
→ Controller se pehle

after()
→ Controller ke baad

</pre></code>