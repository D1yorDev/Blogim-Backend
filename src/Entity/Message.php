<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use App\Repository\MessageRepository;
	use DateTimeInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	#[ORM\Entity(repositoryClass: MessageRepository::class)]
	#[ApiResource]
	class Message
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\ManyToOne(inversedBy: 'messages')]
		#[ORM\JoinColumn(nullable: false)]
		private ?Chat $chat = null;
		
		#[ORM\Column(type: Types::SMALLINT)]
		private ?int $type = null;
		
		#[ORM\OneToMany(mappedBy: 'message', targetEntity: MediaMessage::class)]
		private Collection $mediaMessages;
		
		#[ORM\OneToMany(mappedBy: 'message', targetEntity: TextMessage::class)]
		private Collection $textMessages;
		
		#[ORM\ManyToOne(inversedBy: 'posts')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $createdBy = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
		private ?DateTimeInterface $createdAt = null;
		
		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
		private ?DateTimeInterface $updatedAt = null;
		
		#[ORM\Column(type: 'boolean')]
		private ?bool $isDeleted = false;
		
		public function __construct()
		{
			$this->mediaMessages = new ArrayCollection();
			$this->textMessages = new ArrayCollection();
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
		
		public function getChat(): ?Chat
		{
			return $this->chat;
		}
		
		public function setChat(?Chat $chat): self
		{
			$this->chat = $chat;
			
			return $this;
		}
		
		public function getType(): ?int
		{
			return $this->type;
		}
		
		public function setType(int $type): self
		{
			$this->type = $type;
			
			return $this;
		}
		
		/**
		 * @return Collection<int, MediaMessage>
		 */
		public function getMediaMessages(): Collection
		{
			return $this->mediaMessages;
		}
		
		public function addMediaMessage(MediaMessage $mediaMessage): self
		{
			if (!$this->mediaMessages->contains($mediaMessage)) {
				$this->mediaMessages->add($mediaMessage);
				$mediaMessage->setMessage($this);
			}
			
			return $this;
		}
		
		public function removeMediaMessage(MediaMessage $mediaMessage): self
		{
			if ($this->mediaMessages->removeElement($mediaMessage)) {
				// set the owning side to null (unless already changed)
				if ($mediaMessage->getMessage() === $this) {
					$mediaMessage->setMessage(null);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return Collection<int, TextMessage>
		 */
		public function getTextMessages(): Collection
		{
			return $this->textMessages;
		}
		
		public function addTextMessage(TextMessage $textMessage): self
		{
			if (!$this->textMessages->contains($textMessage)) {
				$this->textMessages->add($textMessage);
				$textMessage->setMessage($this);
			}
			
			return $this;
		}
		
		public function removeTextMessage(TextMessage $textMessage): self
		{
			if ($this->textMessages->removeElement($textMessage)) {
				// set the owning side to null (unless already changed)
				if ($textMessage->getMessage() === $this) {
					$textMessage->setMessage(null);
				}
			}
			
			return $this;
		}
	}
