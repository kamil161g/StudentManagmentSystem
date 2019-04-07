<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObserverRepository")
 */
class Observer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="observers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Module", inversedBy="observers")
     */
    private $module;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAccept;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getIsAccept(): ?bool
    {
        return $this->isAccept;
    }

    public function setIsAccept(?bool $isAccept): self
    {
        $this->isAccept = $isAccept;

        return $this;
    }
}
