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

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): void
    {
        $this->location = $location;
    }

    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    public function setPriceFrom($priceFrom): void
    {
        $this->priceFrom = $priceFrom;
    }

    public function getPriceTo()
    {
        return $this->priceTo;
    }

    public function setPriceTo($priceTo): void
    {
        $this->priceTo = $priceTo;
    }

    public function getAgeFrom()
    {
        return $this->ageFrom;
    }

    public function setAgeFrom($ageFrom): void
    {
        $this->ageFrom = $ageFrom;
    }

    public function getAgeTo()
    {
        return $this->ageTo;
    }

    public function setAgeTo($ageTo): void
    {
        $this->ageTo = $ageTo;
    }

    public function getPathToLogo()
    {
        return $this->pathToLogo;
    }

    public function setPathToLogo($pathToLogo): void
    {
        $this->pathToLogo = $pathToLogo;
    }

    public function getSubcategory()
    {
        return $this->subcategory;
    }

    public function setSubcategory($subcategory): void
    {
        $this->subcategory = $subcategory;
    }

    public function getTimetables()
    {
        return $this->timetables;
    }

    public function addTimetable($timetable)
    {
        if ($this->timetables->contains($timetable)) {
            return;
        }
        $this->timetables[] = $timetable;
    }

}
