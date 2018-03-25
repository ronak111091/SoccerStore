<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 12-03-2018
 * Time: 12:29
 */

    require_once 'classes/Db.php';

    $db = new Db();

/**
 * function to authenticate admin
 */
    function authenticateAdmin(){
        //when user cancels authentication
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo "Authentication failed!";
            exit;
            //when user enters invalid credentials
        } else if ($_SERVER["PHP_AUTH_USER"]!=="admin" || $_SERVER["PHP_AUTH_PW"]!=="admin") {
            echo "Invalid Credentials! Authentication failed!";
            exit;
        }
    }

/**
 * function to render header section in page
 */
    function renderHeader(){
        $header = <<<EOT
<header>
<div class="container">
<h1>Soccer Store</h1>
</div>
</header>
EOT;
    echo $header;
    }

/**
 * function to render footer section in page
 */
    function renderFooter(){
        $footer = <<<EOT
<div class="container">
<footer>
&copy; 2018 All rights reserved.
</footer>
</div>
EOT;
echo $footer;
    }

/**
 * @param int $activeTab
 * funtion to render navigation tabs to the user
 */
    function renderPublicSiteTabs($activeTab=1){
        $homeVal = $activeTab==1?"active":"";
        $cartVal = $activeTab==1?"":"active";
        $header=<<<EOT
<ul class="nav nav-tabs">
<li class="nav-item">
<a class="nav-link {$homeVal}" href="productList.php">Home</a>
</li>
<li class="nav-item">
<a class="nav-link {$cartVal}" href="cartList.php">Cart</a>
</li>
</ul><br>
EOT;
    echo $header;
    }

/**
 * @param int $page
 * @param string $query to search for a particular product.
 * function to list all the available products in the application to the admin
 */
    function displayProducts($page=1,$query=""){
        global $db;
        $rows = $db->getProductCount();
        $limit = 10;
        $totalPages = ceil($rows / $limit);
        $offset = $limit*($page-1);
        $previous = $page-1;
        $next = $page+1;
        $previousHidden = $previous==0?"hidden":"";
        $nextHidden = $next>$totalPages?"hidden":"";
        $products = $db->getAllProducts($limit,$offset,$query);
        $dialog = "return confirm('Do you want to delete this product')";
        $output = '<table class="table"><thead><tr><th scope="col">#</th><th scope="col">Name</th><th scope="col">Price</th><th scope="col">Quantity</th><th scope="col"></th></tr></thead>';
        $output=$output.'<tbody>';
        foreach ($products as $product){
            $name = $product->getName();
            $id = $product->getId();
            $quantity = $product->getQuantity();
            $price = $product->getPrice();
            $output=$output.'<tr>';
            $output=$output.'<th scope="row">'.$id.'</th>';
            $output=$output.'<th scope="row">'.$name.'</th>';
            $output=$output.'<th scope="row">'.$price.'$</th>';
            $output=$output.'<th scope="row">'.$quantity.'</th>';
            $output=$output.'<th scope="row"><a class="btn btn-info" href="addupdateproduct.php?update=&id='.$id
                .'" role="button">Update</a>&nbsp;<a class="btn btn-danger" href="deleteProduct.php?task=deleteRecord&id=' .$id
                .'" role="button" onclick="'.$dialog.'" >Delete</a></th>';
            $output=$output.'</tr>';
        }
        $output=$output.'</tbody></table>';
        $pagination= <<<EOT
<nav>
<ul class="pagination">
<li class="page-item"><a class="page-link" href="admin.php?page={$previous}" {$previousHidden}>Previous</a></li>
<li class="page-item"><span class="page-link">{$page} of {$totalPages}</span></li>
<li class="page-item"><a class="page-link" href="admin.php?page={$next}" {$nextHidden}>Next</a></li>
</ul>
</nav>
EOT;
        $output=$output.$pagination;
        echo $output;
    }

/**
 * @return null|string
 * function to move the image from tmp folder to the uploads folder on the server during image upload
 */
    function moveImageToUploads(){
        $newFileName = null;
        $extTypes = array("jpeg","jpg","png");
        if(file_exists($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])){
            $file = $_FILES["image"];
            $fileName = $file["name"];
            $fileTmpName = $file["tmp_name"];
            $fileSize = $file["size"];
            $fileExtArr = explode(".",$fileName);
            $fileExt = strtolower(end($fileExtArr));
            if(in_array($fileExt,$extTypes)){
                if($fileSize<1000000){
                    $newFileName = uniqid().".".$fileExt;
                    $filePath = "../uploads/".$newFileName;
                    move_uploaded_file($fileTmpName,$filePath);
                }
            }
        }
        return $newFileName;
    }

