<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkillRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ]
)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $image = null;

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
