<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $product;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^(DE\d{9}|IT\d{11}|GR\d{9}|FR[A-Z]{2}\d{9})$/',
        message: 'The tax number format is invalid.'
    )]
    public string $taxNumber;

    #[Assert\Type('string')]
    public ?string $couponCode = null;
}