<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.7
 * Time: 00.31
 */

namespace App\Entity;

use App\Utils\Utils;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
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
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Gatvė negali būti trumpesnis nei {{ limit }} simboliai",
     *      maxMessage = "Gatvė negali būti ilgesnis nei {{ limit }} simboliai"
     * )
     */
    private $street;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Namo nr. negali būti ilgesnis nei {{ limit }} simboliai"
     * )
     */
    private $house;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $apartment;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $postcode;
    
    /**
     * @ORM\Column(type="float")
     */
    private $lat;
    
    /**
     * @ORM\Column(type="float")
     */
    private $lng;
    
    public function __construct()
    {
        $address = $this->getStreet().' '.$this->getHouse().', '.$this->getCity();
        $data = Utils::fetchLocationByAddress($address);
        $this->setLat($data['lat']);
        $this->setLng($data['lng']);
        $this->setPostcode($data['postcode']);
    }
    
    
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

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
    
}