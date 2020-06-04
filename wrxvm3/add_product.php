<?php
require_once('../wrxvm3_priv/database.php');

// Get the product data from post
$productname = filter_input(INPUT_POST, 'productname');
$productnumber = filter_input(INPUT_POST, 'productnumber');
$description = filter_input(INPUT_POST, 'description');
$vendorid = filter_input(INPUT_POST, 'vendorid', FILTER_VALIDATE_INT);
$fitsvehicle = filter_input(INPUT_POST, 'fitsvehicle');
$instock = filter_input(INPUT_POST, 'instock', FILTER_VALIDATE_INT);
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$productcategory = filter_input(INPUT_POST, 'productcategory');

//upload config
$target_dir = "images/";
$target_file_for_image = basename($_FILES['imagefileforcar']['name']);
$imageFileType = strtolower(pathinfo($target_file_for_image,PATHINFO_EXTENSION)); //this converts the filetype to lowercase

// Check if file already exists in the images folder
if (file_exists("$target_dir/$target_file_for_image")) {
    echo "(Error) File already exists or no image selected.<br>";
    echo "<a href=\"sales_dashboard.php\">Back to Sales Dashboard</a>";
    exit;
}

// Check file size not greater than 5000KB (~5MB)
if ($_FILES["imagefileforcar"]["size"] > 5000000) {
    echo "(Error) File is too large.<br>";
    echo "<a href=\"sales_dashboard.php\">Back to Sales Dashboard</a>";
    exit;
}

// Allow only certain image file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "(Error) Only JPG, JPEG, PNG & GIF files are allowed.<br>";
    echo "<a href=\"sales_dashboard.php\">Back to Sales Dashboard</a>";
    exit;
}

// query the db to check if product number is already in database table
$checkproductnumber = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE ProductNumber='$productnumber'");
$checkproduct = $checkproductnumber->fetchAll();

foreach ($checkproduct as $returnedproducts) {

    // check if product number already exists on database table
    if ($returnedproducts['ProductNumber'] == $productnumber) {
        echo "(Error) That Product Number is already on the database.<br>";
        echo "<a href=\"sales_dashboard.php\">Back to Sales Dashboard</a>";
        exit;
    }
}

// Validate inputs make sure not null/empty
if (empty($productname) || empty($productnumber) || empty($vendorid) || empty($fitsvehicle) || empty($instock) || empty($price) || empty($productcategory)) {
    echo "(Error) Some fields cannot be left empty. Check all fields and try again.<br>";
    echo "<a href=\"sales_dashboard.php\">Back to Sales Dashboard</a>";
    exit;
} else {

    //move the image file into target dir
    move_uploaded_file($_FILES["imagefileforcar"]["tmp_name"], "$target_dir/$target_file_for_image");
    
    // add the product to database
    $query = 'INSERT INTO almontee3_PRODUCTS (ProductNumber, ProductName, FitsVehicle, ImageLocation, InStock, Description, VendorID, UnitPrice, Category) VALUES (:productnumber, :productname, :fitsvehicle, :target_file_for_image, :instock, :description, :vendorid, :price, :productcategory)';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':productnumber', $productnumber);
    $statement->bindValue(':productname', $productname);
    $statement->bindValue(':fitsvehicle', $fitsvehicle);
    $statement->bindValue(':target_file_for_image', "images/$target_file_for_image");
    $statement->bindValue(':instock', $instock);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':vendorid', $vendorid);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':productcategory', $productcategory);
    $statement->execute();
    $statement->closeCursor();

    echo "<script>alert('Product added successfully!'); window.location.href='sales_dashboard.php#add_a_product';</script>";
}

?>