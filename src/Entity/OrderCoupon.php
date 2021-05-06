<?php

namespace App\Entity;

use App\Repository\OrderCouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderCouponRepository::class)
 */
class OrderCoupon
{
    const TYPE = [
        'PRICE_FIXED' => 'PRICE_FIXED',
        'PRICE_PERCENT' => 'PRICE_PERCENT',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=OrderCouponRule::class, mappedBy="coupon", orphanRemoval=true)
     */
    private $rules;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, inversedBy="coupon", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|OrderCouponRule[]
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(OrderCouponRule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setCoupon($this);
        }

        return $this;
    }

    public function removeRule(OrderCouponRule $rule): self
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getCoupon() === $this) {
                $rule->setCoupon(null);
            }
        }

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}
