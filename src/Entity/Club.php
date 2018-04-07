<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 */
class Club
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $activity_id;
    /**
     * @ORM\Column(type="text", length=100)
     */
    private $title;

    public function getId() {
        return $this->id;
    }
    public function getActivityId() {
        return $this->activity_id;
    }
    public function getTitle() {
        return $this->title;
    }

}