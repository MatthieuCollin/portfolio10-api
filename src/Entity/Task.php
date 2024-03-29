<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['read']],
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]   
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Institution::class, mappedBy: 'task')]
    private Collection $institutions;

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Institution>
     */
    public function getInstitutions(): Collection
    {
        return $this->institutions;
    }

    public function addInstitution(Institution $institution): static
    {
        if (!$this->institutions->contains($institution)) {
            $this->institutions->add($institution);
            $institution->addTask($this);
        }

        return $this;
    }

    public function removeInstitution(Institution $institution): static
    {
        if ($this->institutions->removeElement($institution)) {
            $institution->removeTask($this);
        }

        return $this;
    }
}
