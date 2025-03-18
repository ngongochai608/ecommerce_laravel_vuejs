<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->text('items');
            $table->decimal('price', 10, 2);
            $table->decimal('price_add', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('note')->nullable();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
