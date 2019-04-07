<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="modules")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="observation")
     */
    private $observation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Observer", mappedBy="module")
     */
    private $observers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="module")
     */
    private $comments;

    public function __construct()
    {
        $this->observation = new ArrayCollection();
        $this->observers = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Observer[]
     */
    public function getObservers(): Collection
    {
        return $this->observers;
    }

    public function addObserver(Observer $observer): self
    {
        if (!$this->observers->contains($observer)) {
            $this->observers[] = $observer;
            $observer->setModule($this);
        }

        return $this;
    }

    public function removeObserver(Observer $observer): self
    {
        if ($this->observers->contains($observer)) {
            $this->observers->removeElement($observer);
            // set the owning side to null (unless already changed)
            if ($observer->getModule() === $this) {
                $observer->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setModule($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getModule() === $this) {
                $comment->setModule(null);
            }
        }

        return $this;
    }
}
