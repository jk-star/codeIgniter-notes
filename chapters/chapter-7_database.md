# Chapter 7 — Database Connection in CodeIgniter 4

## Database Configuration

- `.env` open karo.
- Aapko commented configuration mil sakti hai:

<code><pre>
# database.default.hostname = localhost
# database.default.database = ci4
# database.default.username = root
# database.default.password = root
# database.default.DBDriver = MySQLi
</pre></code>

- `#` hata kar apni values set karo:

<code><pre>
database.default.hostname = localhost
database.default.database = ci4_practice
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
</pre></code>

## 2. Development Environment

`.env` me:

`CI_ENVIRONMENT = development`

- Development ke time error aane par debugging easy hogi.

## 3. Connection Test Karte Hain

**Controller banao:**

`php spark make:controller DatabaseTest`

**File:**

`app/Controllers/DatabaseTest.php`

**Controller:**

<code><pre>
&lt;?php

namespace App\Controllers;

class DatabaseTest extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        if ($db->connID) {
            return 'Database Connected Successfully';
        }

        return 'Database Connection Failed';
    }
}
</pre></code>

**Route:**

`$routes->get('/database-test', 'DatabaseTest::index');`

**Browser:**

`http://localhost:8080/database-test`

## Interview Questions

**Q1. CI4 database configuration kahan kar sakte hain?**

`.env`

**aur:**

`app/Config/Database.php`

**Q2. Database connect kaise kar sakte hain?**

`$db = \Config\Database::connect();`

**Q3. Query Builder kya hai?**

- Database queries ko programmatically build/run karne ke liye CI4 ka database interface.

**Example:**

<code><pre>
$builder = $db->table('products');
$builder->get();

</pre></code>

Q4. `getResultArray()` kya deta hai?
- Results ko array format me deta hai.
<code><pre>
$products = $builder
    ->get()
    ->getResultArray();
</pre></code>

**Q5. .env ka benefit?**
- Environment-specific configuration ko application code se separate rakhna.