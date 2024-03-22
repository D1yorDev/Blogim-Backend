<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Entity\Interfaces\CreatedAtSettableInterface;
	use App\Entity\Interfaces\CreatedBySettableInterface;
	use App\Entity\Interfaces\IsDeletedSettableInterface;
	use App\Entity\Interfaces\UpdatedAtSettableInterface;
	use App\Repository\PersonRepository;
	use DateTimeInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: PersonRepository::class)]
	#[ApiResource]
	class Person implements
		CreatedAtSettableInterface,
		CreatedBySettableInterface,
		UpdatedAtSettableInterface,
		IsDeletedSettableInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\Column(length: 255, nullable: true)]
		#[Groups(['comments:read', 'commentLikes:read','messages:read'])]
		private ?string $givenName = null;
		
		#[ORM\Column(length: 255, nullable: true)]
		#[Groups(['comments:read', 'commentLikes:read','messages:read'])]
		private ?string $familyName = null;
		
		#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
		private ?\DateTimeInterface $birthday = null;
		
		#[ORM\Column]
		private ?bool $isMale = null;
		
		#[ORM\Column(length: 255, nullable: true)]
		private ?string $phone = null;
		
		#[ORM\ManyToOne]
		#[ORM\JoinColumn(nullable: false)]
		private ?Country $country = null;
		
		#[ORM\Column(length: 255, nullable: true)]
		private ?string $city = null;
		
		#[ORM\ManyToOne(inversedBy: 'persons')]
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
		
		public function getGivenName(): ?string
		{
			return $this->givenName;
		}
		
		public function setGivenName(?string $givenName): self
		{
			$this->givenName = $givenName;
			
			return $this;
		}
		
		public function getFamilyName(): ?string
		{
			return $this->familyName;
		}
		
		public function setFamilyName(?string $familyName): self
		{
			$this->familyName = $familyName;
			
			return $this;
		}
		
		public function getBirthday(): ?\DateTimeInterface
		{
			return $this->birthday;
		}
		
		public function setBirthday(?\DateTimeInterface $birthday): self
		{
			$this->birthday = $birthday;
			
			return $this;
		}
		
		public function isIsMale(): ?bool
		{
			return $this->isMale;
		}
		
		public function setIsMale(bool $isMale): self
		{
			$this->isMale = $isMale;
			
			return $this;
		}
		
		public function getPhone(): ?string
		{
			return $this->phone;
		}
		
		public function setPhone(?string $phone): self
		{
			$this->phone = $phone;
			
			return $this;
		}
		
		public function getCountry(): ?Country
		{
			return $this->country;
		}
		
		public function setCountry(?Country $country): self
		{
			$this->country = $country;
			
			return $this;
		}
		
		public function getCity(): ?string
		{
			return $this->city;
		}
		
		public function setCity(?string $city): self
		{
			$this->city = $city;
			
			return $this;
		}
	}
