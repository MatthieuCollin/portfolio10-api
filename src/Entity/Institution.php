<?php

namespace App\Entity;

use ORM\JoinColumn;
use App\Entity\Task;
use ApiPlatform\Metadata\Get;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\InstitutionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Institution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('read')]
    private ?\DateTimeInterface $beginDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups('read')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('read')]
    private ?string $link = null;

    #[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'institutions')]
    #[Groups('read')]
    private Collection $task;

    #[ORM\Column(type: Types::BLOB)]
    #[Groups('read')]
    private $image = null;

    public function __construct()
    {
        $this->task = new ArrayCollection();
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

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): static
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<int, task>
     */
    public function getTask(): Collection
    {
        return $this->task;
    }

    public function addTask(task $task): static
    {
        if (!$this->task->contains($task)) {
            $this->task->add($task);
        }

        return $this;
    }

    public function removeTask(task $task): static
    {
        $this->task->removeElement($task);

        return $this;
    }

    public function getImage()
    {
        // Check if $this->image is a resource (stream)
        if (is_resource($this->image)) {
            // Rewind the stream to the beginning (if needed)
            rewind($this->image);

            // Get the contents of the stream and base64 encode it
            $base64Data = base64_encode(stream_get_contents($this->image));

            // Close the stream
            fclose($this->image);

            return $base64Data;
        }

        // If $this->image is not a resource, assume it's already base64 encoded
        return $this->image;
    }


    public function setImage($image): static
    {
        $this->image = $image;

        return $this;
    }
}
