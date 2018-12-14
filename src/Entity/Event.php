<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, minMessage="Your event require 10 characters min.", maxMessage="Your title is too long !")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, minMessage="Your description require 10 characters min.")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Picture", inversedBy="event")
     * @ORM\JoinColumn(nullable=false)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MeetingPoint", mappedBy="event", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $meetingPoints;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="guestEvent")
     */
    private $guest;

    public function __construct()
    {
        $this->meetingPoints = new ArrayCollection();
        $this->guest = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title . uniqid());
        }
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|MeetingPoint[]
     */
    public function getMeetingPoints(): Collection
    {
        return $this->meetingPoints;
    }

    public function addMeetingPoint(MeetingPoint $meetingPoint): self
    {
        if (!$this->meetingPoints->contains($meetingPoint)) {
            $this->meetingPoints[] = $meetingPoint;
            $meetingPoint->setEvent($this);
        }

        return $this;
    }

    public function removeMeetingPoint(MeetingPoint $meetingPoint): self
    {
        if ($this->meetingPoints->contains($meetingPoint)) {
            $this->meetingPoints->removeElement($meetingPoint);
            // set the owning side to null (unless already changed)
            if ($meetingPoint->getEvent() === $this) {
                $meetingPoint->setEvent(null);
            }
        }

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
     * @return Collection|User[]
     */
    public function getGuest(): Collection
    {
        return $this->guest;
    }

    public function addGuest(User $guest): self
    {
        if (!$this->guest->contains($guest)) {
            $this->guest[] = $guest;
        }

        return $this;
    }

    public function removeGuest(User $guest): self
    {
        if ($this->guest->contains($guest)) {
            $this->guest->removeElement($guest);
        }

        return $this;
    }
}
