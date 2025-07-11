<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // Order Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_SERVED = 'served';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Payment Status Constants
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';

    // Payment Method Constants
    const PAYMENT_METHOD_QRIS = 'QRIS';
    const PAYMENT_METHOD_CASH = 'cash';

    protected $fillable = [
        'user_id',
        'outlet_id',
        'table_id',
        'promotion_id',
        'order_code',
        'guest_info',
        'total_amount',
        'tax_amount',
        'service_fee_amount',
        'discount_amount',
        'status',
        'order_type',
        'status',
        'total_amount',
        'subtotal',
        'additional_fee',
        'other_fee',
        'payment_method',
        'payment_status',
        'note',
        'ordered_at',
        'completed_at',
        'order_number',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_fee_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'guest_info' => 'array',
        'ordered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get all available order statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_PREPARING => 'Sedang Disiapkan',
            self::STATUS_READY => 'Siap Diantar',
            self::STATUS_SERVED => 'Sudah Diantar',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Get all available payment statuses
     */
    public static function getPaymentStatuses(): array
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'Menunggu Pembayaran',
            self::PAYMENT_STATUS_PAID => 'Sudah Dibayar',
            self::PAYMENT_STATUS_FAILED => 'Gagal'
        ];
    }

    /**
     * Get all available payment methods
     */
    public static function getPaymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_QRIS => 'QRIS',
            self::PAYMENT_METHOD_CASH => 'Cash'
        ];
    }

    /**
     * Check if order is in progress (not completed or cancelled)
     */
    public function isInProgress(): bool
    {
        return !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    /**
     * Check if order can be updated to a specific status
     */
    public function canUpdateToStatus(string $newStatus): bool
    {
        $allowedTransitions = [
            self::STATUS_PENDING => [self::STATUS_PREPARING, self::STATUS_CANCELLED],
            self::STATUS_PREPARING => [self::STATUS_READY, self::STATUS_CANCELLED],
            self::STATUS_READY => [self::STATUS_SERVED, self::STATUS_CANCELLED],
            self::STATUS_SERVED => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => []
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the outlet that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the table that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the promotion that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get all of the orderItems for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all of the payments for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
