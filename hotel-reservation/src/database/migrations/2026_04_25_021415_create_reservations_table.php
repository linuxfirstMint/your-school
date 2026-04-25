<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_slot_id')->constrained()->restrictOnDelete();
            $table->foreignId('accommodation_plan_id')->constrained()->restrictOnDelete();
            $table->string('plan_name');
            $table->unsignedInteger('price');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email');
            $table->string('address');
            $table->string('phone', 20);
            $table->text('message')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('memo')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