/**
 * simple functions that generates alerts based on the success and message params in get request.
 */
    function generateMessage(){
        if (isset($_GET["success"])) {
            $successVal = $_GET["success"] == "true" ? true : false;
            $alertClass = $successVal ? "alert-success" : "alert-danger";
            echo '<div class="alert ' . $alertClass . '" role="alert">' . $_GET["message"] . '</div>';
        }
    }

/**
 * @param bool $isInsert
 * @param int $id
 * @param string $name
 * @param string $description
 * @param int $price
 * @param int $quantity
 * @param int $salePrice
 * @param null $image
 * function to render the insert/update form to the admin to add or update products.
 */
    function renderInsertUpdateProductForm($isInsert=true,$id=0, $name="", $description="", $price=0, $quantity=0, $salePrice=0, $image=null){
        $hiddenTaskValue = $isInsert?'addRecord':'updateRecord';
        $submitButtonValue = $isInsert?'Add':'Update';
        $resetButtonHidden = $isInsert?'':'hidden';
        $imageRequired = $isInsert?"required":"";
        $imageSrc = "../images/placeholder.png";
        if(!empty($image)){
            $imageSrc = "../uploads/".$image;
        }
        $htmlOutput= <<<EOT
<div class="row">
<div class="col">
<form method="post" action="addupdateproduct.php" enctype="multipart/form-data">
<div class="form-group">
<label for="nameField">Name</label>
<input type="text" id="nameField" name="name" value="{$name}" class="form-control" required>
</div>
<div class="form-group">
<label for="">Description</label>
<textarea id="descriptionField" name="description" class="form-control" class="form-control" required>{$description}</textarea>
</div>
<div class="form-group">
<label for="priceField">Price</label>
<input type="number" name="price" id="priceField" value="{$price}" class="form-control" required step="0.01">  
</div>
<div class="form-group">
<label for="quantityField">Quantity</label>
<input type="number" name="quantity" value="{$quantity}" id="quantityField" class="form-control">
</div>
<div class="form-group">
<label for="salePriceField">Sale Price</label>
<input type="number" name="salePrice" value="{$salePrice}" id="salePriceField" class="form-control" step="0.01">
</div>
<div class="form-group">
<img src="{$imageSrc}" id="imageSrcField" class="img-thumbnail add-product-image">
<input type="file" name="image" id="imageField" class="form-control" {$imageRequired} accept="image/*" onchange="readURL(this);">
</div>
<input type="hidden" name="existingImage" value="{$image}">
<input type="hidden" name="task" value="{$hiddenTaskValue}">
<input type="hidden" name="id" value="{$id}">
<input type="submit" name="submit" value="{$submitButtonValue}" class="btn btn-primary">&nbsp;
<a href="addupdateproduct.php?insert=" class="btn btn-secondary" {$resetButtonHidden}>Reset</a>
<a href="admin.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
<div class="col">

</div>
</div>
EOT;

        echo $htmlOutput;
    }

/**
 * @param $name
 * @param $description
 * @param $price
 * @param int $quantity
 * @param null $image
 * @param int $sale_price
 * @return int|string
 * function to add products in database
 */
    function addProduct($name, $description, $price, $quantity=0, $image=null,$sale_price=0){
        global $db;
        //add constraints to check minimum and max sale requirements
        if(checkNoOfItemsOnSale(0,true)){
            $result = $db->insertProduct($name, $description, $price, $quantity, $image,$sale_price);
            return $result;
        }else{
            return -1;
        }

    }

