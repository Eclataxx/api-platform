<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     subresourceOperations={
 *      "api_users_orders_get_subresource"={
 *          "method"="GET",
 *          "normalization_context"={"groups"={"user_get_orders"}}
 *      }
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"order_get"}}
 *          },
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"order_get"}}
 *          },
 *          "delete",
 *          "put",
 *          "patch"
 *     },
 * )
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"order_get", "user_get_item", "user_get_orders"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"order_get", "user_get_item", "user_get_orders"})
     */
    private $price;

    /**
     * @ORM\Column(type="date")
     * @Groups({"order_get", "user_get_item", "user_get_orders"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order_get", "user_get_item", "user_get_orders"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"order_get", "user_get_orders"})
     */
    private $associatedUser;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="orders")
     * @Groups({"order_get", "user_get_item", "user_get_orders"})
     */
    private $products;

    public function __construct($date, $status = "ORDERED")
    {
        $this->products = new ArrayCollection();
        $this->date = $date;
        $this->status = $status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function calculatePrice(): int
    {
        $allPrice = $this->getProducts()->map(function($product) {
            return $product->getPrice();
        })->toArray();
        return array_sum($allPrice);
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getAssociatedUser(): ?User
    {
        return $this->associatedUser;
    }

    public function setAssociatedUser(?User $associatedUser): self
    {
        $this->associatedUser = $associatedUser;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $this->setPrice($this->calculatePrice());
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $this->setPrice($this->calculatePrice());
        }

        return $this;
    }
}
