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
	use App\Entity\Interfaces\CreatedBySettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Entity\Interfaces\UpdatedBySettableInterface;
	use App\Repository\StoryRepository;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: StoryRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				normalizationContext: ['groups' => ['stories:read']],
			),
			new Post(),
			new Get(),
			new Put(
				security: "object.getUser() == user ||is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "object.getUser() == user ||is_granted('ROLE_ADMIN')",
			),
		
		
		],
		normalizationContext: ['groups' => ['story:read', 'stories:read']],
		denormalizationContext: ['groups' => ['story:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'user' => 'exect'])]
	class Story implements
		CreatedAtSettableInterface,
		CreatedBySettableInterface,
		UpdatedAtSettableInterface,
		UpdatedBySettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['stories:read'])]
		private ?int $id = null;
		
		#[ORM\ManyToOne]
		#[Groups(['stories:read','story:write'])]
		private ?MediaObject $media = null;
		
		#[ORM\Column(length: 6)]
		#[Groups(['stories:read','story:write'])]
		private ?string $bgColor = null;
		
		#[ORM\OneToMany(mappedBy: 'story', targetEntity: StoryText::class)]
		#[Groups(['stories:read','story:write'])]
		private Collection $storyTexts;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['stories:read','story:write'])]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		#[Groups(['stories:read'])]
		private ?\DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		#[Groups(['stories:read'])]
		private ?\DateTimeInterface $updatedAt = null;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['stories:read'])]
		private ?User $updatedBy = null;
		
		#[ORM\Column]
		private ?bool $isDeleted = false;
		
		public function __construct()
		{
			$this->storyTexts = new ArrayCollection();
		}
		
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
		
		public function getBgColor(): ?string
		{
			return $this->bgColor;
		}
		
		public function setBgColor(string $bgColor): self
		{
			$this->bgColor = $bgColor;
			
			return $this;
		}
		
		/**
		 * @return Collection<int, StoryText>
		 */
		public function getStoryTexts(): Collection
		{
			return $this->storyTexts;
		}
		
		public function addStoryText(StoryText $storyText): self
		{
			if (!$this->storyTexts->contains($storyText)) {
				$this->storyTexts->add($storyText);
				$storyText->setStory($this);
			}
			
			return $this;
		}
		
		public function removeStoryText(StoryText $storyText): self
		{
			if ($this->storyTexts->removeElement($storyText)) {
				// set the owning side to null (unless already changed)
				if ($storyText->getStory() === $this) {
					$storyText->setStory(null);
				}
			}
			
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
