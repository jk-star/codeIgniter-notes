# Chapter 12 — Authentication

## 1. Authentication kya hai?
- User ki identity verify karna.
- Authentication aur Authorization alag concepts hain:

<code><pre>
Authentication
→ Aap kaun ho?

Authorization
→ Aapko kya access karne ki permission hai?
</pre></code>

## 2. users Table

Practice ke liye phpMyAdmin me:

<code><pre>
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
</pre></code>

Important:

<code><pre>
id
name
email
password
created_at
updated_at
</pre></code>

Password ke liye:

`VARCHAR(255)`

- rakhna useful hai because hum plain password nahi, password hash store karenge.

## 3. User Model

Create:

`php spark make:model UserModel`

`app/Models/UserModel.php`

<code><pre>
&lt;?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'email',
        'password'
    ];

    protected $useTimestamps = true;
}
</pre></code>

## 4. Authentication Routes

`app/Config/Routes.php`

<code><pre>
// Register
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::store');

// Login
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');

// Dashboard
$routes->get('/dashboard', 'Auth::dashboard');

// Logout
$routes->post('/logout', 'Auth::logout');
</pre></code>

**Flow:**

<code><pre>
GET  /register
     → Register Form

POST /register
     → User Save

GET  /login
     → Login Form

POST /login
     → Credentials Check

GET  /dashboard
     → Dashboard

POST /logout
     → Logout
</pre></code>

## 5. Auth Controller

Create:

`php spark make:controller Auth`

Start:

<code><pre>
&lt;?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
}
</pre></code>

## 6. Register Form

Controller:

<code><pre>
public function register()
{
    return view('auth/register');
}
</pre></code>

Create:

`app/Views/auth/register.php`

<code><pre>
&lt;h1>Register</h1>

&lt;form action="/register" method="post"&gt;
    &lt;?= csrf_field() ?&gt;
    &lt;label&gt;Name&lt;/label&gt;
    &lt;input
        type="text"
        name="name"
        value="&lt;?= esc(old('name')) ?&gt;"
    &gt;
    &lt;br&gt;&lt;br&gt;
    &lt;label&gt;Email&lt;/label&gt;
    &lt;input
        type="email"
        name="email"
        value="&lt;?= esc(old('email')) ?>"
    &gt;
    &lt;br&gt;&lt;br&gt;
    &lt;label&gt;Password&lt;/label&gt;
    &lt;input
        type="password"
        name="password"
    &gt;
    &lt;br&gt;&lt;br&gt;
    &lt;label&gt;Confirm Password&lt;/label&gt;
    &lt;input
        type="password"
        name="password_confirm"
    &gt;
    &lt;br&gt;&lt;br&gt;
    &lt;button type="submit"&gt;
        Register
    &lt;/button&gt;
&lt;/form&gt;
</pre></code>

## 7. Registration Validation ⭐

Controller:

<code><pre>
public function store()
{
    $rules = [
        'name' => 'required|min_length[3]',

        'email' =>
            'required|valid_email|is_unique[users.email]',

        'password' =>
            'required|min_length[6]',

        'password_confirm' =>
            'required|matches[password]'
    ];

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    // User save karenge
}
</pre></code>

Important new rules:

<code><pre>
valid_email
→ Valid email format

is_unique[users.email]
→ Email users table me duplicate nahi honi chahiye

matches[password]
→ Confirm password aur password same hone chahiye
</pre></code>

## 8. Password Hashing ⭐

<code><pre>
password_hash(
    $this->request->getPost('password'),
    PASSWORD_DEFAULT
);

</pre></code>

## 9. User Register Karna

Complete `store():`

<code><pre>
public function store()
{
    $rules = [
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]'
    ];

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $this->userModel->insert([
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),

        'password' => password_hash(
            $this->request->getPost('password'),
            PASSWORD_DEFAULT
        )
    ]);

    return redirect()
        ->to('/login')
        ->with('success', 'Registration successful. Please login.');
}
</pre></code>

Flow:

<code><pre>
Register Form
     ↓
Validation
     ↓
Password Hash
     ↓
UserModel
     ↓
insert()
     ↓
users table
     ↓
Login Page
</pre></code>

## 10. Login Form

Controller:

<code><pre>
public function login()
{
    return view('auth/login');
}
</pre></code>

Create: `app/Views/auth/login.php`

<code><pre>
<h1>Login</h1>

&lt;form action="/login" method="post"&gt;
    &lt;?= csrf_field() ?&gt;
    &lt;label&gt;Email&lt;/label&gt;
    &lt;input
        type="email"
        name="email"
        value="&lt;?= esc(old('email')) ?&gt;"
    &gt;
    &lt;br&gt;&lt;br&gt;
    &lt;label&gt;Password&lt;/label&gt;
    &lt;input
        type="password"
        name="password"
    &gt;
    &lt;br&gt;&lt;br&gt;
    &lt;button type="submit"&gt;
        Login
    &lt;/button&gt;
&lt;/form&gt;
</pre></code>

## 11. Login Validation

