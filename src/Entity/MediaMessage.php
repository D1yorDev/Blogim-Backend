<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MediaMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaMessageRepository::class)]
#[ApiResource]
class MediaMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MediaObject $media = null;

    #[ORM\ManyToOne(inversedBy: 'mediaMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Message $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?MediaObject
    {
        return $this->media;
    }

    public function setMedia(?MediaObject $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }
}
