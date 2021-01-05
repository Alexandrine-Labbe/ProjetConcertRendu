<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=BandRepository::class)
 */
class Band
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $style;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $yearOfCreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastAlbumName;

    /**
     * @ORM\OneToMany(targetEntity=Concert::class, mappedBy="band", fetch="EAGER")
     * @OrderBy({"date" = "DESC"})
     */
    private $shows;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="band", orphanRemoval=true)
     */
    private $members;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picturePath;

    public function __construct()
    {
        $this->shows = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getYearOfCreation(): ?string
    {
        return $this->yearOfCreation;
    }

    public function setYearOfCreation(string $yearOfCreation): self
    {
        $this->yearOfCreation = $yearOfCreation;

        return $this;
    }

    public function getLastAlbumName(): ?string
    {
        return $this->lastAlbumName;
    }

    public function setLastAlbumName(?string $lastAlbumName): self
    {
        $this->lastAlbumName = $lastAlbumName;

        return $this;
    }



    /**
     * @return Collection|Concert[]
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(Concert $show): self
    {
        if (!$this->shows->contains($show)) {
            $this->shows[] = $show;
            $show->setBand($this);
        }

        return $this;
    }

    public function removeShow(Concert $show): self
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getBand() === $this) {
                $show->setBand(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setBand($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getBand() === $this) {
                $member->setBand(null);
            }
        }

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(?string $picturePath): self
    {
        $this->picturePath = $picturePath;

        return $this;
    }
}
