<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="products")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     */
    private $submittedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="validatedProduct")
     */
    private $validatedBy;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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
     * @return Collection|User[]
     */
    public function getSubmittedBy(): Collection
    {
        return $this->submittedBy;
    }

    public function addSubmittedBy(User $submittedBy): self
    {
        if (!$this->submittedBy->contains($submittedBy)) {
            $this->submittedBy[] = $submittedBy;
            $submittedBy->setProducts($this);
        }

        return $this;
    }

    public function removeSubmittedBy(User $submittedBy): self
    {
        if ($this->submittedBy->contains($submittedBy)) {
            $this->submittedBy->removeElement($submittedBy);
            // set the owning side to null (unless already changed)
            if ($submittedBy->getProducts() === $this) {
                $submittedBy->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getValidatedBy(): Collection
    {
        return $this->validatedBy;
    }

    public function addValidatedBy(User $validatedBy): self
    {
        if (!$this->validatedBy->contains($validatedBy)) {
            $this->validatedBy[] = $validatedBy;
            $validatedBy->setValidatedProduct($this);
        }

        return $this;
    }

    public function removeValidatedBy(User $validatedBy): self
    {
        if ($this->validatedBy->contains($validatedBy)) {
            $this->validatedBy->removeElement($validatedBy);
            // set the owning side to null (unless already changed)
            if ($validatedBy->getValidatedProduct() === $this) {
                $validatedBy->setValidatedProduct(null);
            }
        }

        return $this;
    }
}
