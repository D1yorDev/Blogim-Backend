<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Repository\PersonRepository;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	
	#[ORM\Entity(repositoryClass: PersonRepository::class)]
	#[ApiResource]
	class Person
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\Column(length: 255, nullable: true)]
		private ?string $givenName = null;
		
		#[ORM\Column(length: 255, nullable: true)]
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
