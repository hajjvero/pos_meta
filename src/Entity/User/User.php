<?php

namespace App\Entity\User;

use App\Entity\Order\Order;
use App\Repository\User\UserRepository;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(name: 'user_name_username_email_idx', columns: ['meta_key'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestamp;

    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique:  true)]
    #[Assert\Unique]
    private ?string $username = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Unique]
    private ?string $email = null;

    #[ORM\Column(length: 30, unique: true, nullable: true)]
    #[Assert\Unique]
    private ?string $phone = null;

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $lastLogin = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\OneToMany(targetEntity: UserMeta::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $userMetas;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'cashier')]
    private Collection $orders;

    // ============================================================================
    // Constructor
    // ============================================================================

    public function __construct()
    {
        $this->userMetas = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->userMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->userMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new UserMeta();
            $meta->setUser($this);
            $meta->setMetaKey($key);
            $this->addUserMeta($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->userMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removeUserMeta($meta);
                break;
            }
        }
        return $this;
    }

    // ============================================================================
    // Getters and Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    // ============================== Relationships ================================
    // =============================================================================

    /**
     * @return Collection<int, UserMeta>
     */
    public function getUserMetas(): Collection
    {
        return $this->userMetas;
    }

    public function addUserMeta(UserMeta $userMeta): static
    {
        if (!$this->userMetas->contains($userMeta)) {
            $this->userMetas->add($userMeta);
            $userMeta->setUser($this);
        }

        return $this;
    }

    public function removeUserMeta(UserMeta $userMeta): static
    {
        if ($this->userMetas->removeElement($userMeta)) {
            // set the owning side to null (unless already changed)
            if ($userMeta->getUser() === $this) {
                $userMeta->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCashier($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCashier() === $this) {
                $order->setCashier(null);
            }
        }

        return $this;
    }

    // ============================================================================
    // Implemented methods
    // ============================================================================

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }
}
