<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
	use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
	use ApiPlatform\Metadata\ApiFilter;
	use ApiPlatform\Metadata\ApiResource;
	use ApiPlatform\Metadata\Delete;
	use ApiPlatform\Metadata\Get;
	use ApiPlatform\Metadata\Put;
	use App\Controller\DeleteAction;
	use App\Repository\TextMessageRepository;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: TextMessageRepository::class)]
	#[ApiResource(
		operations: [
			
			new Get(
				security: "object.getMessage().getUser() == user || is_granted('ROLE_ADMIN')",
			),
			new Put(
				security: "object.getMessage().getUser() == user || is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "object.getMessage().getUser() == user || is_granted('ROLE_ADMIN')",
			),
		
		
		],
		normalizationContext: ['groups' => ['mediaMessage:read', 'mediaMessages:read']],
		denormalizationContext: ['groups' => ['mediaMessage:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact'])] #[ApiResource]
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
