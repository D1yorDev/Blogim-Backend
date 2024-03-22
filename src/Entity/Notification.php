<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
	use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
	use ApiPlatform\Metadata\ApiFilter;
	use ApiPlatform\Metadata\ApiResource;
	use ApiPlatform\Metadata\Delete;
	use ApiPlatform\Metadata\Get;
	use ApiPlatform\Metadata\GetCollection;
	use ApiPlatform\Metadata\Put;
	use App\Controller\DeleteAction;
	use App\Entity\Interfaces\CreatedAtSettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Repository\NotificationRepository;
	use DateTimeInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	
	#[ORM\Entity(repositoryClass: NotificationRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				// todo: faqat readEctionda faqat o'z xabarlaringi ko'rsat va normaliazatsiyalarni ko'rsat
				normalizationContext: ['groups' => ['notifications:read']],
			),
			new Get(
				security: "object.getForUser() == user || is_granted('ROLE_ADMIN')",
			),
			new Put(
				security: "is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "is_granted('ROLE_ADMIN')",
			),
		
		
		],
		normalizationContext: ['groups' => ['notification:read', 'notifications:read']],
		denormalizationContext: ['groups' => ['notification:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact'])]
	class Notification implements
		CreatedAtSettableInterface,
		UpdatedAtSettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'notifications')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $forUser = null;
		
		#[ORM\Column(type: Types::TEXT)]
		private ?string $text = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		private ?\DateTimeInterface $readAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
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
		
		public function getForUser(): ?User
		{
			return $this->forUser;
		}
		
		public function setForUser(?User $forUser): self
		{
			$this->forUser = $forUser;
			
			return $this;
		}
		
		public function getText(): ?string
		{
			return $this->text;
		}
		
		public function setText(string $text): self
		{
			$this->text = $text;
			
			return $this;
		}
		
		public function getReadAt(): ?\DateTimeInterface
		{
			return $this->readAt;
		}
		
		public function setReadAt(?\DateTimeInterface $readAt): self
		{
			$this->readAt = $readAt;
			
			return $this;
		}
	}
