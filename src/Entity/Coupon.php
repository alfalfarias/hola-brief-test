<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CouponRepository::class)
 * @UniqueEntity("code")
 */
class Coupon
{
    const TYPE = [
        'PRICE_FIXED' => 'PRICE_FIXED',
        'PRICE_PERCENT' => 'PRICE_PERCENT',
    ];
    
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="El código es requerido")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="El precio es requerido")
     * @Assert\Positive
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=CouponRule::class, mappedBy="coupon", orphanRemoval=true)
     */
    private $rules;

    public function __construct()
    {
        $this->coupons = new ArrayCollection();
        $this->rules = new ArrayCollection();
    }

    public function getId(): ?string
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
     * @return Collection|CouponRule[]
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(CouponRule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setCoupon($this);
        }

        return $this;
    }

    public function removeRule(CouponRule $rule): self
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getCoupon() === $this) {
                $rule->setCoupon(null);
            }
        }

        return $this;
    }
}
