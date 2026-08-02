# Chapter-1 Introduction

## 1. CodeIgniter 4 kya hai?
- CodeIgniter 4 ek PHP framework hai jo web applications banane ke liye use hota hai.

**Simple flow:**
<code><pre>
Browser Request
      ↓
   Routes
      ↓
 Controller
      ↓
    Model
      ↓
  Database
      ↓
    View
      ↓
   Browser
</pre></code>

- CI4 mainly MVC pattern follow karta hai.

## 2. CI3 vs CI4 — Important Difference

| CI3               | CI4                        |
| ----------------- | -------------------------- |
| Old architecture  | Modern architecture        |
| `CI_Controller`   | `BaseController`           |
| Namespaces nahi   | Namespaces important       |
| Composer optional | Composer commonly used     |
| Routes basic      | Better routing             |
| `$this->load`     | Modern loading/autoloading |
| Hooks             | Filters                    |
| Old PHP style     | Modern PHP/OOP             |

**Example**

<code><pre>
namespace App\Controllers;

class Users extends BaseController
{
    public function index()
    {
        return view('users');
    }
}
</pre></code>

## 3. Requirements

<code><pre>
PHP
Composer
MySQL
VS Code
Browser
</pre></code>

- Terminal me PHP check karein:

`php -v`

**Composer:**

`composer -V`

## 4. CI4 Project Create Karna

- Terminal open karein aur jis folder me project banana hai wahan:

`composer create-project codeigniter4/appstarter ci4-project`

**Installation ke baad:**

`cd ci4-project`

**Server run:**

`php spark serve`

**Terminal kuch aisa URL dega:**

`http://localhost:8080`

- Browser me open karein.

**CI4 welcome page aa gaya:**

<code><pre>
CodeIgniter
Welcome to CodeIgniter
</pre></code>

- to installation successful. ✅

## 5. `spark` kya hai?

**CI4 me:**

- spark = Command Line Tool

**Example:**

`php spark serve`

- Development server start karta hai.

**Available commands dekhne ke liye:**

`php spark`

**Aage hum commands use karenge jaise:**

<code><pre>
php spark make:controller
php spark make:model
php spark make:migration
php spark migrate
</pre></code>

## Interview Question: 

**CI4 me spark kya hai?**
- Spark CodeIgniter 4 ka command-line interface (CLI) hai, jisse development tasks aur framework commands run kar sakte hain.

## 6. .env File

**Project me initially ye mil sakti hai:**

`env`

**Development ke liye ise rename/copy karke:**

`.env`

- banaya ja sakta hai.
- Isme environment aur database jaise configuration values rakhi ja sakti hain.

**Example:**

`CI_ENVIRONMENT = development`

**Database later configure karenge:**

<code><pre>
database.default.hostname = localhost
database.default.database = ci4_db
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
</pre></code>

- Abhi database configuration karne ki zarurat nahi.

## 7. Development Mode

`.env:`

`CI_ENVIRONMENT = development`

- Development ke time useful hai kyunki errors/debugging information milti hai.

**Production server par:**

`CI_ENVIRONMENT = production`

- use kiya ja sakta hai.