<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/soccerStore.css">
</head>
<body>

<div class="wrapper">

    <?php
    /**
     * Created by PhpStorm.
     * User: Ronak
     * Date: 14-03-2018
     * Time: 15:33
     */
    require_once '../LIB_project1.php';
    session_start();
    if(isset($_SESSION["cart"])){
        $cart=$_SESSION["cart"];
    }else{
        $cart=array();
    }
    echo renderHeader();
    echo '<main><div class="container">';
    renderPublicSiteTabs(2);
    echo "<br><h3>Your Cart</h3>";

    if(isset($_GET["success"])){
        $successVal = $_GET["success"]=="true"?true:false;
        $alertClass =$successVal?"alert-success":"alert-danger";
        echo '<div class="alert '.$alertClass. '" role="alert">'.$_GET["message"].'</div>';
    }

    $allProductIds="";
    $totalCartPrice=0;
    if(!empty($cart)){
        $rowNo = 1;
        $html = '<table class="table"><thead><tr><th width="10%" scope="col">#</th><th width="20%" scope="col">Image</th><th width="30%"  scope="col">Name</th><th scope="col" width="10%" >Price</th><th scope="col" width="10%" >Quantity</th><th scope="col" width="20%" ></th></tr></thead>';
        foreach ($cart as $id=>$qty){
            $product = getProductById($id);
            $name=$product->getName();
            $price = '$'.$product->getPrice();
            $totalPrice=$product->getPrice()*$qty;
            $image = $product->getImage();
            $allProductIds=$allProductIds.$id."-";
            $totalCartPrice +=$totalPrice;
            if(empty($image)){
                $image="../images/placeholder.png";
            }else{
                $image="../uploads/".$image;
            }
            $html=$html.<<<EOT
<tr>
<th scope="row">{$rowNo}</th>
<th scope="row"><img src="{$image}" class="img-thumbnail"></th>
<th scope="row">{$name}</th>
<th scope="row">{$price}</th>
<th scope="row">{$qty}</th>
<th scope="row"><a href="cartAction.php?deleteItem={$id}" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a></th>
</tr>
EOT;
            $rowNo++;
        }
        $html=$html.'</tbody></table>';
        echo $html;
    }else{
        echo "<h4 class='alert alert-warning'>You don't have any items in the cart!</h4>";
    }
    $total = '$'.$totalCartPrice;
    $cartInfo=<<<EOT
<div class="row">
<div class="col">
    <a href='cartAction.php?deleteItem={$allProductIds}' class='btn btn-danger float-left'>Empty cart</a>    
</div>
<div class="col">
    <h3 class="float-right">Total: {$total}</h3>
</div>
</div>
<div class="row">
<div class="col">
    <a href='productList.php' class='btn btn-primary float-left'>Continue Shopping</a>
</div>

</div>
EOT;
    echo $cartInfo;
//    echo "<a href='cartAction.php?deleteItem={$allProductIds}' class='btn btn-danger'>Empty cart</a><br><br>";
//    echo "<a href='productList.php' class='btn btn-primary'>Continue Shopping</a>";

    echo '</div></main>';
    renderFooter();
    ?>

</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>