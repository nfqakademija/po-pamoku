<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Toks elektroninio pašto addresas jau naudojamas")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Email(message="Neteisingai įvestas elektroninio pašto adresas")
     */
    protected $email;
    
    /**
     * @Assert\NotBlank(groups={"Register", "Update"}, message="Prašome užpildyti šį lauką")
     */
    protected $plainPassword;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $password;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Regex(pattern="/^[\p{L}\s-]+$/u", message="Varde yra įvesta neleistinų ženklų")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Regex(pattern="/^[\p{L}\s-]+$/u", message="Pavardėje yra įvesta neleistinų ženklų")
     */
    protected $surname;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Regex(pattern="/(^(\+370)[0-9]{8})|((8)[0-9]{8})$/", message="Telefono numeris įvestas neteisingu formatu")
     */
    protected $phoneNumber;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $role;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Activity", mappedBy="user", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    protected $activity;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isBlocked;
    
    
    public function getRoles()
    {
        return [$this->getRole()];
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getSalt()
    {
    }
    
    public function getUsername()
    {
        return $this->email;
    }
    
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
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
    
    public function getSurname()
    {
        return $this->surname;
    }
    
    public function setSurname($surname): self
    {
        $this->surname = $surname;
        return $this;
    }
    
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function setPhoneNumber($phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function setRole($role): self
    {
        $this->role = $role;
        return $this;
    }
    
    public function getActivity()
    {
        return $this->activity;
    }
    
    public function setActivity($activity): self
    {
        $this->activity = $activity;
        $activity->setUser($this);
        return $this;
    }

    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    public function setIsBlocked($isBlocked): self
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }
    
}