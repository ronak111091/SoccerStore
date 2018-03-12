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

    public function updateProduct($id,$name,$description,$price,$quantity=0,$image=null,$sale_price=0){
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

    public function getAllProducts(){
        try{
            $stmt = $this->getDbConnection()->prepare("SELECT * FROM product");
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

    public function getAllProductsOnSale(){
        try{
            $stmt = $this->getDbConnection()->prepare("SELECT * FROM product WHERE sale_price>0");
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

}