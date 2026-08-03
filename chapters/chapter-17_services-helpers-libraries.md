# Chapter 17 — Services, Helpers & Libraries

**Helper**   → Chhote reusable functions

**Library**  → Reusable class / business logic

**Service**  → Common/shared object ko access karne ka way

## 1. Helper kya hota hai?

- Helper basically functions ka collection hota hai.

## 2. Built-in Helpers ⭐

**Common Helpers**

| Helper                   | Load Karne ka Code     | Main Use                      | Common Functions                                                                  |
| ------------------------ | ---------------------- | ----------------------------- | --------------------------------------------------------------------------------- |
| **URL Helper**        | `helper('url')`        | URL banana/manage karna       | `base_url()`, `site_url()`, `url_to()`, `url_title()`                             |
| **Form Helper**       | `helper('form')`       | Forms create karna            | `form_open()`, `form_input()`, `form_dropdown()`, `form_submit()`, `form_close()` |
| **Text Helper**        | `helper('text')`       | Text manipulate karna         | `word_limiter()`, `character_limiter()`, `word_censor()`, `highlight_phrase()`    |
| **Number Helper**      | `helper('number')`     | Numbers/size format karna     | `number_to_size()`, `number_to_amount()`, `number_to_currency()`                  |
| **Date Helper**        | `helper('date')`       | Date/time related operations  | `now()`, `timezone_select()`                                                      |
| **Filesystem Helper**  | `helper('filesystem')` | Files/folders handle karna    | `directory_map()`, `write_file()`, `delete_files()`, `get_filenames()`            |
| **Cookie Helper**      | `helper('cookie')`     | Cookies manage karna          | `set_cookie()`, `get_cookie()`, `delete_cookie()`                                 |
| **Security Helper**   | `helper('security')`   | Security-related utilities    | `sanitize_filename()`, `strip_image_tags()`                                       |
| **HTML Helper**         | `helper('html')`       | HTML elements generate karna  | `img()`, `link_tag()`, `heading()`, `ul()`, `ol()`                                |
| **Inflector Helper**    | `helper('inflector')`  | Words transform karna         | `singular()`, `plural()`, `underscore()`, `humanize()`                            |
| **Array Helper**        | `helper('array')`      | Array se specific values lena | `dot_array_search()`, `array_deep_search()`                                       |
| **Custom Helper**    | `helper('product')`    | Apne reusable functions       | `format_price()`, `generate_slug()` etc.                                          |


## 3. Custom Helper

Create:

`app/Helpers/product_helper.php`

Code:

<code><pre>
&lt;$?php

if (! function_exists('format_price')) {

    function format_price($price)
    {
        return '₹' . number_format($price, 2);
    }
}
</pre></code>

## 4. Helper Kab Use Karein?
- Helper best hai jab simple reusable function ho.

## 5. Library Kya Hai? ⭐

- Helper function-based hota hai.
- Library generally class-based reusable logic hoti hai.
- Suppose hamare project me invoice calculation baar-baar hoti hai:

<code><pre>
Subtotal
Discount
Tax
Final Total
</pre></code>

- Controller me calculation repeat nahi karna.
- Custom class bana sakte hain.

## 6. Custom Library Example

Create folder: `app/Libraries/`

File: `app/Libraries/PriceCalculator.php`

Code:

<code><pre>
&lt;?php

namespace App\Libraries;

class PriceCalculator
{
    public function calculateTax($amount, $taxPercent)
    {
        return ($amount * $taxPercent) / 100;
    }

    public function finalPrice($amount, $taxPercent)
    {
        $tax = $this->calculateTax(
            $amount,
            $taxPercent
        );

        return $amount + $tax;
    }
}
</pre></code>

## 7. Library Controller me Use Karna

Import: `use App\Libraries\PriceCalculator;`

Then:

<code><pre>
public function price()
{
    $calculator = new PriceCalculator();

    $total = $calculator->finalPrice(
        1000,
        18
    );

    return (string) $total;
}
</pre></code>

Output: `1180`

Flow:

<code><pre>
Controller
    ↓
PriceCalculator
    ↓
Reusable Business Logic
    ↓
Result
</pre></code>

## 8. Helper vs Library

| Helper             | Library                           |
| ------------------ | --------------------------------- |
| Functions          | Class                             |
| Small utility work | Larger reusable logic             |
| Usually stateless  | State/dependencies rakh sakti hai |
| `helper()` se load | Class instantiate/import          |
| `format_price()`   | `$calculator->finalPrice()`       |


## 9. Service Kya Hai?

- Application ke common objects/services ko centrally access karna.

**Examples:**

<code><pre>
Request
Response
Session
Validation
Email
Cache
Logger
</pre></code>

## 10. service() Function

