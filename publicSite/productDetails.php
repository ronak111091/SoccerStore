<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 15-03-2018
 * Time: 18:19
 */

header("content-type:application/json");
require_once '../LIB_project1.php';

if(isset($_GET["getDetails"])){
    $productId = $_GET["getDetails"];
    $product = getProductById($productId);
    $name = $product->getName();
    $price = $product->getPrice();
    $quantity= $product->getQuantity();
    $image=$product->getImage();
    $description = $product->getDescription();
    $salePrice=$product->getSalePrice();
    if(empty($image)){
        $image="../images/placeholder.png";
    }else{
        $image="../uploads/".$image;
    }
    $jsonResponse = <<<EOT
{
"id":"{$productId}",
"name":"{$name}",
"description":"{$description}",
"price":"{$price}",
"quantity":"{$quantity}",
"salePrice":"{$salePrice}",
"image":"{$image}"
}
EOT;
echo $jsonResponse;

}