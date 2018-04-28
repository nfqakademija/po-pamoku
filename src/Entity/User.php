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
     * @Assert\Regex(pattern="/^[\p{L}\s-]+$/", message="Varde yra įvesta neleistinų ženklų")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Prašome užpildyti šį lauką")
     * @Assert\Regex(pattern="/^[\p{L}\s-]+$/", message="Pavardėje yra įvesta neleistinų ženklų")
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
    
    public function setId($id): void
    {
        $this->id = $id;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email): void
    {
        $this->email = $email;
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
    
    public function setName($name): void
    {
        $this->name = $name;
    }
    
    public function getSurname()
    {
        return $this->surname;
    }
    
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }
    
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function setRole($role): void
    {
        $this->role = $role;
    }
    
    public function getActivity()
    {
        return $this->activity;
    }
    
    public function setActivity($activity): void
    {
        $this->activity = $activity;
        $activity->setUser($this);
    }
    
    public function getOldPassword()
    {
        return $this->getPassword();
    }
    
    
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }
    
    
}