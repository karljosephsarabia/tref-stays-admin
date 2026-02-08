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
        Schema::table('rs_property_images', function (Blueprint $table) {
            if (!Schema::hasColumn('rs_property_images', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('image_url');
            }
            if (!Schema::hasColumn('rs_property_images', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_primary');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rs_property_images', function (Blueprint $table) {
            $table->dropColumn(['is_primary', 'sort_order']);
        });
    }
};
