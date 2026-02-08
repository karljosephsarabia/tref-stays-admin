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
        Schema::table('rs_properties', function (Blueprint $table) {
            // Location fields
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->default('US');
            
            // Property details
            $table->string('tagline')->nullable();
            $table->string('neighborhood')->nullable();
            $table->text('house_rules')->nullable();
            $table->string('checkin_time')->nullable();
            $table->string('checkout_time')->nullable();
            $table->decimal('cleaning_fee', 10, 2)->default(0);
            
            // Amenities - JSON array
            $table->text('amenities')->nullable();
            
            // Kosher info - JSON object
            $table->text('kosher_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rs_properties', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'state',
                'zipcode',
                'country',
                'tagline',
                'neighborhood',
                'house_rules',
                'checkin_time',
                'checkout_time',
                'cleaning_fee',
                'amenities',
                'kosher_info',
            ]);
        });
    }
};