/**
 * @param $id
 * @param $name
 * @param $description
 * @param $price
 * @param int $quantity
 * @param int $sale_price
 * @param null $image
 * @return int
 * function to update products in database
 */
    function updateProduct($id,$name, $description, $price, $quantity=0,$sale_price=0,$image=null){
        global  $db;
        //add constraints to check minimum and max sale requirements
        $oldProduct = $db->getProductById($id);
        $oldProductSalePrice = $oldProduct->getSalePrice();
        $saleConstraint=true;
        if($oldProductSalePrice!=$sale_price){
            if($oldProductSalePrice==0){
                $saleConstraint = checkNoOfItemsOnSale(0,true);
            }else if($sale_price==0){
                $saleConstraint = checkNoOfItemsOnSale($id,false);
            }
        }
        if($saleConstraint){
            $result = $db->updateProduct($id,$name,$description,$price,$quantity,$sale_price,$image);
            return $result;
        }else{
            return -1;
        }

    }

/**
 * @param $id
 * @param bool $add
 * @return bool
 * function to validate that there are always minimum of 3 and a maximum of 5 products on sale
 */
    function checkNoOfItemsOnSale($id,$add=true){
        global $db;
        $noOfSaleItems = $db->getSaleProductCount($id);
        $result = true;
        if($add){
            if($noOfSaleItems>=5){
                $result=false;
            }
        }else{
            if($noOfSaleItems<3){
                $result=false;
            }
        }
        return $result;
    }

/**
 * @param $id
 * @return int
 * function to delete products from the table
 */
    function deleteProduct($id){
        global  $db;
        $result = $db->deleteProduct(array($id));
        return $result;
    }

/**
 * @param $id
 * wrapper function to render the update form to the admin which in turn calls the renderInsertUpdateProductForm
 */
    function renderUpdateProductForm($id){
        global  $db;
        $p = $db->getProductById($id);
        if(!empty($p)){
            renderInsertUpdateProductForm(false,$id,$p->getName(),$p->getDescription(),$p->getPrice(),
                $p->getQuantity(),$p->getSalePrice(),$p->getImage());
        }
    }

/**
 * @param int $page
 * function to display all catalog products to the user along with pagination.
 */
    function displayCatalogProductsForUser($page=1){
            //using pagination
        global $db;
        $rows = $db->getCatalogProductCount();
        $limit=6;
        $totalPages = ceil($rows / $limit);
        $offset = $limit*($page-1);
        $previous = $page-1;
        $next = $page+1;
        $previousHidden = $previous==0?"hidden":"";
        $nextHidden = $next>$totalPages?"hidden":"";

        $products = $db->getCatalogProducts($limit,$offset);
        $label="Catalog";
        $catalogHtml=<<<EOT
<div class="card">
<h5 class="card-header">${label}</h5>
<div class="card-body">
EOT;
    $productListHtml="";
    $rowEle = 1;
    foreach ($products as $catalogProduct){
        if($rowEle==1 || $rowEle==4){
            $productListHtml=$productListHtml.'<div class="row">';
        }
        $productListHtml=$productListHtml.'<div class="col-4">';
        $name = $catalogProduct->getName();
        $id = $catalogProduct->getId();
        $description = $catalogProduct->getDescription();
        $price = '$'.$catalogProduct->getPrice();
        $quantity= $catalogProduct->getQuantity();
        $image=$catalogProduct->getImage();
        if(empty($image)){
            $image="../images/placeholder.png";
        }
        $productListHtml=$productListHtml.<<<EOT
<div class="card" style="width: 18rem">
<a class="image-link" data-id="{$id}" href="#"><img src="../uploads/{$image}" class="card-img-top" alt="Product Image"></a>
<div class="card-body">
<h5 class="card-title">{$name}</h5>
<h5 class="card-title">{$price}</h5>
<p class="card-text">Only {$quantity} left!</p>
<a href="productList.php?addToCart=${id}" onclick="return confirm('Are you sure?')" class="btn btn-primary">Add to cart</a>
</div>
</div>
</div>
EOT;
        if($rowEle==3)
            $productListHtml=$productListHtml.'</div><br>';
        $rowEle++;
    }
    if($rowEle!=4){
        $productListHtml=$productListHtml.'</div><br>';
    }

    $catalogHtml=$catalogHtml.$productListHtml;
    $catalogHtml=$catalogHtml.generatePaginationHTML($page,$totalPages,$previous,$next,$previousHidden,$nextHidden);

    $catalogHtml=$catalogHtml.'</div></div>';
    echo $catalogHtml;
}

/**
 * function to display all products on sale.
 */
