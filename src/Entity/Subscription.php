<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ApiResource]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $follow = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollow(): ?User
    {
        return $this->follow;
    }

    public function setFollow(?User $follow): self
    {
        $this->follow = $follow;

        return $this;
    }
}
