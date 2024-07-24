<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceDto
{

    public function __construct(
        #[Assert\NotBlank]
        private ?int $product,
        #[Assert\NotBlank]
        #[Assert\Regex('/^(DE|IT|GR|FR[A-Z]{2})\d+$/')]
        private ?string $taxNumber,
        #[Assert\NotBlank]
        #[Assert\Regex('/^D\d+$/')]
        private ?string $couponCode,
    )
    {
    }

    public function getProduct(): ?int
    {
        return $this->product;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }
}
