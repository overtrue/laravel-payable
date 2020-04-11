<?php

namespace Overtrue\LaravelPayable\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Overtrue\LaravelPayable\Payment;

class PaymentUpdated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var \Overtrue\LaravelPayable\Payment
     */
    protected $payment;

    /**
     * @param \Overtrue\LaravelPayable\Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
