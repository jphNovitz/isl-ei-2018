<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
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
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $comment;


    /**
     * @var Category
     * @ORM\ManyToMany(targetEntity="App\Entity\Category")
     *
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="parent")
     */
    private $composed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ingredient", inversedBy="composed")
     */
    private $parent;

    /**
     * Ingredient constructor.
     */

    public function __construct()
    {

        $this->categories = new ArrayCollection();
        $this->composed = new ArrayCollection();
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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Add Category
     * @param Category $category
     *
     * @return Category
     */
    public  function  addCategory(Category $category){
        $this->categories[] = $category;
        return $this;

    }

    /**
     * Remove Category
     *
     * @param Category $category
     *
     *
     */
    public function removeCategory(Category $category){
        $this->categories->removeElement($category);
    }

 /**
     * Add Ingredient
     * @param Ingredient $composed
     *
     * @return Ingredient
     */
    public  function  addComposed(Ingredient $ingredient){
        $this->composed[] = $ingredient;
        return $this;

    }

    /**
     * Remove Ingredient
     *
     * @param Ingredient $composed
     *
     *
     */
    public function removeComposed(Ingredient $ingredient){
        $this->composed->removeElement($ingredient);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }


}
