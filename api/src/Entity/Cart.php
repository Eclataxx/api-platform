<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     subresourceOperations={
 *      "api_users_cart_get_subresource"={
 *          "method"="GET",
 *          "normalization_context"={"groups"={"user_get_cart"}}
 *      }
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"cart_get"}}
 *          },
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"cart_get"}}
 *          },
 *          "delete",
 *          "put",
 *          "patch"
 *     },
 * )
 * @ORM\Entity(repositoryClass=CartRepository::class)
 * @ORM\Table(name="`cart`")
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cart_get", "user_get_item", "user_get_cart"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"cart_get", "user_get_item", "user_get_cart"})
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="carts")
     * @Groups({"cart_get", "user_get_item", "user_get_cart"})
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="cart", cascade={"persist", "remove"})
     * @Groups({"cart_get", "user_get_cart"})
     */
    private $relatedUser;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->price = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function calculatePrice(): int
    {
        $allPrice = $this->getProducts()->map(function($product) {
            return $product->getPrice();
        })->toArray();
        return array_sum($allPrice);
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $this->setPrice($this->calculatePrice());
        }

        return $this;
    }

    public function getRelatedUser(): ?User
    {
        return $this->relatedUser;
    }

    public function setRelatedUser(User $relatedUser): self
    {
        $this->relatedUser = $relatedUser;

        // set the owning side of the relation if necessary
        if ($relatedUser->getCart() !== $this) {
            $relatedUser->setCart($this);
        }

        return $this;
    }
}
