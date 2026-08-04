# Chapter 20 — Error Handling, Logging & Debugging

- **Goal:** Application me error aaye to usko properly handle, debug aur log kaise karein.

**Simple flow:**

<code><pre>
Application
    ↓
Error aaya?
    ↓
Handle Error
    ↓
Log Details
    ↓
User ko Safe Message
</pre></code>

## 1. Error Handling kya hai?

- Suppose database insert fail ho gaya:

`$this->productModel->insert($data);`

User ko ye dikhana:

<code><pre>
SQLSTATE...
Database connection...
File path...
Line number...
</pre></code>

❌ Bad practice.

User ko:

`Something went wrong. Please try again.`

dikhana better hai.

Aur actual technical error: `writable/logs/` me save karna chahiye.

## 2. Development vs Production

`.env:`

`CI_ENVIRONMENT = development`

Development me detailed errors useful hain.

<code><pre>
Error Message
File Name
Line Number
Stack Trace
</pre></code>

- Local development ke liye: `CI_ENVIRONMENT = development`
- Production server par: `CI_ENVIRONMENT = production`

## 3. Development Error Example

**Suppose:** echo $abc;

- aur variable properly defined nahi hai.

- Development environment debugging information dikha sakta hai:

<code><pre>
Undefined variable $abc

File:
Product.php

Line:
25

</pre></code>

Isse developer ko problem locate karne me help milti hai.

## 4. try/catch

<code><pre>
try {

    // risky code

} catch (\Throwable $e) {

    // error handling

}
</pre></code>

**Example:**

<code><pre>
try {

    $this->productModel->insert($data);

} catch (\Throwable $e) {

    return 'Something went wrong';

}
</pre></code>

**Flow:**

<code><pre>
try
 ↓
Code Run
 ↓
Error?
 ↙    ↘
NO     YES
↓       ↓
Continue catch
</pre></code>


## 5. Practical try/catch

<code><pre>
public function store()
{
    try {

        $data = [
            'name'  => $this->request->getPost('name'),
            'price' => $this->request->getPost('price')
        ];

        $this->productModel->insert($data);

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product added successfully'
            );

    } catch (\Throwable $e) {

        return redirect()
            ->back()
            ->with(
                'error',
                'Something went wrong'
            );
    }
}
</pre></code>

- User ko technical error nahi milega.

## 6. Error ko Log Karna

- Sirf catch karna enough nahi.

- Developer ko actual error pata bhi chalna chahiye.

**Use:**

<code><pre>
log_message(
    'error',
    $e->getMessage()
);

</pre></code>

**Complete:**

<code><pre>
try {

    $this->productModel->insert($data);

} catch (\Throwable $e) {

    log_message(
        'error',
        $e->getMessage()
    );

    return redirect()
        ->back()
        ->with(
            'error',
            'Something went wrong'
        );
}
</pre></code>

**Ab:**

<code><pre>
User
 ↓
Something went wrong
</pre></code>

**Developer:**

<code><pre>
Log File
 ↓
Actual Error Details
</pre></code>

## 7. Logs Kahan Save Hote Hain?

**Normally:**

<code><pre>
writable/
└── logs/
    └── log-2026-08-03.log
</pre></code>

**Example log:**

`ERROR - Database connection failed`

- Ye debugging me bahut useful hota hai.

## 8. Log Levels

**Common levels:**

| Level       | Use                               |
| ----------- | --------------------------------- |
| `debug`     | Development/debugging information |
| `info`      | Normal important event            |
| `warning`   | Potential problem                 |
| `error`     | Actual error                      |
| `critical`  | Serious failure                   |
| `alert`     | Immediate attention               |
| `emergency` | System unusable                   |


## 9. log_message()

**Debug**

<code><pre>
log_message(
    'debug',
    'Product store method started'
);
</pre></code>

**Info**

<code><pre>
log_message(
    'info',
    'Product created successfully'
);
</pre></code>

**Warning**

<code><pre>
log_message(
    'warning',
    'Product stock is low'
);
</pre></code>

**Error**

<code><pre>
log_message(
    'error',
    'Product could not be created'
);
</pre></code>

## 10. Log me Variables

