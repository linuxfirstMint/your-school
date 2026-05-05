<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('accommodation_plans', function (Blueprint $table) {
            $table->index('deleted_at');
        });

        Schema::table('reservation_slots', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('accommodation_plans', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('reservation_slots', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
