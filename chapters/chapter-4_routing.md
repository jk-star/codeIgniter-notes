# Chapter 4 — Routing

- URL ko correct Controller aur Method se connect karna.

<code><pre>
URL
 ↓
Route
 ↓
Controller
 ↓
Method
</pre></code>

**Example:**

<code><pre>
/student
    ↓
Student::index
</pre></code>

## 1. Routes kahan likhte hain?

`app/Config/Routes.php`

**Example:**

`$routes->get('/student', 'Student::index');`

**Meaning:**

<code><pre>
/student          → URL
Student           → Controller
index             → Method
</pre></code>

**Controller:**

<code><pre>
namespace App\Controllers;

class Student extends BaseController
{
    public function index()
    {
        return "Student Page";
    }
}
</pre></code>

**Browser:**

`http://localhost:8080/student`

**Output:**

`Student Page`

## 2. GET Route ⭐
- GET ka use normally data/page retrieve ya display karne ke liye hota hai.

`$routes->get('/products', 'Product::index');`

**Example URLs:**

<code><pre>
/products
/users
/profile
/about
</pre></code>

**Controller:**

<code><pre>
public function index()
{
    return "Product List";
}
</pre></code>

**Simple rule**

`GET → Data/Page lena ya dikhana`

## 3. POST Route ⭐

- POST ka use normally form data server par submit karne ke liye hota hai.

`$routes->post('/student/save', 'Student::save');`

**Controller:**

<code><pre>
public function save()
{
    return "Student Saved";
}
</pre></code>

**Flow:**

<code><pre>
Form
 ↓
POST /student/save
 ↓
Route
 ↓
Student::save()
 ↓
Data Save
</pre></code>

**Example form:**

<code><pre>
&lt;form action="/student/save" method="post"&gt;
    &lt;input type="text" name="name"&gt;
    &lt;button type="submit"&gt;
        Save
    &lt;/button&gt;
&lt;/form&gt;
</pre></code>

## 4. GET vs POST ⭐⭐⭐

| GET                                  | POST                       |
| ------------------------------------ | -------------------------- |
| Data/page retrieve                   | Data submit                |
| URL parameters visible ho sakte hain | Form body me data          |
| List/page ke liye common             | Create/save ke liye common |
| `$routes->get()`                     | `$routes->post()`          |


**Example CRUD me:**

<code><pre>
GET  /students
     ↓
Students dikhana

POST /students
     ↓
New student save karna
</pre></code>

## 5. Dynamic Route Parameter ⭐⭐⭐

- Suppose hume student ID URL se chahiye:

<code><pre>
/student/10
/student/20
/student/50
</pre></code>

- Har ID ke liye alag route nahi banayenge.

`$routes->get('/student/(:num)', 'Student::show/$1');`

**Yahan:**

`(:num)`

- **ka matlab:** Numeric value accept karo.

**Aur:**

`$1`

- **ka matlab:** URL se mili first value Controller method ko pass karo.

**Controller:**

<code><pre>
public function show($id)
{
    return "Student ID: " . $id;
}
</pre></code>

**Browser:**

`/student/10`

**Output:**

`Student ID: 10`

**Flow:**

<code><pre>
/student/10
     ↓
(:num) = 10
     ↓
$1 = 10
     ↓
Student::show(10)
</pre></code>

## 6. (:num) vs (:segment)

- Common placeholders: `(:num)` Only numbers:

`$routes->get('/user/(:num)', 'User::show/$1');`

**Works:**

`/user/10      ✅`

`/user/500     ✅`

## (:segment)

- Single URL segment accept karta hai.

`$routes->get('/profile/(:segment)', 'Profile::show/$1');`

**URLs:**

<code><pre>
/profile/jyoti
/profile/kokil
/profile/user-10
</pre></code>

**Controller:**

<code><pre>
public function show($username)
{
    return "Profile: " . $username;
}
</pre></code>

## 7. Multiple Parameters

**Suppose URL:**

`/product/10/review/5`

