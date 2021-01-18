<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SELLER = 'ROLE_SELLER';
    public const ROLE_USER = 'ROLE_USER';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="associatedUser")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="associatedUser", orphanRemoval=true)
     */
    private $orderId;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="submittedBy")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="validatedBy")
     */
    private $validatedProducts;

    public function __construct($username = NULL, $email = NULL, $password = NULL, $birthdate = NULL, $phoneNumber = NULL)
    {
        $this->address = new ArrayCollection();
        $this->orderId = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->validatedProducts = new ArrayCollection();
        $this->roles = [self::ROLE_USER];

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->phoneNumber = $phoneNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setAssociatedUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getAssociatedUser() === $this) {
                $address->setAssociatedUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrderId(): Collection
    {
        return $this->orderId;
    }

    public function addOrderId(Order $orderId): self
    {
        if (!$this->orderId->contains($orderId)) {
            $this->orderId[] = $orderId;
            $orderId->setAssociatedUser($this);
        }

        return $this;
    }

    public function removeOrderId(Order $orderId): self
    {
        if ($this->orderId->contains($orderId)) {
            $this->orderId->removeElement($orderId);
            // set the owning side to null (unless already changed)
            if ($orderId->getAssociatedUser() === $this) {
                $orderId->setAssociatedUser(null);
            }
        }

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setSubmittedBy($this);
        }

        return $this;
    }

    public function getValidatedProducts(): Collection
    {
        return $this->validatedProducts;
    }

    public function addValidatedProduct(Product $product): self
    {
        if (in_array(self::ROLE_ADMIN, $this->getRoles())) {
            if (!$this->validatedProducts->contains($product)) {
                $this->validatedProducts[] = $product;
                $product->setValidatedBy($this);
            }
        }

        return $this;
    }
}
