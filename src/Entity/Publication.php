<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Entity\Interfaces\CreatedAtSettableInterface;
	use App\Entity\Interfaces\CreatedBySettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Entity\Interfaces\UpdatedBySettableInterface;
	use App\Repository\PublicationRepository;
	use DateTimeInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	#[ORM\Entity(repositoryClass: PublicationRepository::class)]
	#[ApiResource]
	class Publication implements
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
		
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $text = null;
		
		#[ORM\Column]
		private ?int $likesCount = null;
		
		#[ORM\ManyToOne(inversedBy: 'publications')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\ManyToOne(inversedBy: 'publications')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $updatedBy = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
		#[ORM\OneToMany(mappedBy: 'publication', targetEntity: PublicationLike::class)]
		private Collection $likes;
		
		#[ORM\OneToMany(mappedBy: 'publication', targetEntity: Comment::class)]
		private Collection $comments;
		
		#[ORM\Column]
		private ?int $commentsCount = null;
		
		public function __construct()
		{
			$this->likes = new ArrayCollection();
			$this->comments = new ArrayCollection();
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
		
		public function getText(): ?string
		{
			return $this->text;
		}
		
		public function setText(?string $text): self
		{
			$this->text = $text;
			
			return $this;
		}
		
		public function getLikesCount(): ?int
		{
			return $this->likesCount;
		}
		
		public function setLikesCount(int $likesCount): self
		{
			$this->likesCount = $likesCount;
			
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
		
		/**
		 * @return Collection<int, PublicationLike>
		 */
		public function getLikes(): Collection
		{
			return $this->likes;
		}
		
		public function addLike(PublicationLike $like): self
		{
			if (!$this->likes->contains($like)) {
				$this->likes->add($like);
				$like->setPost($this);
			}
			
			return $this;
		}
		
		public function removeLike(PublicationLike $like): self
		{
			if ($this->likes->removeElement($like)) {
				// set the owning side to null (unless already changed)
				if ($like->getPost() === $this) {
					$like->setPost(null);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return Collection<int, Comment>
		 */
		public function getComments(): Collection
		{
			return $this->comments;
		}
		
		public function addComment(Comment $comment): self
		{
			if (!$this->comments->contains($comment)) {
				$this->comments->add($comment);
				$comment->setPublication($this);
			}
			
			return $this;
		}
		
		public function removeComment(Comment $comment): self
		{
			if ($this->comments->removeElement($comment)) {
				// set the owning side to null (unless already changed)
				if ($comment->getPublication() === $this) {
					$comment->setPublication(null);
				}
			}
			
			return $this;
		}
		
		public function getCommentsCount(): ?int
		{
			return $this->commentsCount;
		}
		
		public function setCommentsCount(int $commentsCount): self
		{
			$this->commentsCount = $commentsCount;
			
			return $this;
		}
	}
