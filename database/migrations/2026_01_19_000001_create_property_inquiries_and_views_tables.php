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
        // Property Inquiries/Leads table
        Schema::create('rs_property_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('property_id');
            $table->unsignedInteger('owner_id'); // Property owner
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message');
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->integer('guests')->nullable();
            $table->enum('status', ['new', 'read', 'responded', 'converted', 'archived'])->default('new');
            $table->text('owner_notes')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('property_id');
            $table->index('owner_id');
            $table->index('status');
        });

        // Property Views/Analytics table
        Schema::create('rs_property_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('property_id');
            $table->unsignedInteger('user_id')->nullable(); // Null if anonymous visitor
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('source')->nullable(); // direct, search, social, etc.
            $table->timestamps();

            $table->index('property_id');
            $table->index(['property_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rs_property_views');
        Schema::dropIfExists('rs_property_inquiries');
    }
};
