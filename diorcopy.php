#[ORM\ManyToOne(inversedBy: 'posts')]
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

normalizationContext: ['groups' => ['blog:read', 'blogs:read']],
denormalizationContext: ['groups' => ['blog:write']],
