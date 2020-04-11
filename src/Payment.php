<?php

namespace Overtrue\LaravelPayable;

use Overtrue\LaravelPayable\Events\PaymentUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int            $id
 * @property string         $user_id
 * @property string         $payable_id
 * @property string         $payable_type
 * @property double         $amount
 * @property double         $paid_amount
 * @property string         $description
 * @property string         $transaction_id
 * @property string         $currency
 * @property string         $status
 * @property string         $gateway
 * @property array          $gateway_order
 * @property array          $context
 * @property array          $original_result
 * @property \Carbon\Carbon $paid_at
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $failed_at
 */
class Payment extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    const STATUSES = [
        self::STATUS_PENDING => '待支付',
        self::STATUS_PAID => '已支付',
        self::STATUS_CANCELLED => '已取消',
        self::STATUS_FAILED => '已失败',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'payable', 'amount', 'paid_amount',
        'description', 'currency', 'status', 'gateway',
        'gateway_order', 'context', 'original_result',
        'paid_at', 'expired_at', 'failed_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'amount' => 'double',
        'paid_amount' => 'double',
        'gateway_order' => 'array',
        'context' => 'array',
        'original_result' => 'array',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'paid_at', 'expired_at', 'failed_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Payment $payment) {
            $payment->status = $payment->status ?: self::STATUS_PENDING;
            $payment->user_id = $payment->user_id ?: auth()->id();
            $payment->transaction_id = $payment->transaction_id ?: Str::orderedUuid();
        });

        static::saved(function (Payment $payment) {
            \event(new PaymentUpdated($payment));
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payable()
    {
        return $this->morphTo();
    }
}
