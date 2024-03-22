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
	use App\Repository\CommentRepository;
	use DateTimeInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: CommentRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				normalizationContext: ['groups' => ['comments:read']],
			),
			new Get(
				security: "object.getUser() == user || is_granted('ROLE_ADMIN')",
			),
			new Put(
				security: "object.getUser() == user || is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "object.getUser() == user || is_granted('ROLE_ADMIN')",
			),
			new Post()
		
		],
		normalizationContext: ['groups' => ['comment:read', 'comments:read']],
		denormalizationContext: ['groups' => ['comment:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact','publication.id' => 'exact'])]
	
	class Comment implements
		CreatedAtSettableInterface,
		CreatedBySettableInterface,
		UpdatedAtSettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['comments:read'])]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'comments')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['comments:read', 'comment:write'])]
		private ?Publication $publication = null;
		
		#[ORM\Column(type: Types::TEXT)]
		#[Groups(['comments:read','comment:write'])]
		private ?string $text = null;
		
		#[ORM\OneToMany(mappedBy: 'comment', targetEntity: CommentLike::class)]
		#[Groups(['comments:read'])]
		private Collection $likes;
		
		#[ORM\Column]
		#[Groups(['comments:read'])]
		private ?int $likesCount = null;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['comments:read'])]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		#[Groups(['comments:read'])]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		#[Groups(['comments:read'])]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
		public function __construct()
		{
			$this->likes = new ArrayCollection();
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
		
		public function getPublication(): ?Publication
		{
			return $this->publication;
		}
		
		public function setPublication(?Publication $publication): self
		{
			$this->publication = $publication;
			
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
		
		/**
		 * @return Collection<int, CommentLike>
		 */
		public function getLikes(): Collection
		{
			return $this->likes;
		}
		
		public function addLike(CommentLike $like): self
		{
			if (!$this->likes->contains($like)) {
				$this->likes->add($like);
				$like->setComment($this);
			}
			
			return $this;
		}
		
		public function removeLike(CommentLike $like): self
		{
			if ($this->likes->removeElement($like)) {
				// set the owning side to null (unless already changed)
				if ($like->getComment() === $this) {
					$like->setComment(null);
				}
			}
			
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
	}
