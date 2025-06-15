<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequest
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

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['paypal', 'stripe'], message: 'Choose a valid payment processor.')]
    public string $paymentProcessor;
} 