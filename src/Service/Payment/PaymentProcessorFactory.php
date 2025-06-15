<?php

namespace App\Service\Payment;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class PaymentProcessorFactory
{
    private iterable $processors;

    public function __construct(
        #[TaggedIterator('app.payment_processor', indexAttribute: 'key')]
        iterable $processors
    ) {
        $this->processors = $processors;
    }

    public function getProcessor(string $key): PaymentProcessorInterface
    {
        $processorsArray = iterator_to_array($this->processors);
        if (!isset($processorsArray[$key])) {
            throw new InvalidArgumentException("Payment processor '$key' not found.");
        }

        return $processorsArray[$key];
    }
} 