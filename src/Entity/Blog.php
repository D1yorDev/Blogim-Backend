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
	use App\Repository\BlogRepository;
	use DateTimeInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Serializer\Annotation\Groups;
	use Symfony\Component\Validator\Constraints as Assert;
	
	#[ORM\Entity(repositoryClass: BlogRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				normalizationContext: ['groups' => ['blogs:read']],
			),
			new Get(),
			new Put(),
			new Get(),
			new Put(
				security: "object == user || is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "object == user || is_granted('ROLE_ADMIN')",
			),
		
		],
		normalizationContext: ['groups' => ['blog:read', 'blogs:read']],
		denormalizationContext: ['groups' => ['blog:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'nickname' => 'partial'])]
	
	class Blog
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['blogs:read'])]
		private ?int $id = null;
		
		#[ORM\ManyToOne]
		#[Groups(['blogs:read', 'blog:write'])]
		private ?MediaObject $picture = null;
		
		#[ORM\Column]
		#[Groups(['blogs:read'])]
		private ?int $followersCount = null;
		
		#[ORM\Column]
		#[Groups(['blogs:read'])]
		private ?int $followingCount = null;
		
		#[Assert\Length(min: 3, maxMessage: 255)]
		#[ORM\Column(length: 255)]
		private ?string $nickname = null;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		#[Groups(['blogs:read'])]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		#[Groups(['blogs:read'])]
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
		
		public function getPicture(): ?MediaObject
		{
			return $this->picture;
		}
		
		public function setPicture(?MediaObject $picture): self
		{
			$this->picture = $picture;
			
			return $this;
		}
		
		public function getFollowersCount(): ?int
		{
			return $this->followersCount;
		}
		
		public function setFollowersCount(int $followersCount): self
		{
			$this->followersCount = $followersCount;
			
			return $this;
		}
		
		public function getFollowingCount(): ?int
		{
			return $this->followingCount;
		}
		
		public function setFollowingCount(int $followingCount): self
		{
			$this->followingCount = $followingCount;
			
			return $this;
		}
		
		public function getNickname(): ?string
		{
			return $this->nickname;
		}
		
		public function setNickname(string $nickname): self
		{
			$this->nickname = $nickname;
			
			return $this;
		}
	}
