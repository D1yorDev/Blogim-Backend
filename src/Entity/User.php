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
use ApiPlatform\OpenApi\Model\Operation;
use App\Component\User\Dtos\RefreshTokenRequestDto;
use App\Component\User\Dtos\TokensDto;
use App\Controller\DeleteAction;
use App\Controller\UserAboutMeAction;
use App\Controller\UserAuthAction;
use App\Controller\UserAuthByRefreshTokenAction;
use App\Controller\UserChangePasswordAction;
use App\Controller\UserCreateAction;
use App\Controller\UserIsUniqueEmailAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\DeletedAtSettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['users:read']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Get(
            security: "object == user || is_granted('ROLE_ADMIN')",
        ),
        new Post(
            controller: UserCreateAction::class,
        ),
        new Put(
            denormalizationContext: ['groups' => ['user:put:write']],
            security: "object == user || is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            controller: DeleteAction::class,
            security: "object == user || is_granted('ROLE_ADMIN')",
        ),
        new Post(
            uriTemplate: 'users/about_me',
            controller: UserAboutMeAction::class,
            openapi: new Operation(
                summary: 'Shows info about the authenticated user'
            ),
            denormalizationContext: ['groups' => ['user:empty:body']],
            name: 'aboutMe',
        ),
        new Post(
            uriTemplate: 'users/auth',
            controller: UserAuthAction::class,
            openapi: new Operation(
                summary: 'Authorization'
            ),
            output: TokensDto::class,
            name: 'auth',
        ),
        new Post(
            uriTemplate: 'users/auth/refreshToken',
            controller: UserAuthByRefreshTokenAction::class,
            openapi: new Operation(
                summary: 'Authorization by refreshToken'
            ),
            input: RefreshTokenRequestDto::class,
            output: TokensDto::class,
            name: 'authByRefreshToken',
        ),
        new Post(
            uriTemplate: 'users/is_unique_email',
            controller: UserIsUniqueEmailAction::class,
            openapi: new Operation(
                summary: 'Checks email for uniqueness'
            ),
            denormalizationContext: ['groups' => ['user:isUniqueEmail:write']],
            name: 'isUniqueEmail',
        ),
        new Put(
            uriTemplate: 'users/{id}/password',
            controller: UserChangePasswordAction::class,
            openapi: new Operation(
                summary: 'Changes password'
            ),
            denormalizationContext: ['groups' => ['user:changePassword:write']],
            security: "object == user || is_granted('ROLE_ADMIN')",
            name: 'changePassword',
        ),
    ],
    normalizationContext: ['groups' => ['user:read', 'users:read']],
    denormalizationContext: ['groups' => ['user:write']],
    extraProperties: [
        'standard_put' => true,
    ],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'email' => 'partial'])]
#[UniqueEntity('email', message: 'This email is already used')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements
    UserInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    DeletedAtSettableInterface,
    PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['users:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email]
    #[Groups(['users:read', 'user:write', 'user:put:write', 'user:isUniqueEmail:write'])]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:write', 'user:changePassword:write'])]
    #[Assert\Length(min: 6, minMessage: 'Password must be at least {{ limit }} characters long')]
    private ?string $password = null;

    #[ORM\Column(type: 'array')]
    #[Groups(['user:read'])]
    private array $roles = [];

    #[ORM\Column(type: 'datetime')]
    #[Groups(['user:read'])]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['user:read'])]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deletedAt = null;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'withUser', targetEntity: Chat::class)]
    private Collection $chats;

    #[ORM\OneToMany(mappedBy: 'forUser', targetEntity: Notification::class)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'follow', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->getId();
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function deleteRole(string $role): self
    {
        $roles = $this->roles;

        foreach ($roles as $roleKey => $roleName) {
            if ($roleName === $role) {
                unset($roles[$roleKey]);
                $this->setRoles($roles);
            }
        }

        return $this;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCreatedBy($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCreatedBy() === $this) {
                $post->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setWithUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getWithUser() === $this) {
                $chat->setWithUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setForUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getForUser() === $this) {
                $notification->setForUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setFollow($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getFollow() === $this) {
                $subscription->setFollow(null);
            }
        }

        return $this;
    }
}
