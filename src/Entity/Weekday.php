<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.7
 * Time: 00.27
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Weekday
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
     *      max = 50,
     *      minMessage = "Pavadinimas negali būti trumpesnis nei {{ limit }} simboliai",
     *      maxMessage = "Pavadinimas negali būti ilgesnis nei {{ limit }} simboliai"
     * )
     */
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function __toString()
    {
        return $this->name;
    }
    
}