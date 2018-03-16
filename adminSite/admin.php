<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <h1>All Products</h1><br>

    <?php
    /**
     * Created by PhpStorm.
     * User: Ronak
     * Date: 12-03-2018
     * Time: 12:28
     */
    require_once '../LIB_project1.php';

    authenticateAdmin();

    /*function moveImageToUploads(){
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
    }*/


    /*if(isset($_POST["task"])){

        if($_POST["task"]=="addRecord"){
            $fileName = moveImageToUploads();
            if(!empty($fileName)){
                $result = addProduct($_POST["name"], $_POST["description"], $_POST["price"], $_POST["quantity"], $fileName, $_POST["salePrice"]);
                if ($result) {
                    echo '<div class="alert alert-success" role="alert">Product was successfully added!</div>';
                }
            }else{
                echo '<div class="alert alert-danger" role="alert">Invalid image uploaded! Could not add the product!</div>';
            }

        } else if($_POST["task"]=="updateRecord"){
            $id = $_POST["id"];
            $fileName = moveImageToUploads();
            if(empty($fileName)){
                $fileName = $_POST["existingImage"];
            }
            $result = updateProduct($id,$_POST["name"], $_POST["description"], $_POST["price"], $_POST["quantity"],$_POST["salePrice"], $fileName);
            if ($result) {
                echo '<div class="alert alert-success" role="alert">Product was successfully updated!</div>';
            }
        }
    }*/

    //fetching results of delete product action
    if(isset($_GET["success"])){
        $successVal = $_GET["success"]=="true"?true:false;
        $alertClass =$successVal?"alert-success":"alert-danger";
        echo '<div class="alert '.$alertClass. '" role="alert">'.$_GET["message"].'</div>';
    }

    echo "<div class='row'>";
    $page = isset($_GET["page"])?$_GET["page"]:1;
    displayProducts($page);

    echo "</div>";
    ?>
        <div class="row">
            <a href="addupdateproduct.php?insert=" class="btn btn-success">Add Product</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
