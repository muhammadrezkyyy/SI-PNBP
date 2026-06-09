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
        // 1. Update facility_types table
        Schema::table('facility_types', function (Blueprint $table) {
            $table->string('description')->nullable()->after('name');
            $table->decimal('daily_rate', 15, 2)->default(0)->after('description');
            $table->string('image_path')->nullable()->after('daily_rate');
        });

        // 2. We need to assign a facility_type_id to existing buildings before we make it constrained
        // Actually, we can add it as nullable first, or we can just drop columns. 
        // If there is data, let's keep it simple.

        Schema::table('buildings', function (Blueprint $table) {
            $table->foreignId('facility_type_id')->nullable()->after('name')->constrained('facility_types')->nullOnDelete();
        });

        // Try to migrate existing types if needed, but since it's just testing we can leave it.
        // Drop the old columns from buildings
        Schema::table('buildings', function (Blueprint $table) {
            if (Schema::hasColumn('buildings', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('buildings', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('buildings', 'daily_rate')) {
                $table->dropColumn('daily_rate');
            }
            if (Schema::hasColumn('buildings', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('type')->default('Gedung');
            $table->string('description')->nullable();
            $table->decimal('daily_rate', 15, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->dropForeign(['facility_type_id']);
            $table->dropColumn('facility_type_id');
        });

        Schema::table('facility_types', function (Blueprint $table) {
            $table->dropColumn(['description', 'daily_rate', 'image_path']);
        });
    }
};
