<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('gen_random_uuid()'));
            $table->foreignUuid('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->string('simponi_billing_code', 15)->nullable();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->string('ntpn', 16)->nullable();
            $table->string('receipt_path')->nullable();
            $table->jsonb('ocr_metadata')->nullable();
            $table->string('simponi_pdf_path')->nullable();
            $table->timestamps();

            // PRD-specified unique constraint on NTPN (anti-replay)
            $table->unique('ntpn', 'uq_payment_ntpn');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
