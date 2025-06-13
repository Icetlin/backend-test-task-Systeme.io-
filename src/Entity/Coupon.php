<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENTAGE = 'percentage';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $code;

    #[ORM\Column(length: 255)]
    private string $type;

    #[ORM\Column]
    private int $discount;

    public function __construct(string $code, string $type, int $discount)
    {
        $this->code = $code;
        $this->type = $type;
        $this->discount = $discount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }
} 