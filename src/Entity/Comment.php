<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity")
     */
    protected $activity;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    protected $commentText;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getActivity()
    {
        return $this->activity;
    }

    public function setActivity($activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCommentText()
    {
        return $this->commentText;
    }

    public function setCommentText($commentText): self
    {
        $this->commentText = $commentText;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateDate()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }
}