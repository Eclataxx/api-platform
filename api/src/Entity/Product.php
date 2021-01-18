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

    public function __construct($name, $price, $stock, $status)
    {
        $this->orders = new ArrayCollection([]);

        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->status = $status;
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

    public function setSubmittedBy(User $submittedBy): self
    {
        $this->submittedBy = $submittedBy;
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getValidatedBy(): Collection
    {
        return $this->validatedBy;
    }

    public function setValidatedBy(User $validatedBy): self
    {
        $this->validatedBy = $validatedBy;
        return $this;
    }
}
