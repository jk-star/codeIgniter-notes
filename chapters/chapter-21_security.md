# Chapter 21 — Security

- **Goal**: CI4 application ko common attacks aur security mistakes se protect karna.

## 1. Web Security ka Basic Concept

Suppose application me form hai:

<code><pre>
User Input
    ↓
Controller
    ↓
Database
</pre></code>

- User jo input bhej raha hai wo hamesha safe ho, ye assume nahi karna chahiye.

<code><pre>
User Input
    ↓
Validate
    ↓
Process
    ↓
Escape Output
    ↓
Database / View
</pre></code>

**Golden Rule:**

- User input ko trust mat karo. Validate input, safe database APIs use karo, aur output ko context ke hisaab se escape karo.

## 2. CSRF Protection

**Cross-Site Request Forgery**

- **Example:** User aapki website me logged in hai. Kisi malicious page se user ke browser ko unwanted request bhejne ki koshish ki ja sakti hai.

<code><pre>
Logged-in User
      ↓
Malicious Request
      ↓
POST /products/delete
      ↓
Server
</pre></code>

- CSRF protection ka purpose hai verify karna ki state-changing request legitimate application flow se aayi hai.

## 3. CSRF Enable Karna

- CI4 me security/filter configuration version/setup ke hisaab se configured hoti hai.

- Typical approach me app/Config/Filters.php ke globals me CSRF filter enable kiya ja sakta hai:

<code><pre>
public array $globals = [
    'before' => [
        'csrf',
    ],
    'after' => [],
];
</pre></code>

- Ab relevant requests CSRF protection se pass hongi.

## 4. Form me CSRF Token

Form:

<code><pre>
&lt;form action="/products/store" method="post"&gt;
    &lt;?= csrf_field() ?&gt;
    &lt;input
        type="text"
        name="name"
    &gt;
    &lt;button type="submit"&gt;
        Save
    &lt;/button&gt;
&lt;/form&gt;
</pre></code>

- `csrf_field()` hidden security field generate karta hai.

Conceptually:

<code><pre>
&lt;input
    type="hidden"
    name="csrf_test_name"
    value="random-secure-token"
&gt;
</pre></code>

- Actual field/token values configuration par depend kar sakte hain.

## 5. CSRF Flow
<code><pre>
Form Open
    ↓
CSRF Token Generate
    ↓
User Submit
    ↓
Token Server ko gaya
    ↓
Server Token Verify
   ↙          ↘
Valid       Invalid
 ↓             ↓
Process       Reject
</pre></code>

**Yaad rakho** 

`<?= csrf_field() ?>`

- POST forms me important hai jab CSRF filter enabled ho.

## 6. XSS Kya Hai?

**Cross-Site Scripting**

- Suppose user product name me malicious HTML/script input deta hai.

<code><pre>
&lt;script&gt;
    alert('Hacked')
&lt;/script&gt;
</pre></code>

- Agar application ise unsafe way se HTML me output kare, browser ise execute kar sakta hai.

## 7. Output Escape with esc()

Unsafe output:

`<?= $product['name'] ?>`

User-controlled data ke liye safer:

`<?= esc($product['name']) ?>`

Suppose data:

`<script>alert('test')</script>`

- `esc()` ke baad browser ise executable HTML ke bajay text ke roop me treat kar sakta hai.

**Important rule**

- Views me user/database generated plain-text values:

`<?= esc($value) ?>`

- use karna strong default hai.

## 8. esc() Kahan Use Karein?

Product name:

`<?= esc($product['name']) ?>`

User name:

`<?= esc($user['name']) ?>`

Comment:

`<?= esc($comment['message']) ?>`


Search value:

<code><pre>
&lt;input
    value="<?= esc($keyword, 'attr') ?>"
&gt;
</pre></code>

- Output context matter karta hai. HTML body aur HTML attribute ke escaping requirements same nahi hote.

## 9. Validation vs Escaping

**Validation**

Check karta hai: `Input acceptable hai?`

Example: `'name' => 'required|min_length[3]'`

**Escaping**

- Check nahi karta; output ko safe context me represent karta hai.

`<?= esc($name) ?>`

Easy:

<code><pre>
Validation
→ Input Rules

Escaping
→ Safe Output
</pre></code>

- Dono useful hain.

## 10. SQL Injection

- Suppose unsafe raw SQL manually concatenate ki:

<code><pre>
$sql = "
SELECT *
FROM users
WHERE email = '$email'
";
</pre></code>

- Attacker specially crafted input bhejne ki koshish kar sakta hai.

- Isi type ki vulnerability ko SQL Injection kehte hain.

## 11. Query Builder Protection

Prefer:

<code><pre>
$user = $builder
    ->where('email', $email)
    ->get()
    ->getRowArray();
</pre></code>

Ya Model:

<code><pre>
$user = $this->userModel
    ->where('email', $email)
    ->first();
</pre></code>

- CI4 Query Builder values ko safely bind/escape karne me help karta hai.

**Avoid** `->where("email = '$email'")`

- User values ko SQL strings me manually concatenate karna avoid karo.

## 12. Raw SQL Karna Ho To Bind Values

**❌ Bad:**

<code><pre>
$query = $db->query(
    "SELECT * FROM users WHERE email='$email'"
);
</pre></code>

**Better:**

<code><pre>
$query = $db->query(
    'SELECT * FROM users WHERE email = ?',
    [$email]
);
</pre></code>

- ? placeholder hai.

<code><pre>
SQL
 ↓
Placeholder
 ↓
Value Binding
</pre></code>

- SQL Injection risk reduce hota hai.

## 13. Password Security

- Password ko kabhi plain text me store nahi karna.

**❌ Wrong:**

`'password' => $password`

**Database:** `123456`

- Agar database leak hua to password directly visible.

## 14. password_hash()

Registration:

<code><pre>
$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);
</pre></code>

Database me: `$2y$...` store hoga.

Then: 

<code><pre>
$this->userModel->insert([
    'email'    => $email,
    'password' => $hashedPassword
]);
</pre></code>

## 15. Login — password_verify()

Database:

<code><pre>
$user = $this->userModel
    ->where('email', $email)
    ->first();
</pre></code>

Then:
<code><pre>
if (
    $user &&
    password_verify(
        $password,
        $user['password']
    )
) {

    // Login success

}
</pre></code>

**Remember**

<code><pre>
Register
   ↓
password_hash()


Login
   ↓
password_verify()
</pre></code>

<code><pre>
</pre></code>

<code><pre>
</pre></code>

<code><pre>
</pre></code>

<code><pre>
</pre></code>