<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 15-03-2018
 * Time: 11:27
 */

require_once '../LIB_project1.php';
session_start();
if(isset($_GET["deleteItem"]) && !empty($_GET["deleteItem"])){
    $productIds = explode("-",$_GET["deleteItem"]);
    $success = false;
    $finalMessage="";
    if(isset($_SESSION["cart"])) {
        $cart = $_SESSION["cart"];

        foreach ($productIds as $productId) {
            if (empty($productId))
                break;
            $product = getProductById($productId);
            $name = $product->getName();

            if (array_key_exists($productId, $cart)) {
                $qty = $cart[$productId];
                $result = incrementProductQuantity($productId, $qty);
                if ($result) {
                    unset($cart[$productId]);
                    $_SESSION["cart"] = $cart;
                    $message = "{$name} has been successfully removed from the cart!";
                    $success = true;
                } else {
                    $message = "An error occurred while removing {$name} from the cart!";
                }
                $finalMessage=$finalMessage.$message;
                if(end($productIds)!==$productId){
                    $finalMessage=$finalMessage."<br>";
                }
            }
        }
    }else {
        $message = "Cart details not found!";
    }
    $success=$success?"true":"false";
        header("Location: cartList.php?success={$success}&message={$finalMessage}");
}else{
    header("Location: cartList.php");
}