function displayOnSaleProductsForUser(){
    global $db;
    $products = $db->getSaleProducts();
    $label="On Sale";
    $saleHtml=<<<EOT
<div class="card">
<h5 class="card-header">${label}</h5>
<div class="card-body">
EOT;
    $productListHtml="";
    $rowEle = 1;
    foreach ($products as $catalogProduct){
        if($rowEle==1 || $rowEle==4){
            $productListHtml=$productListHtml.'<div class="row">';
        }
        $productListHtml=$productListHtml.'<div class="col-4">';
        $id = $catalogProduct->getId();
        $name = $catalogProduct->getName();
        $description = $catalogProduct->getDescription();
        $price = '$'.$catalogProduct->getPrice();
        $salePrice = '$'.$catalogProduct->getSalePrice();
        $quantity= $catalogProduct->getQuantity();
        $image=$catalogProduct->getImage();
        if(empty($image)){
            $image="../images/placeholder.png";
        }
        $productListHtml=$productListHtml.<<<EOT
<div class="card" style="width: 18rem">
<a class="image-link" data-id="{$id}" href="#"><img src="../uploads/{$image}" class="card-img-top" alt="Product Image"></a>
<div class="card-body">
<h5 class="card-title">{$name}</h5>
<h5 class="card-text text-linethrough">{$price}</h5>
<h5 class="card-title text-green">{$salePrice}</h5>
<p class="card-text">Only {$quantity} left!</p>
<a href="productList.php?addToCart=${id}" onclick="return confirm('Are you sure?')" class="btn btn-primary">Add to cart</a>
</div>
</div>
</div>
EOT;
        if($rowEle==3)
            $productListHtml=$productListHtml.'</div><br>';
        $rowEle++;
    }
    if($rowEle!=4){
        $productListHtml=$productListHtml.'</div><br>';
    }
    $saleHtml=$saleHtml.$productListHtml;
    $saleHtml=$saleHtml.'</div></div>';
    echo $saleHtml;
}

/**
 * @param $page
 * @param $totalPages
 * @param $previous
 * @param $next
 * @param $previousHidden
 * @param $nextHidden
 * @return string
 * function to generate pagination for a table
 */
    function generatePaginationHTML($page,$totalPages,$previous,$next,$previousHidden,$nextHidden){
        $pagination= <<<EOT
<nav>
<ul class="pagination">
<li class="page-item"><a class="page-link" href="productList.php?page={$previous}" {$previousHidden}>Previous</a></li>
<li class="page-item"><span class="page-link">{$page} of {$totalPages}</span></li>
<li class="page-item"><a class="page-link" href="productList.php?page={$next}" {$nextHidden}>Next</a></li>
</ul>
</nav>
EOT;
        return $pagination;
    }

/**
 * @param $id
 * @return int
 * wrapper function that decrement the quantity of a product.
 */
    function decrementProductQuantity($id){
        global $db;
        return $db->updateProductQuantity($id,false);
    }

/**
 * @param $id
 * @param $amt
 * @return int
 * wrapper function that increments the quantity of a product based on the amt passed.
 */
    function incrementProductQuantity($id,$amt){
        global $db;
        return $db->updateProductQuantity($id,true,$amt);
    }

    function getProductById($id){
        global $db;
        return $db->getProductById($id);
    }

    function sanitizeString($var){
        $var = trim($var);
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return $var;
    }

    function numeric($value) {
        $reg = "/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/";
        return preg_match($reg,$value);
    }

    function numbers($value) {
        $reg = "/^[0-9.]+$/";
        return preg_match($reg,$value);
    }

    function validateAddUpdateFormInputValues($name,$description,$quantity,$price,$salePrice){
        $name = sanitizeString($name);
        $description = sanitizeString($description);
        $price = sanitizeString($price);
        $quantity = sanitizeString($quantity);

        $errorMsg="";
        if(empty($name)){
            $errorMsg=$errorMsg."Please enter a valid value for name!<br>";
        }

        if(empty($description)){
            $errorMsg=$errorMsg."Please enter a valid value for Description!<br>";
        }

        if($price=="" || !numbers($price) || $price<=0){
            $errorMsg=$errorMsg."Please enter a valid value for Price!<br>";
        }

        if($quantity=="" || !numbers($quantity)){
            $errorMsg=$errorMsg."Please enter a valid value for Quantity!<br>";
        }
        return $errorMsg;
    }