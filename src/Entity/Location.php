<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.7
 * Time: 00.31
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     */
    private $street;

    /**
     * @ORM\Column(type="string")
     */
    private $house;

    /**
     * @ORM\Column(type="integer")
     */
    private $apartment;

    /**
     * @ORM\Column(type="string")
     */
    private $postcode;


    public function getCity()
    {
        return $this->city;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCity($city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouse()
    {
        return $this->house;
    }

    public function setHouse($house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getApartment()
    {
        return $this->apartment;
    }

    public function setApartment($apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setPostcode($postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }



}