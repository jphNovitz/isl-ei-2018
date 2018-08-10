<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var ArrayCollection $items
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", cascade={"persist"})
     */
     private $items ;

    /**
     * @var string $user
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="cart_client", referencedColumnName="id")
     */
    private $client;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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
     * @return datetime
     */
    public function getCreated(): \datetime
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     */
    public function setCreated(\datetime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return datetime
     */
    public function getUpdated(): \datetime
    {
        return $this->updated;
    }

    /**
     * @param datetime $updated
     */
    public function setUpdated(\datetime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getItems(): string
    {
        return $this->items;
    }

    /**
     * @param mixed $item
     */
    public function addItem($item)
    {
        $this->items->add($item);
        // uncomment if you want to update other side
        //$item->setCart($this);
    }

    /**
     * @param mixed $item
     */
    public function removeItem($item)
    {
        $this->items->removeElement($item);
        // uncomment if you want to update other side
        //$item->setCart(null);
    }


    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient(User $client): void
    {
        $this->client = $client;
    }


}
