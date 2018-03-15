<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <?php
    require_once '../LIB_project1.php';
    session_start();
    if(!isset($_SESSION["cart"])){
        $cart = array();
    }else{
        $cart = $_SESSION["cart"];
    }
    if (isset($_GET["addToCart"])){
        $productId = $_GET["addToCart"];
        $result=decrementProductQuantity($productId);
        if($result){
            if(!array_key_exists($productId,$cart)){
                $cart[$productId]=1;
            }else{
                $cart[$productId]=$cart[$productId]+1;
            }
            $_SESSION["cart"] = $cart;
        }
    }

    echo '<div class="container">';
    renderHeader();

    $page = isset($_GET["page"])?$_GET["page"]:1;
    displayCatalogProductsForUser($page);
    echo "<br>";
    displayOnSaleProductsForUser();

    echo '</div>';
?>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>