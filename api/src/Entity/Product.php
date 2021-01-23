<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"product_get"}}
 *          },
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"product_get"}}
 *          },
 *          "delete",
 *          "put",
 *          "patch"
 *     },
 *
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"product_get", "user_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product_get", "order_get", "user_get_item"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"product_get", "order_get", "user_get_item"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product_get"})
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="products")
     * @Groups({"product_get"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @Groups({"product_get", "order_get", "user_get_item"})
     */
    private $submittedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="validatedProducts")
     * @Groups({"product_get"})
     */
    private $validatedBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"product_get"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Cart::class, mappedBy="products")
     */
    private $carts;

    public function __construct($name, $description, $price, $status)
    {
        $this->orders = new ArrayCollection([]);

        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->status = $status;
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
            $order->addProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            $order->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getSubmittedBy(): User
    {
        return $this->submittedBy;
    }

    public function setSubmittedBy(User $submittedBy): self
    {
        $this->submittedBy = $submittedBy;
        return $this;
    }

    /**
     * @return User
     */
    public function getValidatedBy(): User
    {
        return $this->validatedBy;
    }

    public function setValidatedBy(User $validatedBy): self
    {
        $this->validatedBy = $validatedBy;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->addProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            $cart->removeProduct($this);
        }

        return $this;
    }
}
