<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.7
 * Time: 00.31
 */

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location")
     */
    private $location;

    /**
     * @ORM\Column(type="float")
     */
    private $priceFrom;

    /**
     * @ORM\Column(type="float")
     */
    private $priceTo;

    /**
     * @ORM\Column(type="integer")
     */
    private $ageFrom;

    /**
     * @ORM\Column(type="integer")
     */
    private $ageTo;

    /**
     * @ORM\Column(type="string")
     */
    private $pathToLogo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subcategory")
     */
    private $subcategory;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Timetable")
     */
    private $timetables;


    public function __construct()
    {
        $this->timetables = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    public function setPriceFrom($priceFrom): self
    {
        $this->priceFrom = $priceFrom;

        return $this;
    }

    public function getPriceTo()
    {
        return $this->priceTo;
    }

    public function setPriceTo($priceTo): self
    {
        $this->priceTo = $priceTo;

        return $this;
    }

    public function getAgeFrom()
    {
        return $this->ageFrom;
    }

    public function setAgeFrom($ageFrom): self
    {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    public function getAgeTo()
    {
        return $this->ageTo;
    }

    public function setAgeTo($ageTo): self
    {
        $this->ageTo = $ageTo;

        return $this;
    }

    public function getPathToLogo()
    {
        return $this->pathToLogo;
    }

    public function setPathToLogo($pathToLogo): self
    {
        $this->pathToLogo = $pathToLogo;

        return $this;
    }

    public function getSubcategory()
    {
        return $this->subcategory;
    }

    public function setSubcategory($subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getTimetables()
    {
        return $this->timetables;
    }

    public function addTimetable($timetable) : self
    {
        if ($this->timetables->contains($timetable)) {
            return $this;
        }
        $this->timetables[] = $timetable;

        return $this;
    }

}