**Suppose:** `$productId = 10;`

**Log:**

<code><pre>
log_message(
    'info',
    'Product ID: ' . $productId
);
</pre></code>

**Better context:**

<code><pre>
log_message(
    'error',
    'Unable to update product ID: ' . $productId
);
</pre></code>

**Log:** `Unable to update product ID: 10`

## 11. Exception Details

**Catch :** `catch (\Throwable $e)`

**Error message :** `$e->getMessage();`

**File :** `$e->getFile();`

**Line number :** `$e->getLine();`

**Example:**
<code><pre>
log_message(
    'error',
    'Error: {message} | File: {file} | Line: {line}',
    [
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine()
    ]
);
</pre></code>

- Ye information log me useful hai, browser par production user ko nahi dikhani.

## 12. Exception vs Throwable

- Aapko code me dono mil sakte hain: `catch (\Exception $e)`
- aur: `catch (\Throwable $e)`
- Simple understanding:
<code><pre>
Throwable
   ↓
Exceptions + many PHP Errors
</pre></code>

- Broad application-level handling me: `catch (\Throwable $e)` useful hai.

## 13. API Error Handling
- API ko JSON response dena hai.

**Example:**
<code><pre>
public function create()
{
    try {

        $data = $this->request->getJSON(true);

        $this->model->insert($data);

        return $this->respondCreated([
            'status'  => true,
            'message' => 'Product created'
        ]);

    } catch (\Throwable $e) {

        log_message(
            'error',
            $e->getMessage()
        );

        return $this->failServerError(
            'Something went wrong'
        );
    }
}
</pre></code>

**Response:**

<code><pre>
{
    "status": 500,
    "error": 500,
    "messages": {
        "error": "Something went wrong"
    }
}
</pre></code>

## 14. Proper HTTP Errors
- API me appropriate response important hai.

**Not Found**
<code><pre>
return $this->failNotFound(
    'Product not found'
);
</pre></code>

Status: 404

**Validation**

<code><pre>
return $this->failValidationErrors(
    $this->validator->getErrors()
);

</pre></code>

**Unauthorized**

<code><pre>
return $this->failUnauthorized(
    'Please login'
);

</pre></code>

**Server Error**

<code><pre>
return $this->failServerError(
    'Something went wrong'
);

</pre></code>

## 15. Validation Error ≠ Exception

- **User:** `Name = blank`

- Ye expected validation problem hai.

**Use:**

<code><pre>
if (! $this->validate($rules)) {

    return redirect()
        ->back()
        ->withInput();

}
</pre></code>

- `try/catch` ki zarurat nahi.

**Unexpected problem:**

<code><pre>
Database unavailable
File operation failed
Unexpected service failure
</pre></code>

- Is type ki exceptional situation me exception handling relevant ho sakti hai.

**Easy Rule**

<code><pre>
Wrong User Input
      ↓
Validation


Unexpected Technical Failure
      ↓
Exception Handling / Logging
</pre></code>


## 16. dd() Debugging

Development ke time: `dd($product);`

Example:

<code><pre>
$product = $this->productModel->find(1);
dd($product);
</pre></code>

- Output inspect karega aur execution stop kar dega.

Useful when checking:
<code><pre>
Array me kya aa raha hai?
Database result kya hai?
Variable ki value kya hai?
</pre></code>

## 17. d() Debugging

`d($product);`

Data inspect karta hai.

Difference roughly:

<code><pre>
d()
→ Dump

dd()
→ Dump + Die
</pre></code>

- `dd()` ke baad execution stop.

## 18. Debug Toolbar

- Browser ke bottom area me debugging details mil sakti hain, such as:

<code><pre>
Execution Time
Memory
Database Queries
Logs
Request
Routes
Files
</pre></code>

**Example:**

<code><pre>
Page Load: 0.12 sec
Queries: 5
Memory: 4 MB
</pre></code>

## 19. Database Query Debugging
Suppose Query Builder:
<code><pre>
$builder
    ->where('price >', 1000)
    ->get();

</pre></code>

- Generated query inspect karne ke liye database connection se last query dekh sakte ho:

<code><pre>
$db = db_connect();

$query = $db
    ->table('products')
    ->where('price >', 1000)
    ->get();

