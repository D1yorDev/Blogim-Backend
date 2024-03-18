<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PublicationLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationLikeRepository::class)]
#[ApiResource]
class PublicationLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publication $post = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Publication
    {
        return $this->post;
    }

    public function setPost(?Publication $post): self
    {
        $this->post = $post;

        return $this;
    }
}
