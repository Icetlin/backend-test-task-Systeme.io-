<?php

namespace App\Service\Payment\Adapter;

use App\Service\Payment\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripeAdapter implements PaymentProcessorInterface
{
    private StripePaymentProcessor $processor;

    public function __construct()
    {
        $this->processor = new StripePaymentProcessor();
    }

    public function pay(int $amountInCents): void
    {
        /*
         * I (Ilia), assume that processPayment of
         * StripePaymentProcessor is expecting price in euro and not in cents
         * because of float type in argument.
         */
        $amountInEuros = $amountInCents / 100.0;
        
        $paymentResult = $this->processor->processPayment($amountInEuros);
        
        if ($paymentResult === false) {
            throw new \Exception('Stripe payment failed.');
        }
    }
}