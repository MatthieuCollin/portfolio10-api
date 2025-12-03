<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WorkRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: WorkRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ]
)]
class Work
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $githubLink = null;

    #[ORM\Column(length: 255)]
    private ?string $websiteLink = null;

    #[ORM\Column(type: Types::BLOB)]
    private  $image = null;

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

    public function getGithubLink(): ?string
    {
        return $this->githubLink;
    }

    public function setGithubLink(string $githubLink): static
    {
        $this->githubLink = $githubLink;

        return $this;
    }

    public function getWebsiteLink(): ?string
    {
        return $this->websiteLink;
    }

    public function setWebsiteLink(string $websiteLink): static
    {
        $this->websiteLink = $websiteLink;

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
