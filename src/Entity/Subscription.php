<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Entity\Interfaces\CreatedAtSettableInterface;
	use App\Entity\Interfaces\CreatedBySettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Repository\SubscriptionRepository;
	use DateTimeInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
	#[ApiResource]
	class Subscription implements
		CreatedAtSettableInterface,
		CreatedBySettableInterface,
		UpdatedAtSettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'subscriptions')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $follow = null;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
		public function getCreatedBy(): ?User
		{
			return $this->createdBy;
		}
		
		public function setCreatedBy(?UserInterface $createdBy): self
		{
			$this->createdBy = $createdBy;
			
			return $this;
		}
		
		public function getCreatedAt(): ?DateTimeInterface
		{
			return $this->createdAt;
		}
		
		public function setCreatedAt(DateTimeInterface $createdAt): self
		{
			$this->createdAt = $createdAt;
			
			return $this;
		}
		
		public function getUpdatedAt(): ?DateTimeInterface
		{
			return $this->updatedAt;
		}
		
		public function setUpdatedAt(?DateTimeInterface $updatedAt): self
		{
			$this->updatedAt = $updatedAt;
			
			return $this;
		}
		
		public function getIsDeleted(): ?bool
		{
			return $this->isDeleted;
		}
		
		public function setIsDeleted(bool $isDeleted): self
		{
			$this->isDeleted = $isDeleted;
			
			return $this;
		}
		
		
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
