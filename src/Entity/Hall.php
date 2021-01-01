<?php

namespace App\Entity;

use App\Repository\HallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HallRepository::class)
 */
class Hall
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
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\OneToMany(targetEntity=Concert::class, mappedBy="hall")
     */
    private $shows;

    /**
     * @ORM\ManyToOne(targetEntity=ConcertHall::class, inversedBy="halls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $concertHall;

    public function __construct()
    {
        $this->shows = new ArrayCollection();
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

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
            $show->setHall($this);
        }

        return $this;
    }

    public function removeShow(Concert $show): self
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getHall() === $this) {
                $show->setHall(null);
            }
        }

        return $this;
    }

    public function getConcertHall(): ?ConcertHall
    {
        return $this->concertHall;
    }

    public function setConcertHall(?ConcertHall $concertHall): self
    {
        $this->concertHall = $concertHall;

        return $this;
    }
}