<code><pre>
public function authenticate()
{
    $rules = [
        'email' => 'required|valid_email',
        'password' => 'required'
    ];

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    // User check next
}
</pre></code>

## 12. Email se User Find Karna ⭐

Login form se email:

<code><pre>
$email = $this->request->getPost('email');

$user = $this->userModel
    ->where('email', $email)
    ->first();
</pre></code>

Model:

<code><pre>
->where('email', $email)
->first();
</pre></code>

## 13. User Nahi Mila?

<code><pre>
if (! $user) {
    return redirect()
        ->back()
        ->withInput()
        ->with('error', 'Invalid email or password.');
}
</pre></code>

## 14. Password Verify Karna ⭐

<code><pre>
$password = $this->request->getPost('password');

if (! password_verify($password, $user['password'])) {

    return redirect()
        ->back()
        ->withInput()
        ->with('error', 'Invalid email or password.');
}
</pre></code>

Concept:

<code><pre>
Entered Password
      ↓
password_verify()
      ↓
Stored Password Hash
      ↓
Match?
</pre></code>

## 15. Login Session Create Karna ⭐

- Email aur password correct hain.
- Ab session:

<code><pre>
session()->set([
    'user_id' => $user['id'],
    'user_name' => $user['name'],
    'user_email' => $user['email'],
    'logged_in' => true
]);
</pre></code>

## 16. Complete Login Method

<code><pre>
public function authenticate()
{
    $rules = [
        'email' => 'required|valid_email',
        'password' => 'required'
    ];

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $user = $this->userModel
        ->where('email', $email)
        ->first();

    if (
        ! $user ||
        ! password_verify($password, $user['password'])
    ) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Invalid email or password.');
    }

    session()->set([
        'user_id' => $user['id'],
        'user_name' => $user['name'],
        'user_email' => $user['email'],
        'logged_in' => true
    ]);

    return redirect()->to('/dashboard');
}
</pre></code>

## 17. Dashboard

Controller:

<code><pre>
public function dashboard()
{
    if (! session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    return view('dashboard');
}
</pre></code>

View: `app/Views/dashboard.php`

<code><pre>
&lt;$h1&gt;
    Welcome &lt;?= esc(session()->get('user_name')) ?&gt;
&lt;/h1&gt;

&lt;p&gt;
    &lt;?= esc(session()->get('user_email')) ?&gt;
&lt;/p&gt;
</pre></code>

## 18. Protected Page Concept

<code><pre>
if (! session()->get('logged_in')) {
    return redirect()->to('/login');
}
</pre></code>

## 19. Logout ⭐

View me logout form:

<code><pre>
&lt;form action="/logout" method="post"&gt;
    &lt;?= csrf_field() ?&gt;
    &lt;button type="submit"&gt;
        Logout
    &lt;/button&gt;
&lt;/form&gt;
</pre></code>

Controller:

<code><pre>
public function logout()
{
    session()->destroy();

    return redirect()
        ->to('/login')
        ->with('success', 'Logged out successfully.');
}
</pre></code>

Flow:

<code><pre>
Logout
   ↓
session()->destroy()
   ↓
Login Session Removed
   ↓
/login
</pre></code>

## 20. Complete Authentication Flow

<code><pre>
              REGISTER
                  │
                  ↓
           Validate Form
                  │
                  ↓
          password_hash()
                  │
                  ↓
             Database
                  │
                  ↓
                Login
                  │
          ┌───────┴────────┐
          ↓                ↓
     Find Email      User Not Found
          ↓                ↓
  password_verify()       Error
          ↓
     Password Correct?
       ↙          ↘
     YES           NO
      ↓             ↓
 Session Create    Error
      ↓
 Dashboard
      ↓
    Logout
      ↓
Session Destroy
</pre></code>

## 21. Error Messages View me

Errors:

<code><pre>
<?php if (session()->has('errors')): ?>

    <?php foreach (session('errors') as $error): ?>

        <p>
            <?= esc($error) ?>
        </p>

    <?php endforeach; ?>

<?php endif; ?>
</pre></code>

Errors:

<code><pre>
<?php if ($message = session()->getFlashdata('error')): ?>

    <p>
        <?= esc($message) ?>
    </p>

<?php endif; ?>
</pre></code>

Success:

<code><pre>
<?php if ($message = session()->getFlashdata('success')): ?>

    <p>
        <?= esc($message) ?>
    </p>

<?php endif; ?>
</pre></code>

## 🧠 Interview Questions

**Q1. Authentication kya hai?**

- User ki identity/credentials verify karna.

**Q2. Password database me kaise store karna chahiye?**

- Hash form me: `password_hash($password, PASSWORD_DEFAULT);`

**Q3. Login password verify?**

`password_verify($password, $hash);`

**Q4. User email find?**

<code><pre>
$user = $model
    ->where('email', $email)
    ->first();
</pre></code>

**Q5. Login information maintain kaise karenge?**

Session:
<code><pre>
session()->set([
    'user_id' => $user['id'],
    'logged_in' => true
]);
</pre></code>

**Q6. Logout?**

`session()->destroy();`