<?php

namespace App\Service\Payment;

use Exception;

interface PaymentProcessorInterface
{
    /**
     * @throws Exception
     */
    public function pay(int $amountInCents): void;
}