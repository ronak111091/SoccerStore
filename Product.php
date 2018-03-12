<?php
/**
 * Created by PhpStorm.
 * User: Ronak
 * Date: 12-03-2018
 * Time: 12:41
 */

class Product
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $quantity;
    private $image;
    private $sale_price;
    private $created_timestamp;
    private $updated_timestamp;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getSalePrice()
    {
        return $this->sale_price;
    }

    /**
     * @param mixed $sale_price
     */
    public function setSalePrice($sale_price): void
    {
        $this->sale_price = $sale_price;
    }

    /**
     * @return mixed
     */
    public function getCreatedTimestamp()
    {
        return $this->created_timestamp;
    }

    /**
     * @param mixed $created_timestamp
     */
    public function setCreatedTimestamp($created_timestamp): void
    {
        $this->created_timestamp = $created_timestamp;
    }

    /**
     * @return mixed
     */
    public function getUpdatedTimestamp()
    {
        return $this->updated_timestamp;
    }

    /**
     * @param mixed $updated_timestamp
     */
    public function setUpdatedTimestamp($updated_timestamp): void
    {
        $this->updated_timestamp = $updated_timestamp;
    }





}