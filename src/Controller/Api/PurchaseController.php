<?php

namespace App\Controller\Api;

use App\Dto\CalculatePriceRequest;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\PriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/calculate-price', name: 'api_calculate_price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload] CalculatePriceRequest $request,
        ProductRepository $productRepository,
        CouponRepository $couponRepository,
        PriceCalculatorService $priceCalculator
    ): JsonResponse
    {

        $product = $productRepository->find($request->product);;
        if ($product === null) {
            return $this->json(['error' => 'Product not found'], 400);
        }

        $coupon = null;
        if ($request->couponCode !== null) {
            $coupon = $couponRepository->findOneBy(['code' => $request->couponCode]);
            if ($coupon === null) {
                return $this->json(['error' => 'Coupon not found'], 400);
            }
        }

        $finalPrice = $priceCalculator->calculate(
            product: $product,
            coupon: $coupon,
            taxNumber: $request->taxNumber
        );

        return $this->json(['price' => $finalPrice]);
    }
}