<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Cart;
use App\Entity\Address;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Controller\UserOrderCart;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"user_get_collection"}}
 *          },
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"user_get_item"}, "enable_max_depth"=true}
 *          },
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *          "put"={"security"="is_granted('ROLE_ADMIN')"},
 *          "patch"={"security"="is_granted('ROLE_ADMIN')"},
 *          "post_user_order"={
 *              "method"="POST",
 *              "path"="/users/{id}/order",
 *              "controller"=UserOrderCart::class
 *          }
 *     },
 * )
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
     * @Groups({"user_get_collection", "user_get_item"})
     * @MaxDepth(1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"product_get", "order_get", "user_get_collection", "user_get_item", "user_get_orders", "user_get_cart"})
     * @MaxDepth(2)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_get_item", "user_get_collection"})
     * @MaxDepth(1)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user_get_item"})
     * @MaxDepth(1)
     */
    private $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_collection", "user_get_item"})
     * @MaxDepth(1)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, inversedBy="associatedUser", cascade={"persist", "remove"})
     * @Groups({"user_get_item"})
     * @MaxDepth(1)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="associatedUser", cascade={"persist", "remove"})
     * @Groups({"user_get_item"})
     * @ORM\JoinColumn(name="associatedUser", referencedColumnName="id", onDelete="cascade")
     * @ApiSubresource()
     * @MaxDepth(1)
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="submittedBy", cascade={"persist", "remove"})
     * @Groups({"user_get_item", "user_get_collection"})
     * @ORM\JoinColumn(name="submittedBy", referencedColumnName="id", onDelete="cascade")
     * @ApiSubresource()
     * @MaxDepth(1)
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="validatedBy", cascade={"persist", "remove"})
     * @Groups({"user_get_item"})
     * @ApiSubresource()
     * @MaxDepth(1)
     */
    private $validatedProducts;

    /**
     * @ORM\OneToOne(targetEntity=Cart::class, inversedBy="relatedUser", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $cart;

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->validatedProducts = new ArrayCollection();
        $this->roles = [self::ROLE_USER];
        $this->setCart(new Cart());
        $this->setAddress(new Address());
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

    public function getPlainPassword(): string
    {
        return (string)$this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
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

    /**
     * @return Address || null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setAssociatedUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getAssociatedUser() === $this) {
                $order->setAssociatedUser(null);
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

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}
