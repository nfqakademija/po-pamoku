<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Timetable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Weekday")
     */
    private $weekday;
    
    /**
     * @ORM\Column(type="time")
     * @Assert\Time()
     */
    private $timeFrom;
    
    /**
     * @ORM\Column(type="time")
     * @Assert\Time()
     */
    private $timeTo;
    

    public function getWeekday()
    {
        return $this->weekday;
    }
    
    
    public function setWeekday($weekday): self
    {
        $this->weekday = $weekday;
        
        return $this;
    }
    
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }
    
    public function setTimeFrom($timeFrom): self
    {
        $this->timeFrom = $timeFrom;
        
        return $this;
    }
    
    public function getTimeTo()
    {
        return $this->timeTo;
    }
    
    public function setTimeTo($timeTo): self
    {
        $this->timeTo = $timeTo;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
}