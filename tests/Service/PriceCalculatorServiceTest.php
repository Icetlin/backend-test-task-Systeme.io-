<?php

namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\PriceCalculatorService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

//php bin/phpunit - start test
class PriceCalculatorServiceTest extends TestCase
{
    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;
    private PriceCalculatorService $priceCalculatorService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->couponRepository = $this->createMock(CouponRepository::class);

        $this->priceCalculatorService = new PriceCalculatorService(
            $this->productRepository,
            $this->couponRepository
        );
    }

    #[DataProvider('priceCalculationProvider')]
    public function testCalculate(int $productId, string $taxNumber, ?string $couponCode, ?Product $product, ?Coupon $coupon, int $expectedPrice): void
    {
        $this->productRepository->method('find')
            ->with($productId)
            ->willReturn($product);

        if ($couponCode) {
            $this->couponRepository->method('findOneBy')
                ->with(['code' => $couponCode])
                ->willReturn($coupon);
        }

        $calculatedPrice = $this->priceCalculatorService->calculate($productId, $taxNumber, $couponCode);

        $this->assertEquals($expectedPrice, $calculatedPrice);
    }

    public static function priceCalculationProvider(): array
    {
        $product = new Product('Test Product', 10000);
        $percentCoupon = new Coupon('D15', Coupon::TYPE_PERCENTAGE, 15);
        $fixedCoupon = new Coupon('F5', Coupon::TYPE_FIXED, 500);

        return [
            'Simple case with DE tax (19%)' => [1, 'DE123456789', null, $product, null, 11900],
            'With percentage coupon and IT tax (22%)' => [1, 'IT12345678901', 'D15', $product, $percentCoupon, 10370],
            'With fixed coupon and FR tax (20%)' => [1, 'FRYY123456789', 'F5', $product, $fixedCoupon, 11400],
            'With percentage coupon and GR tax (24%)' => [1, 'GR123456789', 'D15', $product, $percentCoupon, 10540],
        ];
    }

    public function testCalculateThrowsExceptionForInvalidProduct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Product not found.');

        $this->productRepository->method('find')
            ->with(999)
            ->willReturn(null);

        $this->priceCalculatorService->calculate(999, 'DE123456789', null);
    }

    public function testCalculateThrowsExceptionForInvalidCoupon(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Coupon not found.');

        $this->productRepository->method('find')
            ->with(1)
            ->willReturn(new Product('Test Product', 10000));

        $this->couponRepository->method('findOneBy')
            ->with(['code' => 'INVALID'])
            ->willReturn(null);

        $this->priceCalculatorService->calculate(1, 'DE123456789', 'INVALID');
    }
} 