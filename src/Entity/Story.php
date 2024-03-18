<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
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
	
	#[ORM\Entity(repositoryClass: StoryRepository::class)]
	#[ApiResource]
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
		private ?int $id = null;
		
		#[ORM\ManyToOne]
		private ?MediaObject $media = null;
		
		#[ORM\Column(length: 6)]
		private ?string $bgColor = null;
		
		#[ORM\OneToMany(mappedBy: 'story', targetEntity: StoryText::class)]
		private Collection $storyTexts;
		
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
