<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('milk_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('date');
            $table->decimal('morning_liter', 8, 2)->default(0);
            $table->decimal('morning_fat',   8, 2)->default(0);
            $table->decimal('evening_liter', 8, 2)->default(0);
            $table->decimal('evening_fat',   8, 2)->default(0);
            $table->timestamps();

            $table->unique(['customer_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milk_entries');
    }
};
