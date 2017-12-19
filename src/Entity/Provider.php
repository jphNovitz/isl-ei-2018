<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProviderRepository")
 */
class Provider
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
    private $street_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $street_nr;

    /**
     * @var  string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;

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
    public function getStreetName()
    {
        return $this->street_name;
    }

    /**
     * @param string $street_name
     */
    public function setStreetName($street_name)
    {
        $this->street_name = $street_name;
    }

    /**
     * @return string
     */
    public function getStreetNr()
    {
        return $this->street_nr;
    }

    /**
     * @param string $street_nr
     */
    public function setStreetNr($street_nr)
    {
        $this->street_nr = $street_nr;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }



}
