<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.7
 * Time: 00.31
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
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
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Pavadinimas negali būti trumpesnis nei {{ limit }} simboliai",
     *      maxMessage = "Pavadinimas negali būti ilgesnis nei {{ limit }} simboliai"
     * )
     */
    private $name;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Prašome aprašyti šį lauką")
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", cascade={"persist"})
     * @Assert\Valid()
     */
    private $location;
    
    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Prašome užpildyti šį lauką")
     * @Assert\Type(
     *     type="float",
     *     message="Laukelio reikšmė turėtų būti numeris"
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Minimali galima kaina 0 Eurų",
     *      maxMessage = "Maksimaili galima kaina 100 Eurų"
     * )
     */
    private $priceFrom;
    
    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Prašome užpildyti šį lauką")
     * @Assert\Type(
     *     type="float",
     *     message="Laukelio reikšmė turėtų būti numeris"
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Minimali galima kaina 0 Eurų",
     *      maxMessage = "Maksimaili galima kaina 100 Eurų"
     * )
     */
    private $priceTo;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Prašome užpildyti šį lauką")
     * @Assert\Range(
     *      min = 1,
     *      max = 90,
     *      minMessage = "Minimalus galimas amžius 1 metai",
     *      maxMessage = "Maksimailus galimas amžius 90 metų"
     * )
     */
    private $ageFrom;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Prašome užpildyti šį lauką")
     * @Assert\Range(
     *      min = 1,
     *      max = 90,
     *      minMessage = "Minimalus galimas amžius 1 metas",
     *      maxMessage = "Maksimailus galimas amžius 90 metų"
     * )
     */
    private $ageTo;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull(message="Prašome įkelti logotipą.")
     * @Assert\File(mimeTypes={ "image/*"}, mimeTypesMessage="Failas turi būti paveikslėlio formato" )
     *
     */
    private $pathToLogo;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subcategory")
     */
    private $subcategory;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Timetable", cascade={"persist"})
     */
    private $timetables;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="activity")
     */
    private $user;
    
    
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
    
    public function addTimetable($timetable): self
    {
        if ($this->timetables->contains($timetable)) {
            return $this;
        }
        $this->timetables[] = $timetable;
        
        return $this;
    }
    
    
    public function removeTimetable(Timetable $timetable): self
    {
        if ($this->timetables->contains($timetable)) {
            $this->timetables->removeElement($timetable);
        }
        
        return $this;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user): self
    {
        $this->user = $user;
        
        return $this;
    }
    
    
}
