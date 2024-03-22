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
	use App\Repository\CountryRepository;
	use DateTimeInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: CountryRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				normalizationContext: ['groups' => ['countries:read']],
			),
			new Get(),
			new Put(
				security: "is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "is_granted('ROLE_ADMIN')",
			),
			new Post(
				security: 'is_granted("ROLE_ADMIN")'
			)
		
		],
		normalizationContext: ['groups' => ['country:read', 'countries:read']],
		denormalizationContext: ['groups' => ['country:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'name'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial'])]
	class Country implements
		CreatedAtSettableInterface,
		CreatedBySettableInterface,
		UpdatedAtSettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['countries:read'])]
		private ?int $id = null;
		
		#[ORM\Column(length: 255)]
		#[Groups(['countries:read', 'country:write'])]
		private ?string $name = null;
		
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['countries:read'])]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		#[Groups(['countries:read'])]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		#[Groups(['countries:read'])]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
		public function getCreatedBy(): ?User
		{
			return $this->createdBy;
		}
		
		/**
		 * @param UserInterface|null $createdBy
		 * @return $this
		 */
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
		
		public function getName(): ?string
		{
			return $this->name;
		}
		
		public function setName(string $name): self
		{
			$this->name = $name;
			
			return $this;
		}
	}
