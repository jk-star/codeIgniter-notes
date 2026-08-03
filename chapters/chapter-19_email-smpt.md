# Chapter 19 — Email Sending & SMTP

- **Goal:** CI4 se real emails send karna — Registration, Contact Form, Password Reset, Order Confirmation etc.

## 1. Email Service

Email service:

`$email = service('email');`

Ya:

`$email = \Config\Services::email();`

Then:

<code><pre>
$email->setTo();
$email->setSubject();
$email->setMessage();
$email->send();
</pre></code>

Basic flow:

<code><pre>
Email Service
    ↓
From
    ↓
To
    ↓
Subject
    ↓
Message
    ↓
send()
</pre></code>

## 2. Simple Email Example

Controller:

<code><pre>
public function sendEmail()
{
    $email = service('email');

    $email->setFrom(
        'admin@example.com',
        'My Website'
    );

    $email->setTo('user@example.com');

    $email->setSubject('Welcome');

    $email->setMessage(
        'Welcome to our website.'
    );

    if ($email->send()) {
        return 'Email sent successfully';
    }

    return 'Email sending failed';
}

</pre></code>

- But actual email send karne ke liye `SMTP configuration` chahiye.

## 3. SMTP Kya Hai?
- Simple Mail Transfer Protocol

Simple language me:

<code><pre>
CI4 Application
      ↓
SMTP Server
      ↓
Internet
      ↓
User Inbox
</pre></code>

Example SMTP providers:

<code><pre>
Gmail SMTP
Microsoft SMTP
Hosting SMTP
Amazon SES
Mailgun
SendGrid
</pre></code>

## 4. SMTP Configuration

`app/Config/Email.php`

- Lekin credentials ko source code me hard-code karne ke bajay `.env` me rakhna better hai.

Example:

<code><pre>
email.fromEmail = "your@example.com"
email.fromName = "My Website"

email.protocol = "smtp"

email.SMTPHost = "smtp.example.com"
email.SMTPUser = "your@example.com"
email.SMTPPass = "YOUR_SMTP_PASSWORD"

email.SMTPPort = 587
email.SMTPCrypto = "tls"

email.mailType = "html"
email.charset = "UTF-8"
</pre></code>

- Actual host, port aur encryption aapke email provider par depend karenge.

## 5. Complete Email Template Example

<code><pre>
public function sendWelcomeEmail()
{
    $email = service('email');

    $data = [
        'name'      => 'Rahul',
        'userEmail' => 'rahul@example.com'
    ];

    $message = view(
        'emails/welcome',
        $data
    );

    $email->setFrom(
        'admin@example.com',
        'My Website'
    );

    $email->setTo(
        $data['userEmail']
    );

    $email->setSubject(
        'Welcome to My Website'
    );

    $email->setMessage($message);

    if ($email->send()) {

        return 'Email sent successfully';

    }

    return 'Email sending failed';
}
</pre></code>

**Flow:**

<code><pre>
Controller
    ↓
Email View
    ↓
HTML
    ↓
Email Service
    ↓
SMTP
    ↓
User Inbox
</pre></code>

## 6. Registration ke Baad Email

<code><pre>
$this->userModel->insert([
    'name'     => $name,
    'email'    => $userEmail,
    'password' => $hashedPassword
]);
</pre></code>

Uske baad:

<code><pre>

$email = service('email');

$email->setTo($userEmail);

$email->setSubject(
    'Registration Successful'
);

$email->setMessage(
    'Welcome ' . esc($name)
);

`$email->send();`

</pre></code>

**Complete flow:**

<code><pre>
Register Form
      ↓
Validation
      ↓
Password Hash
      ↓
Database Insert
      ↓
Send Welcome Email
      ↓
Login Page
</pre></code>

## 7. Contact Form Example

Form:

<code><pre>
&lt;form action="/contact" method="post"&gt;
    &lt;input
        type="text"
        name="name"
        placeholder="Name"
    &gt;
    &lt;input
        type="email"
        name="email"
        placeholder="Email"
    &gt;
    &lt;textarea
        name="message"
        placeholder="Message"
    >&lt;/textarea&gt;
    &lt;button type="submit"&gt;
        Send
    &lt;/button&gt;
&lt;/form>
</pre></code>

Controller:

<code><pre>
public function contact()
{
    $name = $this->request->getPost('name');

    $userEmail = $this->request->getPost('email');

    $message = $this->request->getPost('message');

    $email = service('email');

    $email->setTo('admin@example.com');

    $email->setFrom(
        'no-reply@example.com',
        'Website Contact Form'
    );

    $email->setReplyTo(
        $userEmail,
        $name
    );

    $email->setSubject(
        'New Contact Message'
    );

    $email->setMessage(
        esc($message)
    );

    if ($email->send()) {

        return redirect()
            ->back()
            ->with(
                'success',
                'Message sent successfully'
            );
    }

    return redirect()
        ->back()
        ->with(
            'error',
            'Unable to send message'
        );
}
</pre></code>

**Important**

- Contact form me user-supplied email ko blindly From address banana avoid karo. Usually apne domain ka verified From address aur user's address ko Reply-To me rakhna better hota hai.

## 8. Attachment Send Karna

PDF/image attach:

<code><pre>
$email->attach(
    WRITEPATH . 'uploads/invoice.pdf'
);

</pre></code>

Then:

`$email->send();`

Useful : 

<code><pre>
Invoice PDF
Resume
Report
Receipt
</pre></code>

## 9. Multiple Recipients

<code><pre>
$email->setTo([
    'user1@example.com',
    'user2@example.com'
]);

</pre></code>

CC:

<code><pre>
$email->setCC(
    'manager@example.com'
);

</pre></code>

BCC:
<code><pre>
$email->setCC(
    'manager@example.com'
);

</pre></code>

## 10. Email Debugging 

- Email nahi ja rahi ho:

<code><pre>
if (! $email->send()) {
    echo $email->printDebugger();
}

</pre></code>

- Development me useful.

Possible errors:

- SMTP authentication failed

- Connection failed

- Invalid credentials

- Wrong port

- Wrong encryption

- Sender rejected

- Production me raw SMTP debugging output users ko mat dikhana, kyunki sensitive configuration/details leak ho sakti hain.

## 11. Email Service Reusable Banana

`app/Libraries/Mailer.php`

**Example:**

<code><pre>
&lt;?php

namespace App\Libraries;

class Mailer
{
    public function sendWelcome(
        string $to,
        string $name
    ): bool {

        $email = service('email');

        $message = view(
            'emails/welcome',
            [
                'name'      => $name,
                'userEmail' => $to
            ]
        );

        $email->setTo($to);

        $email->setSubject(
            'Welcome'
        );

        $email->setMessage($message);

        return $email->send();
    }
}
</pre></code>

Controller:

<code><pre>
use App\Libraries\Mailer;

$mailer = new Mailer();

$mailer->sendWelcome(
    'rahul@example.com',
    'Rahul'
);
</pre></code>

- Cleaner reusable architecture. ✅

## 12. Most Important Methods

| Method             | Use                   |
| ------------------ | --------------------- |
| `service('email')` | Email object          |
| `setFrom()`        | Sender                |
| `setTo()`          | Receiver              |
| `setReplyTo()`     | Reply address         |
| `setSubject()`     | Subject               |
| `setMessage()`     | Body                  |
| `setCC()`          | CC                    |
| `setBCC()`         | BCC                   |
| `attach()`         | Attachment            |
| `send()`           | Send email            |
| `printDebugger()`  | Development debugging |
