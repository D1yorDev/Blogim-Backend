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
	use App\Repository\ChatRepository;
	use DateTimeInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Serializer\Annotation\Groups;
	
	#[ORM\Entity(repositoryClass: ChatRepository::class)]
	#[ApiResource(
		operations: [
			new GetCollection(
				normalizationContext: ['groups' => ['chats:read']],
			),
			new Get(
				security: "object == user || is_granted('ROLE_ADMIN')",
			),
			new Put(
				security : "object == user || is_granted('ROLE_ADMIN')",
			),
			new Delete(
				controller: DeleteAction::class,
				security: "object == user || is_granted('ROLE_ADMIN')",
			),
			new Post()
		
		],
		normalizationContext: ['groups' => ['chat:read', 'chats:read']],
		denormalizationContext: ['groups' => ['chat:write']],
	
	)]
	#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact'])]
	class Chat
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		#[Groups(['chats:read'])]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'chats')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['chats:read', 'chat:write'])]
		private ?User $withUser = null;
		
		#[ORM\OneToMany(mappedBy: 'chat', targetEntity: Message::class)]
		private Collection $messages;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		#[Groups(['chat:read'])]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		#[Groups(['chat:read'])]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		#[Groups(['chat:read'])]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
		public function __construct()
		{
			$this->messages = new ArrayCollection();
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
		
		public function getWithUser(): ?User
		{
			return $this->withUser;
		}
		
		public function setWithUser(?User $withUser): self
		{
			$this->withUser = $withUser;
			
			return $this;
		}
		
		/**
		 * @return Collection<int, Message>
		 */
		public function getMessages(): Collection
		{
			return $this->messages;
		}
		
		public function addMessage(Message $message): self
		{
			if (!$this->messages->contains($message)) {
				$this->messages->add($message);
				$message->setChat($this);
			}
			
			return $this;
		}
		
		public function removeMessage(Message $message): self
		{
			if ($this->messages->removeElement($message)) {
				// set the owning side to null (unless already changed)
				if ($message->getChat() === $this) {
					$message->setChat(null);
				}
			}
			
			return $this;
		}
	}
