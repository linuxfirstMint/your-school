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
        Schema::create('plan_room_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('accommodation_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['room_type_id', 'accommodation_plan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_room_prices');
    }
};
