<?php

declare(strict_types=1);

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    use HasFactory;

    protected static function newFactory(): mixed
    {
        $parts = Str::of(get_called_class())->explode("\\");
        $domain = $parts[1];
        $model = $parts->last();

        return app("Database\\Factories\\{$domain}\\{$model}Factory");
    }
}
