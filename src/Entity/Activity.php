<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50
     * )
     */
    private $name;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", cascade={"persist"})
     * @Assert\Valid()
     */
    private $location;
    
    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull()
     * @Assert\Type(type="float")
     * @Assert\Range(
     *      min = 0,
     *      max = 100
     * )
     */
    private $priceFrom;
    
    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull()
     * @Assert\Type(type="float")
     * @Assert\Range(
     *      min = 0,
     *      max = 100
     * )
     */
    private $priceTo;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 1,
     *      max = 90
     * )
     */
    private $ageFrom;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 1,
     *      max = 90
     * )
     */
    private $ageTo;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/*"})
     */
    private $pathToLogo = "/uploads/33e75ff09dd601bbe69f351039152189.jpg";
    
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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $commentCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ratingCount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rating;


    
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

    public function setTimetables($timetables): self
    {
        $this->timetables = $timetables;

        return $this;
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

    public function getCommentCount()
    {
        return $this->commentCount;
    }

    public function setCommentCount($commentCount): self
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    public function setRatingCount($ratingCount): self
    {
        $this->ratingCount = $ratingCount;

        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
