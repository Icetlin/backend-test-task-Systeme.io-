<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\Country;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use InvalidArgumentException;

class PriceCalculatorService
{
    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;

    public function __construct(ProductRepository $productRepository, CouponRepository $couponRepository)
    {
        $this->productRepository = $productRepository;
        $this->couponRepository = $couponRepository;
    }

    public function calculate(
        int $productId, 
        string $taxNumber, 
        ?string $couponCode
    ): int
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new InvalidArgumentException('Product not found.');
        }

        $coupon = null;
        if ($couponCode) {
            $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);
            if (!$coupon) {
                throw new InvalidArgumentException('Coupon not found.');
            }
        }

        $basePrice = $product->getPrice();

        $priceAfterCoupon = $this->calculatePriceAfterCoupon(basePrice: $basePrice, coupon: $coupon);
        $validPriceAfterCoupon = max(0, $priceAfterCoupon);

        $taxAmount = $this->calculateTaxAmount(price: $validPriceAfterCoupon, taxNumber: $taxNumber);

        $finalPrice = $validPriceAfterCoupon + $taxAmount;
        
        return (int) round($finalPrice);
    }

    private function calculatePriceAfterCoupon(int $basePrice, ?Coupon $coupon): float
    {
        if ($coupon === null) {
            return $basePrice;
        }
        
        return match ($coupon->getType()) {
            Coupon::TYPE_PERCENTAGE => $basePrice - (($basePrice * $coupon->getDiscount()) / 100),
            Coupon::TYPE_FIXED => $basePrice - $coupon->getDiscount(),
            default => $basePrice,
        };
    }

    private function calculateTaxAmount(float $price, string $taxNumber): float
    {
        $countryCode = substr($taxNumber, 0, 2);
        $country = Country::tryFromCode($countryCode);

        if ($country === null) {
            throw new InvalidArgumentException('Invalid tax number country code');
        }

        $taxRate = $country->getTaxRate();
        
        return ($price * $taxRate) / 100;
    }
}