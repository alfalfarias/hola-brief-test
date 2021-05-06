<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $discount;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="order", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity=OrderCoupon::class, mappedBy="order", cascade={"persist", "remove"})
     */
    private $coupon;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(OrderProduct $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrder($this);
        }

        return $this;
    }

    public function removeProduct(OrderProduct $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrder() === $this) {
                $product->setOrder(null);
            }
        }

        return $this;
    }

    public function getCoupon(): ?OrderCoupon
    {
        return $this->coupon;
    }

    public function setCoupon(OrderCoupon $coupon): self
    {
        // set the owning side of the relation if necessary
        if ($coupon->getOrder() !== $this) {
            $coupon->setOrder($this);
        }

        $this->coupon = $coupon;

        return $this;
    }
}
