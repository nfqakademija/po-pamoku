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
     * @ORM\ManyToMany(targetEntity="App\Entity\Subcategory")
     */
    private $subcategories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Timetable")
     */
    private $timetables;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
        $this->timetables = new ArrayCollection();
    }
}
