# Chapter 16 — Database Migrations & Seeders

- **Goal:** Database tables ko manually phpMyAdmin me create karne ke bajay code se create aur manage karna.

## Migration Kya Hai?
- Database structure ko code ke through manage karna.

Professional projects me:

<code><pre>
Migration File
↓
php spark migrate
↓
Database Table Automatically Create
</pre></code>

## Seeder Kya Hai?

- Database me sample/default data insert karna.

**Example**

<code><pre>
Categories
↓
Electronics
Fashion
Books
Sports
</pre></code>

- Ye manually insert karne ki jagah Seeder automatically insert karega.

## Migration + Seeder Flow

<code><pre>
Migration
↓
Create Table
↓
Seeder
↓
Insert Sample Data
↓
Application Ready
</pre></code>

## 1. Migration Create Karna

Command:

`php spark make:migration CreateProductsTable`

**Output:**

<code><pre>
app/
Database/
Migrations/
20260803123456_CreateProductsTable.php
</pre></code>

- Filename ke beginning me timestamp hota hai.

## 2. Migration File Structure

<code><pre>
&lt;?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {

    }

    public function down()
    {

    }
}
</pre></code>

Important Methods:

<code><pre>
up()
↓
Table Create

`----------------`

down()
↓
Table Delete
</pre></code>

## 3. Table Create ⭐

<code><pre>
public function up()
{
    $this->forge->addField([

        'id' => [

            'type'           => 'INT',

            'constraint'     => 11,

            'unsigned'       => true,

            'auto_increment' => true

        ],

        'name' => [

            'type'       => 'VARCHAR',

            'constraint' => 100

        ],

        'price' => [

            'type'       => 'DECIMAL',

            'constraint' => '10,2'

        ],

        'created_at' => [

            'type' => 'DATETIME',

            'null' => true

        ],

        'updated_at' => [

            'type' => 'DATETIME',

            'null' => true

        ]

    ]);

    $this->forge->addKey('id', true);

    $this->forge->createTable('products');
}

</pre></code>

## 5. Rollback Method

<code><pre>
public function down()
{
    $this->forge->dropTable('products');
}

</pre></code>

Meaning

<code><pre>
Rollback

↓

Delete products table
</pre></code>

## 6. Migration Run ⭐

Command

`php spark migrate`

Output

`Products Table Created`

Database

<code><pre>
products
↓
Auto Create
</pre></code>

## 7. Rollback

Undo

`php spark migrate:rollback`

Result

<code><pre>
products
↓
Deleted
</pre></code>

## 8. Refresh

<code><pre>
Delete
↓
Create Again
</pre></code>

`php spark migrate:refresh`

- Useful Development.

## 9. Fresh Migration

<code><pre>
Delete All Tables
↓
Create Again
</pre></code>

`php spark migrate:fresh`

- Use carefully. Ye saari tables delete kar deta hai.

## 10. Seeder Create ⭐

Command

`php spark make:seeder ProductSeeder`

File

<code><pre>
app/
Database/
Seeds/
ProductSeeder.php
</pre></code>

## 11. Seeder Structure

<code><pre>
&lt;?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {

    }
}
</pre></code>

## 12. Insert Data ⭐

<code><pre>
public function run()
{
    $data = [

        [

            'name' => 'Laptop',

            'price' => 50000

        ],

        [

            'name' => 'Mobile',

            'price' => 20000

        ],

        [

            'name' => 'Keyboard',

            'price' => 1500

        ]

    ];

    $this->db

    ->table('products')

    ->insertBatch($data);
}

</pre></code>

**Output**

<code><pre>
Laptop
Mobile
Keyboard
</pre></code>

- Automatically insert.

## 13. Seeder Run

Command

`php spark db:seed ProductSeeder`

Database

<code><pre>
products
↓
3 Records
</pre></code>

## 14. Single Insert

<code><pre>
$this->db ->table('products') 
->insert([
'name'=>'Mouse',
'price'=>800
]);

</pre></code>

## 15. Faker ⭐
- Professional Projects Random Data

`$faker = \Faker\Factory::create();`

Loop

<cdoe><pre>
for($i=1;$i<=50;$i++)
{
$this->db

->table('products')

->insert([

'name'=>$faker->word(),

'price'=>$faker->numberBetween(100,50000)

]);
}
</pre></code>

Output `50 Random Products`

Useful

- Testing, Pagination Search Performance

## 16. Multiple Seeders

<code><pre>
CategorySeeder
ProductSeeder
UserSeeder
RoleSeeder
</pre></code>

- Run individually.

## 17. DatabaseSeeder ⭐

Main Seeder

<code><pre>
public function run()
{
    $this->call( ProductSeeder::class );
    $this->call( CategorySeeder::class );
    $this->call( UserSeeder::class );
}

</pre></code>

Command

`php spark db:seed DatabaseSeeder`

<code><pre>
Everything
↓
Inserted.
</pre></code>

## 18. Migration vs Seeder

Migration

<code><pre>
Database Structure
↓
Tables
Columns
Indexes
</pre></code>

Seeder

<code><pre>
Database Data
↓
Products
Users
Categories
</pre></code>

## 19. Professional Workflow ⭐

<code><pre>
Developer
↓
Create Migration
↓
Git Commit
↓
Other Developer
↓
Git Pull
↓
php spark migrate
↓
Database Ready
</pre></code>

- No phpMyAdmin work.

## 20. Important Spark Commands ⭐

Create Migration

`php spark make:migration CreateProductsTable`

Run

`php spark migrate`

Rollback

`php spark migrate:rollback`

Refresh

`php spark migrate:refresh`

Fresh

`php spark migrate:fresh`

Seeder

`php spark make:seeder ProductSeeder`

Run Seeder

`php spark db:seed ProductSeeder`

## 21. Migration Folder

<code><pre>
app/
Database/
├── Migrations/
│
└── Seeds/
</pre></code>


## Interview Questions 

**Migration kya hai?**

- Database structure ko code se manage karna.

**Seeder kya hai?**

- Sample/default data insert karna.

**Migration Command**

- `php spark migrate`

**Rollback**

- `php spark migrate:rollback`

**Seeder**
- `php spark db:seed ProductSeeder`

**Faker**
- Random testing data generate karne ke liye.