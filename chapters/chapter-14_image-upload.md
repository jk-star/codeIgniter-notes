# Chapter 14 — File & Image Upload

- **Goal:** User se file ya image upload karna, validate karna, server me save karna aur database me filename store karna.

## 1. File Upload Flow

<code><pre>
User
   │
Choose Image
   │
Submit Form
   │
Controller
   │
Validation
   │
Move File
   │
public/uploads/
   │
Filename Database
   │
View Image
</pre></code>

## 2. Folder Structure
Create folder:
<code><pre>
public/

├── uploads/
│
├── index.php
</pre></code>

Final:

<code><pre>
public/
uploads/
    products/
    profile/
    category/
</pre></code>

- Normally images public/uploads/ me rakhte hain.

## 3. Database Table

products table

<code><pre>
id
name
price
image
</pre></code>

## 4. Complete Upload Example

<code><pre>
public function store()
{
    $image = $this->request->getFile('image');

    $imageName = null;

    if ($image->isValid())
    {
        $imageName = $image->getRandomName();

        $image->move(
            'uploads',
            $imageName
        );
    }

    $this->productModel->insert([

        'name' =>

        $this->request->getPost('name'),

        'price' =>

        $this->request->getPost('price'),

        'image' =>

        $imageName

    ]);

    return redirect()

    ->to('/products');
}
</pre></code>

Flow:

<code><pre>
Image Select
↓
getFile()
↓
Random Name
↓
move()
↓
Database
↓
Redirect
</pre></code>

## 5. Image Display Karna ⭐
<code><pre>
&lt;img src="/uploads/&lt;?= esc($product['image']) ?&gt;" width="150" &gt;
</pre></code>

## 6. Validation ⭐
<code><pre>
$rules = [ 'image' => [ 'rules' =>

    'uploaded[image]

    |max_size[image,2048]

    |is_image[image]

    |mime_in[image,image/jpg,image/jpeg,image/png]'

    ]

];
</pre></code>

## 7. Validation Example

<code><pre>
if (! $this->validate($rules))
{
return redirect()
->back()
->withInput();
}
</pre></code>

## 15. Delete Old Image ⭐
<code><pre>
if( file_exists( 'uploads/' . $product['image'] ) )
{ 
    unlink( 'uploads/' . $product['image'] );
}
</pre></code>

## 16. Update Controller
<code><pre>
$image = $this->request->getFile('image');

if( $image->isValid() ) {

$imageName = $image->getRandomName();
$image->move( 'uploads', $imageName );

}

**Then**

$this->productModel->update( $id, [ 'image'=>$imageName ] );
</pre></code>

## 17. Delete Product Image ⭐

<code><pre>
Delete Product
↓
Database Delete
↓
Image bhi delete
</pre></code>

<code><pre>
if( file_exists( 'uploads/' . $product['image'] ) )
{
unlink( 'uploads/' . $product['image'] );
}

**Then**

$this->productModel ->delete($id);
</pre></code>

## 18. Multiple Images
Input
<code><pre>
&lt;input type="file" name="images[]" multiple &gt;
</pre></code>

Controller:
<code><pre>
$images = $this->request ->getFiles();
</pre></code>

- Ye Gallery projects me use hota hai.

## 19. Common File Methods ⭐
File
<code><pre>
$image = $this->request ->getFile('image');

`Valid`
$image->isValid();

`Move`
$image->move( 'uploads' );

`Random Name`
$image->getRandomName();

`Original Name`
$image->getName();

`Extension`
$image->getExtension();

`Size`
$image->getSize();

`Mime`

`$image->getMimeType();`

</pre></code>

## 20. Complete Flow

<code><pre>
Choose Image
↓
Validation
↓
Random Name
↓
Move Upload Folder
↓
Filename Database
↓
Display Image
</pre></code>

## Interview Questions

**Upload file**

`$this->request ->getFile('image');`

**Move**

`$image ->move( 'uploads' );`

**Random Name**

``$image ->getRandomName();``

**Validation**
<code><pre>
uploaded
is_image
mime_in
max_size
</pre></code>

**Display**

`<img src="/uploads/ <?= $product['image'] ?>" >`

**Delete**

`unlink( 'uploads/'.$image );`
