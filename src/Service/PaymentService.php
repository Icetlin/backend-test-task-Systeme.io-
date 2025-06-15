<?php

namespace App\Service;

use App\Dto\PurchaseRequest;
use App\Service\Payment\PaymentProcessorFactory;
use Exception;

class PaymentService
{
    private PriceCalculatorService $priceCalculator;
    private PaymentProcessorFactory $paymentProcessorFactory;

    public function __construct(
        PriceCalculatorService $priceCalculator,
        PaymentProcessorFactory $paymentProcessorFactory
    ) {
        $this->priceCalculator = $priceCalculator;
        $this->paymentProcessorFactory = $paymentProcessorFactory;
    }

    /**
     * @throws Exception
     */
    public function purchase(PurchaseRequest $purchaseRequest): void
    {
        $totalPrice = $this->priceCalculator->calculate(
            $purchaseRequest->product,
            $purchaseRequest->taxNumber,
            $purchaseRequest->couponCode
        );

        $paymentProcessor = $this->paymentProcessorFactory->getProcessor($purchaseRequest->paymentProcessor);

        $paymentProcessor->pay($totalPrice);
    }
} 