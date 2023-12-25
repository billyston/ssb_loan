<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'customers', callback: function (Blueprint $table) {
            // Table ids
            $table->unsignedBigInteger(column: 'id')->primary();
            $table->uuid(column: 'resource_id')->unique()->nullable(value: false);

            // Table main attributes
            $table->string(column: 'first_name');
            $table->string(column: 'last_name');

            $table->string(column: 'phone_number')->unique()->nullable();
            $table->string(column: 'email')->unique()->nullable();

            $table->string(column: 'status')->default(value: 'pending');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'customers');
    }
};
