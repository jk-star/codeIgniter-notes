# Chapter 3 — MVC Architecture in CodeIgniter 4

## 1. MVC kya hai?

| Part           | Kaam                                       |
| -------------- | ------------------------------------------ |
| **Model**      | Database se data lena/save karna           |
| **View**       | HTML/UI dikhana                            |
| **Controller** | Request aur application logic handle karna |

**Flow:**

<code><pre>
User Browser
     ↓
   Route
     ↓
 Controller
     ↓
   Model
     ↓
 Database
     ↓
 Controller
     ↓
    View
     ↓
 Browser
</pre></code>

**Example:** User `/products` open karta hai.

<code><pre>
/products
    ↓
Product Controller
    ↓
Product Model
    ↓
products table
    ↓
Product Controller
    ↓
products View
    ↓
Product List
</pre></code>

## 2. Controller Banate Hain

File create karein:

`app/Controllers/Student.php`

Code:


<code><pre>
<?php

namespace App\Controllers;

class Student extends BaseController
{
    public function index()
    {
        return "Hello Student";
    }
}
</pre></code>

**Code samjho**

`namespace App\Controllers;`

- Batata hai ki class `App\Controllers` namespace ka part hai.

`class Student extends BaseController`

- Humne `Student` controller banaya jo CI4 ke application `BaseController` ko extend kar raha hai.

`public function index()`

- Ye controller ka method hai.

## 3. Route Banayein

- Ab browser ko kaise pata chalega `/student` par kaunsa controller chalana hai?

**File open:**

`app/Config/Routes.php`

**Add:**

`$routes->get('/student', 'Student::index');`

**Meaning:**

<code><pre>
/student
    ↓
Student Controller
    ↓
index()
</pre></code>

**Ab server:**

`php spark serve`

**Browser:**

`http://localhost:8080/student`

**Output:**

`Hello Student`

## 4. Ab View Banate Hain

**File:**

`app/Views/student.php`

**Code:**

<code><pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title>Students&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Student List&lt;/h1&gt;
    &lt;p&gt;Welcome to CodeIgniter 4&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre></code>

**Controller mein :**

<code><pre>
public function index()
{
    return view('student');
}
</pre></code>

**Ab:**

<code><pre>
/student
   ↓
Student::index()
   ↓
view('student')
   ↓
app/Views/student.php
   ↓
Browser
</pre></code>

**Refresh:**

`http://localhost:8080/student`

## 5. Controller se View me Data Send Karna ⭐

**Controller:**

<code><pre>
public function index()
{
    $data = [
        'name' => 'Rahul',
        'course' => 'MCA'
    ];

    return view('student', $data);
}
</pre></code>

**View:**
`app/Views/student.php`

<code><pre>
&lt;h1&gt;Student Details&lt;/h1&gt;

&lt;p&gt;Name: <?= esc($name) ?>&lt;/p&gt;

&lt;p&gt;Course: <?= esc($course) ?>&lt;/p&gt;
</pre></code>

## `esc()` kya hai?

`<?= esc($name) ?>`

- Dynamic output ko safely HTML me display karne ke liye CI4 ka `esc()` function use kiya jata hai.

## 6. Array Data View me Send Karna

**Controller:**

<code><pre>
public function index()
{
    $data['students'] = [
        ['name' => 'Amit', 'course' => 'BCA'],
        ['name' => 'Rahul', 'course' => 'MCA'],
        ['name' => 'Neha', 'course' => 'BCom']
    ];

    return view('student', $data);
}
</pre></code>

**View:**

<code><pre>
&lt;h1&gt;Student List&lt;/h1&gt;

&lt;?php foreach ($students as $student): ?&gt;
    &lt;p&gt;
        &lt;?= esc($student['name']) ?&gt;
        -
        &lt;?= esc($student['course']) ?&gt;
    &lt;/p&gt;

&lt;?php endforeach; ?&gt;
</pre></code>

## 7. Model kahan aayega?

`app/Models/StudentModel.php`

**Model ka kaam hoga:**

<code><pre>
StudentModel
     ↓
students table
     ↓
Student data
</pre></code>

**Controller Model se data lega:**

<code><pre>
Student Controller
       ↓
Student Model
       ↓
Database
</pre></code>

**Aur phir View ko dega:**
<code><pre>
Database
   ↓
Model
   ↓
Controller
   ↓
View
</pre></code>

## 🧠 Interview Questions

**Q1. MVC ka full form?**
- Model View Controller

**Q2. Model ka kaam?**
- Database/data related operations.

**Q3. View ka kaam?**
- User interface/display.

**Q4. Controller ka kaam?**
- Request handle karna aur Model/View ke beech flow manage karna.

**Q5. Controller se View kaise load karte hain?**
`return view('student');`

**Q6. Controller se View me data?**

<code><pre>
$data['name'] = 'Rahul';

return view('student', $data);
</pre></code>

**View:**

`<?= esc($name) ?>`