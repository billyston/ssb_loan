<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'resource_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'status',
    ];

    public function getRouteKeyName(): string
    {
        return 'resource_id';
    }
}
