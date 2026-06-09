<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('gen_random_uuid()'));
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', [
                'PENDING_BILLING',
                'WAITING_PAYMENT',
                'VERIFYING',
                'CONFIRMED',
                'REJECTED',
                'EXPIRED',
            ])->default('PENDING_BILLING');
            $table->jsonb('customer_data')->nullable();
            $table->timestamp('lock_expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // PRD-specified composite availability index
            $table->index(['building_id', 'start_date', 'end_date', 'status'], 'idx_availability');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
