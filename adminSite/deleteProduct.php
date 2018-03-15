<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 15-03-2018
 * Time: 13:17
 */


require_once '../LIB_project1.php';

if(isset($_GET["task"]) && $_GET["task"]=="deleteRecord"){
    $productId = $_GET["id"];
    $product = getProductById($productId);
    $name = $product->getName();
    $result = deleteProduct($productId);

    if ($result) {
        $success=true;
        $message = "{$name} was successfully deleted!";
    }else{
        $success=false;
        $message = "{$name} was successfully deleted!";
    }
    $success=$success?"true":"false";
    header("Location: admin.php?success={$success}&message={$message}");
}