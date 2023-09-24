<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [
        'id',
        'resource_id',
        'first_name',
        'middle_name',
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
