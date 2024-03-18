<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Entity\Interfaces\CreatedAtSettableInterface;
	use App\Entity\Interfaces\CreatedBySettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Entity\Interfaces\UpdatedBySettableInterface;
	use App\Repository\StoryTextRepository;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	#[ORM\Entity(repositoryClass: StoryTextRepository::class)]
	#[ApiResource]
	class StoryText implements
		CreatedAtSettableInterface,
		CreatedBySettableInterface,
		UpdatedAtSettableInterface,
		UpdatedBySettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'storyTexts')]
		#[ORM\JoinColumn(nullable: false)]
		private ?Story $story = null;
		
		#[ORM\Column(type: Types::TEXT)]
		private ?string $text = null;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		private ?\DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		private ?\DateTimeInterface $updatedAt = null;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $updatedBy = null;
		
		#[ORM\Column]
		private ?bool $isDeleted = false;
		
		
		public function getId(): ?int
		{
			return $this->id;
		}
		
		public function getStory(): ?Story
		{
			return $this->story;
		}
		
		public function setStory(?Story $story): self
		{
			$this->story = $story;
			
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
		
		public function getCreatedBy(): ?User
		{
			return $this->createdBy;
		}
		
		public function setCreatedBy(?UserInterface $createdBy): self
		{
			$this->createdBy = $createdBy;
			
			return $this;
		}
		
		public function getCreatedAt(): ?\DateTimeInterface
		{
			return $this->createdAt;
		}
		
		public function setCreatedAt(\DateTimeInterface $createdAt): self
		{
			$this->createdAt = $createdAt;
			
			return $this;
		}
		
		public function getUpdatedAt(): ?\DateTimeInterface
		{
			return $this->updatedAt;
		}
		
		public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
		{
			$this->updatedAt = $updatedAt;
			
			return $this;
		}
		
		public function getUpdatedby(): ?User
		{
			return $this->updatedBy;
		}
		
		public function setUpdatedby(?UserInterface $updatedBy): self
		{
			$this->updatedBy = $updatedBy;
			
			return $this;
		}
		
		public function isIsDeleted(): ?bool
		{
			return $this->isDeleted;
		}
		
		public function setIsDeleted(bool $isDeleted): self
		{
			$this->isDeleted = $isDeleted;
			
			return $this;
		}
	}