Example:  `$request = service('request');`

Then : `$name = $request->getPost('name');`

`$this->request->getPost('name'); `

- `BaseController` ki wajah se request object conveniently available hai.

- Service directly: `$request = service('request');`

- bhi access kar sakte hain.

## 11. Validation Service

<code><pre>
`$validation = service('validation');`

`Rules:`                                             

$validation->setRules([
    'name' => 'required|min_length[3]',
    'price' => 'required|numeric'
]);

`Run:`

if (! $validation->withRequest($this->request)->run()) {

    return redirect()
        ->back()
        ->withInput();
}

$this->validate($rules);

</pre></code>

- higher-level convenient approach hai.

## 11. Session Service

Hum use kar rahe the:

`session()->set('name', 'jonh');`

Service se:

<code><pre>
$session = service('session');

`$session->set( 'name', 'jonh' );`

</pre></code>

Get: `$name = $session->get('name');`

- Dono styles mil sakti hain.

## 12. Response Service

`$response = service('response');`

JSON response:

<code><pre>
return $response->setJSON([
    'status' => true,
    'message' => 'Product added'
]);
</pre></code>

- API development me bahut useful.

## 13. Logger Service

Logging:

<code><pre>
log_message(
    'error',
    'Product could not be saved'
);
</pre></code>

Ya logger service:

<code><pre>
$logger = service('logger');

`$logger->error('Product could not be saved );`

</pre></code>

Useful for:

<code><pre>
Errors
Debugging
API failures
Payment failures
Important events
</pre></code>

- Logs commonly: `writable/logs/` me milte hain.

## 14. Email Service

`$email = service('email');`

Configure/send example:

<code><pre>
$email->setFrom(
    'admin@example.com',
    'My Website'
);

$email->setTo(
    'user@example.com'
);

$email->setSubject(
    'Welcome'
);

$email->setMessage(
    'Welcome to our website.'
);

$email->send();
</pre></code>

Real projects:

<code><pre>
Registration Email
Password Reset
Order Confirmation
Contact Form
OTP/notification workflows
</pre></code>

- Actual sending ke liye SMTP/email configuration bhi required hogi.

## 15. Cache Service

- Suppose database query expensive hai aur data frequently change nahi hota.

`$cache = service('cache');`

Save:

<code><pre>
$cache->save(
    'products',
    $products,
    300
);
</pre></code>

`300 seconds = 5 minutes`

Get: `$products = $cache->get('products');`

Delete: `$cache->delete('products');`

## 16. Cache ka Benefit

Without cache:

<code><pre>
Request
 ↓
Database Query
 ↓
Result
</pre></code>

Har request database hit karegi.
With cache:

<code><pre>
Request
 ↓
Cache Available?
 ↙          ↘
YES          NO
 ↓            ↓
Result      Database
              ↓
          Save Cache
              ↓
            Result
</pre></code>

Performance improve ho sakti hai.

## 17. Custom Service — Concept

- Suppose `PriceCalculator` ko poore project me service ki tarah access karna hai.

- CI4 custom Services configuration ke through custom shared service register kar sakta hai.

**Conceptually:**
<code><pre>
PriceCalculator Class
        ↓
Services Config
        ↓
service('priceCalculator')
        ↓
Reusable Shared Object
</pre></code>

## 18. Service vs Library
**Library**

- Aap class bana rahe hain:

`$calculator = new PriceCalculator();`

- Har jagah manually instantiate kar sakte hain.

**Service**

- Object creation/access ko centralized mechanism se handle karte hain:

`$validation = service('validation');`

**Simple concept:**
<code><pre>
Library
→ Reusable Class

Service
→ Reusable object ko centrally obtain/manage karne ka mechanism
</pre></code>

## 19. Most Useful Services

<code><pre>
service('request');
service('response');
service('session');
service('validation');
service('email');
service('cache');
service('logger');
</pre></code>

## 🧠 Interview Questions
**Q1. Helper kya hai?**
- Small reusable functions ka collection.

`helper('url');`

**Q2. Custom Helper kahan?**

`app/Helpers/`

**Q3. Helper load?**

`helper('product');`

**Q4. Library kya hai?**

- Reusable class containing application/business logic.

**Example:**

<code><pre>
use App\Libraries\PriceCalculator;
$calculator = new PriceCalculator();

</pre></code>

**Q5. Service kya hai?**

- Application ke commonly used objects ko centrally access/manage karne ka CI4 mechanism.

`$validation = service('validation');`

**Q6. Helper vs Library?**

<code><pre>
Helper
→ Functions

Library
→ Classes
</pre></code>

**Q7. Library vs Service?**

<code><pre>
Library
→ Reusable class

Service
→ Object ko centrally obtain/manage karne ka mechanism
</pre></code>