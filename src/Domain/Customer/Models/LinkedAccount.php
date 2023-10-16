<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LinkedAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [
        'id',
        'resource_id',
        'customer_id',
        'account_number',
        'scheme',
        'status',
    ];

    public function getRouteKeyName(): string
    {
        return 'resource_id';
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id'
        );
    }
}
