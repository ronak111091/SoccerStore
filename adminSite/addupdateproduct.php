<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/soccerStore.css">
</head>
<body>
<div class="container">
<?php

    require_once '../LIB_project1.php';
    authenticateAdmin();

    if(isset($_POST["task"])){

        if($_POST["task"]=="addRecord"){
            $fileName = moveImageToUploads();
            $success="false";
            if(!empty($fileName)){
                $result = addProduct($_POST["name"], $_POST["description"], $_POST["price"], $_POST["quantity"], $fileName, $_POST["salePrice"]);
                if ($result) {
                    $message = "Product was successfully added!";
                    $success="true";
                    header("Location: admin.php?success={$success}&message={$message}");
                }else{
                    $message = "An error occurred while adding the product!";
                    header("Location: admin.php?success={$success}&message={$message}");
                }
            }else{
                $message = "Invalid image uploaded!";
                header("Location: addupdateproduct.php?insert=&success={$success}&message={$message}");
            }
        } else if($_POST["task"]=="updateRecord"){
            $id = $_POST["id"];
            $fileName = moveImageToUploads();
            if(empty($fileName)){
                $fileName = $_POST["existingImage"];
            }
            $result = updateProduct($id,$_POST["name"], $_POST["description"], $_POST["price"], $_POST["quantity"],$_POST["salePrice"], $fileName);
            if ($result) {
                $message = "Product was successfully updated!";
                $success="true";
                header("Location: admin.php?success={$success}&message={$message}");
            }else{
                $message = "An error occurred while adding the product!";
                header("Location: admin.php?success={$success}&message={$message}");
            }
        }
    }



generateMessage();


if(isset($_GET["insert"])){
        echo "<h1>Add Product</h1><br>";
        renderInsertUpdateProductForm();
    }else if(isset($_GET["update"])){
        $id = $_GET["id"];
        echo "<h1>Update Product</h1><br>";
        renderUpdateProductForm($id);
    }

?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imageSrcField').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
