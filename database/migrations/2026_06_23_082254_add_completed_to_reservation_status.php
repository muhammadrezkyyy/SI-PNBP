<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE reservations DROP CONSTRAINT reservations_status_check');
        DB::statement("ALTER TABLE reservations ADD CONSTRAINT reservations_status_check CHECK (status::text = ANY (ARRAY['PENDING_BILLING'::character varying, 'WAITING_PAYMENT'::character varying, 'VERIFYING'::character varying, 'CONFIRMED'::character varying, 'COMPLETED'::character varying, 'REJECTED'::character varying, 'EXPIRED'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE reservations DROP CONSTRAINT reservations_status_check');
        DB::statement("ALTER TABLE reservations ADD CONSTRAINT reservations_status_check CHECK (status::text = ANY (ARRAY['PENDING_BILLING'::character varying, 'WAITING_PAYMENT'::character varying, 'VERIFYING'::character varying, 'CONFIRMED'::character varying, 'REJECTED'::character varying, 'EXPIRED'::character varying]::text[]))");
    }
};
