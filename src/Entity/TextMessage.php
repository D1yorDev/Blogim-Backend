<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Repository\TextMessageRepository;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: TextMessageRepository::class)]
	#[ApiResource]
	class TextMessage
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['messages:read'])]
		private ?int $id = null;
		
		#[ORM\Column(type: Types::TEXT)]
		#[Groups(['messages:read'])]
		private ?string $text = null;
		
		#[ORM\ManyToOne(inversedBy: 'textMessages')]
		#[ORM\JoinColumn(nullable: false)]
		private ?Message $message = null;
		
		public function getId(): ?int
		{
			return $this->id;
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
		
		public function getMessage(): ?Message
		{
			return $this->message;
		}
		
		public function setMessage(?Message $message): self
		{
			$this->message = $message;
			
			return $this;
		}
	}
