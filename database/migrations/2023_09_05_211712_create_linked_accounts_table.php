<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'linked_accounts', callback: function (Blueprint $table) {
            // Table ids
            $table->unsignedBigInteger(column: 'id')->primary();
            $table->uuid(column: 'resource_id')->unique()->nullable(value: false);

            // Table related fields
            $table->unsignedBigInteger(column: 'customer_id');

            // Table main attributes
            $table->string(column: 'scheme');
            $table->string(column: 'account_number')->unique();

            $table->string(column: 'status')->default(value: 'active');

            // Foreign key field
            $table->foreign(columns: 'customer_id')->references(columns: 'id')->on(table: 'customers')->onDelete(action: 'cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'linked_accounts');
    }
};
