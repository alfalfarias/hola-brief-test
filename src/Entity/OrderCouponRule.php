<?php

namespace App\Entity;

use App\Repository\OrderCouponRuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderCouponRuleRepository::class)
 */
class OrderCouponRule
{
    const TYPE = [
        'PRICE_MIN' => 'PRICE_MIN',
        'PRICE_MAX' => 'PRICE_MAX',
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
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=OrderCoupon::class, inversedBy="rules")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $coupon;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCoupon(): ?OrderCoupon
    {
        return $this->coupon;
    }

    public function setCoupon(?OrderCoupon $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }
}
