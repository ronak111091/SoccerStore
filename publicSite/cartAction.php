<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 15-03-2018
 * Time: 11:27
 */

require_once '../LIB_project1.php';
session_start();
if(isset($_GET["deleteItem"])){
    $productId = $_GET["deleteItem"];
    $product = getProductById($productId);
    $name = $product->getName();
    $success=false;
    if(isset($_SESSION["cart"])){
        $cart = $_SESSION["cart"];
        if(array_key_exists($productId,$cart)){
            $qty = $cart[$productId];
            $result = incrementProductQuantity($productId,$qty);
            if($result){
                unset($cart[$productId]);
                $_SESSION["cart"]=$cart;
                $message = "{$name} has been successfully removed from the cart!";
                $success=true;
            }else{
                $message = "An error occurred while removing the item from the cart!";
            }
        }else{
            $message = "Item not found in the cart!";
        }
    }else{
        $message = "Cart details not found!";
    }
    $success=$success?"true":"false";
    header("Location: cartList.php?success={$success}&message={$message}");
}