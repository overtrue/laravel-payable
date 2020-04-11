<?php

namespace Overtrue\LaravelPayable;

interface Payable
{
    public function getPaymentTotal(): int;
    public function getPaymentDescription(): int;
    public function getUserId();
}