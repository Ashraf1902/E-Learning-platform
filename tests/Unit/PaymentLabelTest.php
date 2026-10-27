<?php

namespace Tests\Unit;

use App\Models\Payment;
use PHPUnit\Framework\TestCase;

class PaymentLabelTest extends TestCase
{
    public function test_payment_method_labels_are_human_readable(): void
    {
        $this->assertSame('فوري', (new Payment(['method' => 'fawry']))->method_label);
        $this->assertSame('فودافون كاش', (new Payment(['method' => 'vodafone_cash']))->method_label);
        $this->assertSame('unknown', (new Payment(['method' => 'unknown']))->method_label);
    }
}
