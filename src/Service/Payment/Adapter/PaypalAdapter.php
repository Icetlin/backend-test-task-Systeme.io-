<?php

namespace App\Service\Payment\Adapter;

use App\Service\Payment\PaymentProcessorInterface;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalAdapter implements PaymentProcessorInterface
{
    private PaypalPaymentProcessor $processor;

    public function __construct()
    {
        $this->processor = new PaypalPaymentProcessor();
    }

    /**
     * @throws Exception
     */
    public function pay(int $amountInCents): void
    {
        $this->processor->pay($amountInCents);
    }
}