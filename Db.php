<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 12-03-2018
 * Time: 12:28
 */

require_once 'Product.php';

class Db
{
    private $dbConnection;

    function __construct(){

        try{
            $this->dbConnection = new PDO("mysql:host=localhost;dbname=test",
                "root", "Password");
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $pDOException){
            echo $pDOException->getMessage();
            die();
        }
    }

    /**
     * @return PDO
     */
    public function getDbConnection(): PDO
    {
        return $this->dbConnection;
    }

    public function insertProduct($name,$description,$price,$quantity=0,$image=null,$sale_price=0){
        try{
            $stmt= $this->getDbConnection()->prepare("INSERT INTO product(name,description,price,quantity,image,
              sale_price) VALUES(:name,:description,:price,:quantity,:image,:sale_price)");
            $stmt->execute(array(
                ":name"=>$name,
                ":description"=> $description,
                ":price"=> $price,
                ":quantity"=>$quantity,
                ":image"=>$image,
                ":sale_price"=>$sale_price
            ));
            return $this->getDbConnection()->lastInsertId();
        }catch(PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function updateProduct($id,$name,$description,$price,$quantity=0,$sale_price=0,$image=null){
        try{
            $stmt = $this->getDbConnection()->prepare("UPDATE product SET name=:name, description=:description,
              price=:price, quantity=:quantity, image=:image, sale_price=:sale_price WHERE id=:id");
            $stmt->execute(array(
                ":id"=>$id,
                ":name"=>$name,
                ":description"=> $description,
                ":price"=> $price,
                ":quantity"=>$quantity,
                ":image"=>$image,
                ":sale_price"=>$sale_price
            ));
            return $stmt->rowCount();
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function updateProductQuantity($id,$add=true,$amt=1){
        try{
            if($add){
                $query = "UPDATE product SET quantity=quantity+:amt WHERE id=:id AND quantity>=0";
            }else{
                $query = "UPDATE product SET quantity=quantity-:amt WHERE id=:id AND quantity>0";
            }
            $stmt = $this->getDbConnection()->prepare($query);
            $stmt->execute(array(
                ":id"=>$id,
                ":amt"=>$amt
            ));
            return $stmt->rowCount();
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function deleteProduct($ids){
        try{
            $stmt = $this->getDbConnection()->prepare("DELETE FROM product WHERE id=:id");
            $rowCount = 0;
            foreach ($ids as $id){
                $stmt->execute(array(
                    ":id"=>$id
                ));
                $rowCount+=$stmt->rowCount();
            }
            return $rowCount;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getProductCount($searchQuery=""){
        try{
            $sqlQuery = "SELECT COUNT(*) FROM product";

            $stmt = $this->getDbConnection()->prepare($sqlQuery);

            $stmt->execute();

            return $stmt->fetchColumn();
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getAllProducts($limit=10,$offset=0,$query=""){
        try{
            $sqlQuery = "SELECT * FROM product ORDER BY updated_timestamp DESC LIMIT ? OFFSET ?";
//            $this->getDbConnection()->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $this->getDbConnection()->prepare($sqlQuery);
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->bindParam(1,$limit,PDO::PARAM_INT);
            $stmt->bindParam(2,$offset,PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while ($row=$stmt->fetch()){
                $data[]=$row;
            }
            return $data;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getCatalogProducts($limit=6,$offset=0){
        try{
            $sqlQuery = "SELECT * FROM product WHERE sale_price=0 AND quantity>0 LIMIT ? OFFSET ?";
            $stmt = $this->getDbConnection()->prepare($sqlQuery);
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->bindParam(1,$limit,PDO::PARAM_INT);
            $stmt->bindParam(2,$offset,PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while ($row=$stmt->fetch()){
                $data[]=$row;
            }
            return $data;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getSaleProducts($limit=5,$offset=0){
        try{
            $sqlQuery = "SELECT * FROM product WHERE sale_price>0 AND quantity>0 LIMIT ? OFFSET ?";
            $stmt = $this->getDbConnection()->prepare($sqlQuery);
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->bindParam(1,$limit,PDO::PARAM_INT);
            $stmt->bindParam(2,$offset,PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while ($row=$stmt->fetch()){
                $data[]=$row;
            }
            return $data;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function searchProducts($query){
        try{
            $stmt = $this->getDbConnection()->prepare("SELECT * FROM product WHERE name LIKE :name");
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->execute(array(
                ":name"=>"%".$query."%"
            ));
            $data = array();
            while ($row=$stmt->fetch()){
                $data[]=$row;
            }
            return $data;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

//    public function getAllProductsOnSale(){
//        try{
//            $stmt = $this->getDbConnection()->prepare("SELECT * FROM product WHERE sale_price>0");
//            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
//            $stmt->execute();
//            $data = array();
//            while ($row=$stmt->fetch()){
//                $data[]=$row;
//            }
//            return $data;
//        }catch (PDOException $pDOException){
//            echo $pDOException->getMessage();
//        }
//    }

    public function getAllOutOfStockProducts(){
        try{
            $stmt = $this->getDbConnection()->prepare("SELECT * FROM product WHERE quantity=0");
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->execute();
            $data = array();
            while ($row=$stmt->fetch()){
                $data[]=$row;
            }
            return $data;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getProductById($id){
        try{
            $stmt = $this->getDbConnection()->prepare("SELECT * FROM product WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->execute(array(
                ":id"=>$id
            ));
            $row=$stmt->fetch();
            return $row;
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getCatalogProductCount(){
        try{
            $sqlQuery = "SELECT COUNT(*) FROM product WHERE sale_price=0";

            $stmt = $this->getDbConnection()->prepare($sqlQuery);

            $stmt->execute();

            return $stmt->fetchColumn();
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

    public function getSaleProductCount(){
        try{
            $sqlQuery = "SELECT COUNT(*) FROM product WHERE sale_price>0";

            $stmt = $this->getDbConnection()->prepare($sqlQuery);

            $stmt->execute();

            return $stmt->fetchColumn();
        }catch (PDOException $pDOException){
            echo $pDOException->getMessage();
        }
    }

}