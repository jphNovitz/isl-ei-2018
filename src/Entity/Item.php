<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\Mapping as ORM;
use mageekguy\atoum\asserters\boolean;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $mode;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $qty;

    /**
     * @var float
     *
     * @ORM\Column(type="float", length=3, nullable=false)
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", length=1, nullable=false)
     */
    private $halal;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     *
     */
    private $product;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Ingredient")
     * @ORM\JoinTable(name="item_vegetables")
     * @ORM\JoinColumn(nullable=true)
     */
    private $vegetables;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $bread;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $sauce;


    public function __construct()
    {
        $this->halal=true;
        $this->qty=1;
        $this->vegetables = new ArrayCollection();
    }

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
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }



    /**
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     */
    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isHalal(): bool
    {
        return $this->halal;
    }

    /**
     * @param bool $halal
     */
    public function setHalal(bool $halal): void
    {
        $this->halal = $halal;
    }

    /**
     * @return string
     */
    public function getSauce(): ?string
    {
        return $this->sauce;
    }

    /**
     * @param string $sauce
     */
    public function setSauce(string $sauce): void
    {
        $this->sauce = $sauce;
    }


    /**
     * @return string
     */
    public function getBread(): ?string
    {
        return $this->bread;
    }

    /**
     * @param string $bread
     */
    public function setBread(string $bread): void
    {
        $this->bread = $bread;
    }


    /**
     * @return ArrayCollection
     */
    public function getVegetables(): ?Collection
    {
        return $this->vegetables;
    }




    /**
     * @param mixed $vegetable
     */
    public function addVegetable($vegetable)
    {
        $this->vegetables[] = $vegetable;
    }

    /**
     * @param mixed $vegetable
     */
    public function removeVegetable($vegetable)
    {
        $this->vegetables->removeElement($vegetable);
        // uncomment if you want to update other side
        //$vegetable->setItem(null);
    }


}
