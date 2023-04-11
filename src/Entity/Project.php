<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[UniqueEntity('name')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $cost = 0;

    #[ORM\Column]
    private ?\DateTime $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endAt = null;

    #[ORM\Column]
    private bool $isActive = false;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectType $type = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sector $sector = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $closeContract = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $presentedAt = null;

    #[ORM\Column(nullable: true, unique: true)]
    private ?int $number = null;

    public function __construct()
    {
        $this->startAt = new DateTime();
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

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getType(): ?ProjectType
    {
        return $this->type;
    }

    public function setType(?ProjectType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    public function setSector(?Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getCloseContract(): ?string
    {
        return $this->closeContract;
    }

    public function setCloseContract(?string $closeContract): self
    {
        $this->closeContract = $closeContract;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getPresentedAt(): ?\DateTimeImmutable
    {
        return $this->presentedAt;
    }

    public function setPresentedAt(?\DateTimeImmutable $presentedAt): self
    {
        $this->presentedAt = $presentedAt;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }
}
