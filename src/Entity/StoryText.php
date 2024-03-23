<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
	use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
	use ApiPlatform\Metadata\ApiFilter;
	use ApiPlatform\Metadata\ApiResource;
	use ApiPlatform\Metadata\Delete;
	use ApiPlatform\Metadata\Get;
	use ApiPlatform\Metadata\GetCollection;
	use ApiPlatform\Metadata\Post;
	use ApiPlatform\Metadata\Put;
	use App\Controller\DeleteAction;
	use App\Entity\Interfaces\CreatedAtSettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Repository\StoryTextRepository;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: StoryTextRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				normalizationContext: ['groups' => ['storyTexts:read']],
			),
			new Post(),
			new Get(),
			new Put(
				security: "object.getStory().getUser() == user ||is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "object.getStory().getUser() == user ||is_granted('ROLE_ADMIN')",
			),
		
		
		],
		normalizationContext: ['groups' => ['storyText:read', 'storyTexts:read']],
		denormalizationContext: ['groups' => ['storyText:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'user' => 'exect'])]
	class StoryText implements
		CreatedAtSettableInterface,
		UpdatedAtSettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['storyTexts:read'])]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'storyTexts')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['storyTexts:read','storyText:write'])]
		private ?Story $story = null;
		
		#[ORM\Column(type: Types::TEXT)]
		#[Groups(['storyTexts:read','storyText:write'])]
		private ?string $text = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		#[Groups(['storyTexts:read'])]
		private ?\DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		#[Groups(['storyTexts:read'])]
		private ?\DateTimeInterface $updatedAt = null;
		
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
		
		
		public function getIsDeleted(): ?bool
		{
			return $this->isDeleted;
		}
		
		public function setIsDeleted(bool $isDeleted): self
		{
			$this->isDeleted = $isDeleted;
			
			return $this;
		}
	}
