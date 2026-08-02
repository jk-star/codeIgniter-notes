
# Chapter 2 — CI4 Folder Structure

## Basic structure:

<code><pre>
ci4-practice/
│
├── app/             ⭐ Main coding yahan
├── public/          ⭐ Browser-accessible files
├── writable/        ⭐ Logs/cache/uploads
├── vendor/          ⭐ Composer packages
│
├── .env             ⭐ Environment/config
├── composer.json
└── spark            ⭐ CLI
</pre></code>

## 1. app/ ⭐⭐⭐ Most Important
- Aapka maximum development work app/ ke andar hoga.

<code><pre>
app/
│
├── Config/
├── Controllers/
├── Database/
├── Filters/
├── Helpers/
├── Libraries/
├── Models/
└── Views/
</pre></code>

**Inme sabse important:**

<code><pre>
Controllers/
Models/
Views/
Config/
Database/
</pre></code>

## 2. Controllers/

**Location:**

`app/Controllers/`

- Controller browser ki request handle karta hai.

**Example:**

<code></pre>
URL
 ↓
Controller
 ↓
Response
</pre></code>

**Example file:**

`app/Controllers/User.php`

<code><pre>
<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index()
    {
        return "Hello User";
    }
}
</pre></code>

- Abhi code yaad nahi karna. Controllers chapter me proper practical karenge.

## 3. Models/

**Location:**

`app/Models/`

- Model mainly database ke saath kaam karta hai.

**For example:**

<code><pre>
UserController
      ↓
 UserModel
      ↓
   Database
</pre></code>

**Model se hum:**

<code><pre>
Insert
Update
Delete
Select
</pre></code>

- jaise database operations karenge.

**Example:**

`app/Models/UserModel.php`

## 4. Views/

**Location:**

`app/Views/`

- View me generally HTML/UI hoti hai.

**Example:**

`app/Views/users.php`

<code><pre>
&lt;h1&gt;User List&lt;/h1&gt;

&lt;button&gt;Add User&lt;/button&gt;
</pre></code>

**Simple rule:**

<code><pre>
Controller = Logic
Model = Database
View = UI
</pre></code>

## 5. Config/

**Location:**

`app/Config/`

- Application ki configuration files.

**Example:**

<code><pre>
app/Config/
├── App.php
├── Database.php
├── Routes.php
├── Filters.php
└── Validation.php
</pre></code>

- Sabse frequently use hone wali file:

`Routes.php`

**Example:**

`$routes->get('/users', 'User::index');`

- Routing next chapters me detail me karenge.

## 6. Database/

**Location:**

`app/Database/`

**Iske andar mainly:**

<code><pre>
Database/
├── Migrations/
└── Seeds/
</pre></code>

## Migration

- Database tables create/change karne ke liye.

<code><pre>
Migration
   ↓
users table
</pre></code>

## Seeder

- Dummy/initial data insert karne ke liye.

<code><pre>
Seeder
   ↓
users table
   ↓
10 sample users
</pre></code>

- Ye advanced database chapters me practical karenge.

## 7. Filters/

`app/Filters/`

- Request ko controller tak pahunchne se pehle/baad me process kar sakte hain.
- Authentication me bahut useful.

**Example:**

<code><pre>
/dashboard
     ↓
Auth Filter
     ↓
Login hai?
 ↙       ↘
YES       NO
 ↓         ↓
Dashboard  Login Page
</pre></code>

## 8. Helpers/

`app/Helpers/`

- Reusable functions ke liye.

**Example:**

<code><pre>
function formatPrice($price)
{
    return '₹' . $price;
}
</pre></code>

- Phir different places par:

`formatPrice(500);`

**Output:**

``₹500``

## 9. Libraries/

`app/Libraries/`

- Apni reusable custom classes rakh sakte hain.

**For example:**

<code><pre>
Libraries/
└── PaymentService.php
</pre></code>

- Abhi Helpers vs Libraries me deep nahi jana.

## 10. public/ ⭐

<code><pre>
public/
├── index.php
├── favicon.ico
└── robots.txt
</pre></code>

- `public/` web-accessible directory hai.
- Aap CSS/JS/images bhi iske andar organize kar sakti hain:

<code><pre>
public/
├── css/
│   └── style.css
├── js/
│   └── app.js
└── images/
    └── logo.png
</pre></code>

**For example:**

`<img src="/images/logo.png">`

## 11. writable/

<code><pre>
writable/
├── cache/
├── logs/
├── session/
└── uploads/
</pre></code>

- Runtime-generated data ke liye.
- Especially errors/debugging ke time:
- `writable/logs/` kaafi useful hota hai.

## 12. vendor/

`vendor/`  

- Composer-installed dependencies yahan hoti hain.
- Is folder ki files ko manually edit nahi karna.

## 13. .env

- Project-level environment settings:

`.env`

**Example:**

`CI_ENVIRONMENT = development`

- Database credentials bhi commonly yahin configure karte hain:

<code><pre>
database.default.hostname = localhost
database.default.database = ci4_db
database.default.username = root
database.default.password =
</pre></code>

## 14. spark

- `spark` CI4 ka CLI entry script.

**Example:**

`php spark serve`

**Later:**

`php spark make:controller User`
`php spark make:model UserModel`
`php spark migrate`

## Chapter 2 Short Notes

| Folder/File       | Kaam                          |
| ----------------- | ----------------------------- |
| `app/Controllers` | Request / logic               |
| `app/Models`      | Database                      |
| `app/Views`       | HTML/UI                       |
| `app/Config`      | Configuration                 |
| `app/Database`    | Migration/Seeder              |
| `public`          | CSS, JS, images + `index.php` |
| `writable`        | Logs/cache/runtime files      |
| `.env`            | Environment/database config   |