**Route:**

<code><pre>
$routes->get(
    '/product/(:num)/review/(:num)',
    'Product::review/$1/$2'
);
</pre></code>

**Controller:**

<code><pre>
public function review($productId, $reviewId)
{
    return $productId . ' - ' . $reviewId;
}
</pre></code>

**Here:**

<code><pre>
$productId = 10
$reviewId  = 5
</pre></code>

**Remember:**

<code><pre>
$1 → First parameter
$2 → Second parameter
$3 → Third parameter
</pre></code>

## 8. Route Group ⭐⭐

- Real projects me routes bahut zyada ho jate hain.

**Suppose:**

<code><pre>
/admin/users
/admin/products
/admin/orders
</pre></code>

- Har jagah `/admin` repeat karne ke bajay group bana sakte hain:

<code><pre>
$routes->group('admin', function ($routes) {

    $routes->get('users', 'Admin\User::index');

    $routes->get('products', 'Admin\Product::index');

    $routes->get('orders', 'Admin\Order::index');

});
</pre></code>

**Result:**

<code><pre>
/admin/users
/admin/products
/admin/orders
</pre></code>

- Route groups especially Admin Panel aur APIs me useful hain.

## 9. Named Routes
- Kisi route ko name bhi de sakte hain.
<code><pre>
$routes->get(
    '/students',
    'Student::index',
    ['as' => 'students']
);
</pre></code>

**Ab iska route name hai:**
`students`

**URL generate kar sakte hain:**

`route_to('students');`

Named routes ka benefit:

Agar future me:

`/students`

ko:

`/all-students`

kar diya, to hard-coded URLs ko har jagah change karne ki zarurat kam padti hai.

## 10. CRUD me Routes kaise dikhenge? ⭐⭐⭐

Student CRUD:

<code><pre>
// List
$routes->get('/students', 'Student::index');

// Add Form
$routes->get('/students/create', 'Student::create');

// Save
$routes->post('/students', 'Student::store');

// Edit Form
$routes->get('/students/edit/(:num)', 'Student::edit/$1');

// Update
$routes->post('/students/update/(:num)', 'Student::update/$1');

// Delete
$routes->post('/students/delete/(:num)', 'Student::delete/$1');
</pre></code>

**Flow:**

<code><pre>
/students
    ↓
index()
    ↓
Student List


/students/create
    ↓
create()
    ↓
Add Form


POST /students
    ↓
store()
    ↓
Save Student


/students/edit/5
    ↓
edit(5)
    ↓
Edit Student #5
</pre></code>

## 11. match() Route

- Kabhi same URL par multiple HTTP methods allow karne ho:

<code><pre>
$routes->match(
    ['get', 'post'],
    '/login',
    'Auth::login'
);
</pre></code>

**Iska matlab:**

`GET  /login  ✅`

`POST /login  ✅`

- Dono Auth::login() tak ja sakte hain.
- Lekin jahan possible ho, explicit GET/POST routes rakhna code ko clearer banata hai.

## 12. add() ke bajay GET/POST

`$routes->add('/student', 'Student::index');`

- Lekin normally specific HTTP method likhna better hai:

`$routes->get('/student', 'Student::index');`

ya:

`$routes->post('/student', 'Student::save');`

## 🧠 Interview Questions

**Q1. Routing kya hai?**

- URL/request ko Controller ke appropriate method se map karna.

**Q2. Routes file kahan hai?**

`app/Config/Routes.php`

**Q3. GET route ka syntax?**

`$routes->get('/users', 'User::index');`

**Q4. POST route?**

`$routes->post('/users', 'User::store');`

**Q5. Numeric dynamic parameter?**

`$routes->get('/user/(:num)', 'User::show/$1');`

**Q6. `$1` kya hai?**

- Route me match hui first captured value.

<code><pre>
/user/15
       ↓
      $1
</pre></code>

**Q7. Route Group kyun?**

- Same URL prefix wale routes ko organize karne ke liye.