dd((string) $db->getLastQuery());
</pre></code>

**Output roughly:** `SELECT * FROM products WHERE price > 1000`

- Query Builder debug karne me bahut useful.

## 20. Production me dd() Mat Chhodna

Development: `dd($data);` ✅ Debugging ke liye.

Production: `dd($data);` ❌ Avoid.

- Sensitive information expose ho sakti hai aur page execution stop ho jayega.

## 21. `echo` Debugging vs Logging

Beginner often: 

`echo $variable;`
`die;`

- use karta hai.

- Development me kabhi useful ho sakta hai, but better tools hain:

`dd($variable);`

ya:

<code><pre>
log_message(
    'debug',
    'Value: ' . $variable
);
</pre></code>

## 22. Custom 404 Handling — Concept

- User open karta hai: `/products/99999` Product nahi hai.

**Controller me:**

<code><pre>
$product = $this->productModel->find($id);

if (! $product) {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
}
</pre></code>

Result: `404 Page Not Found`

- Normal web pages ke liye appropriate.

API me:

<code><pre>
return $this->failNotFound(
    'Product not found'
);
</pre></code>

- better hai because JSON response chahiye.

## 23. Logging Configuration
- Logging configuration: `app/Config/Logger.php`

**concept:**

<code><pre>
log_message()
      ↓
Logger
      ↓
Configured Handler
      ↓
writable/logs/
</pre></code>

## 24. Sensitive Data Log Mat Karna

- Ye bahut important security rule hai.

**❌ Never log:**

<code><pre>
Password
Credit Card Number
Authentication Token
API Secret
SMTP Password
</pre></code>

**Bad:**

<code><pre>
log_message(
    'debug',
    'Password: ' . $password
);
</pre></code>

**❌ Better:**

<code><pre>
log_message(
    'info',
    'Login attempt for user ID: ' . $userId
);
</pre></code>

## 25. Real Product CRUD Example

<code><pre>
public function delete($id)
{
    try {

        $product = $this->productModel->find($id);

        if (! $product) {

            throw \CodeIgniter\Exceptions\PageNotFoundException
                ::forPageNotFound(
                    'Product not found'
                );
        }

        $this->productModel->delete($id);

        log_message(
            'info',
            'Product deleted. ID: ' . $id
        );

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product deleted successfully'
            );

    } catch (
        \CodeIgniter\Exceptions\PageNotFoundException $e
    ) {

        throw $e;

    } catch (\Throwable $e) {

        log_message(
            'error',
            'Product delete failed: ' . $e->getMessage()
        );

        return redirect()
            ->to('/products')
            ->with(
                'error',
                'Unable to delete product'
            );
    }
}
</pre></code>

**Concept:**

<code><pre>
Delete Request
      ↓
Product Exists?
   ↙       ↘
 NO        YES
 ↓          ↓
404       Delete
            ↓
          Success

Unexpected Error
      ↓
Log Error
      ↓
Safe Message
</pre></code>

## 26. Error Handling Flow

<code><pre>
      REQUEST
                   ↓
              Validation
               ↙     ↘
            Fail      Pass
             ↓         ↓
       Validation    Business
          Error        Logic
                        ↓
                  Technical Error?
                    ↙       ↘
                   NO       YES
                   ↓         ↓
                Success   Exception
                             ↓
                           Catch
                             ↓
                            Log
                             ↓
                       Safe Response
</pre></code>

## 🧠 Interview Questions

**Q1. try/catch ka use?**

- Unexpected exceptions/errors ko handle karne ke liye.

<code><pre>
try {

} catch (\Throwable $e) {

}
</pre></code>

**Q2. CI4 me logging?**

<code><pre>
log_message(
    'error',
    'Something failed'
);
</pre></code>

**Q3. Logs kahan?**

`writable/logs/`

**Q4. Development environment?**

`CI_ENVIRONMENT = development`

**Q5. Production?**

`CI_ENVIRONMENT = production`

**Q6. dd()?**

- Variable dump karta hai aur execution stop karta hai.

**Q7. API me 404?**

`$this->failNotFound();`

**Q8. Server error?**

`$this->failServerError();`
