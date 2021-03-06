<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_item"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_item"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_item"})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_item"})
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_item"})
     */
    private $streetAddress;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_get_item"})
     */
    private $additionalStreetAddress;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="address")
     */
    private $associatedUser;

    public function __construct($city = "", $additionalStreetAddress = "", $country = "", $postalCode = "", $state = "", $streetAddress = "")
    {
        $this->city = $city;
        $this->additionalStreetAddress = $additionalStreetAddress;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->state = $state;
        $this->streetAddress = $streetAddress;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getAdditionalStreetAddress(): ?string
    {
        return $this->additionalStreetAddress;
    }

    public function setAdditionalStreetAddress(?string $additionalStreetAddress): self
    {
        $this->additionalStreetAddress = $additionalStreetAddress;

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
}
