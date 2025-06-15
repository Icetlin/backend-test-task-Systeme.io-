<?php

namespace App\Controller\Api;

use App\Dto\CalculatePriceRequest;
use App\Dto\PurchaseRequest;
use App\Service\PaymentService;
use App\Service\PriceCalculatorService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/calculate-price', name: 'api_calculate_price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload] CalculatePriceRequest $request,
        PriceCalculatorService $priceCalculator
    ): JsonResponse
    {
        try {
            $finalPrice = $priceCalculator->calculate(
                $request->product,
                $request->taxNumber,
                $request->couponCode
            );
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }


        return $this->json(['price' => $finalPrice]);
    }

    #[Route('/purchase', name: 'api_purchase', methods: ['POST'])]
    public function purchase(
        #[MapRequestPayload] PurchaseRequest $request,
        PaymentService $paymentService
    ): JsonResponse
    {
        try {
            $paymentService->purchase($request);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json(['message' => 'Purchase successful']);
    }
}