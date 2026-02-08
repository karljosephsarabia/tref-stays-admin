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
        // Saved/Favorited properties
        Schema::create('rs_saved_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('property_id');
            $table->timestamps();
            
            $table->unique(['user_id', 'property_id']);
            $table->index('user_id');
            $table->index('property_id');
        });

        // Property reviews/feedback
        Schema::create('rs_property_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('property_id');
            $table->unsignedInteger('reservation_id')->nullable();
            $table->integer('rating')->default(5); // 1-5 stars
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('property_id');
        });

        // Chat/Messages between renters and hosts
        Schema::create('rs_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('recipient_id');
            $table->unsignedInteger('property_id')->nullable();
            $table->unsignedBigInteger('conversation_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index('sender_id');
            $table->index('recipient_id');
            $table->index('conversation_id');
        });

        // Conversations (groups messages between two users about a property)
        Schema::create('rs_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_one_id'); // renter
            $table->unsignedInteger('user_two_id'); // host/owner
            $table->unsignedInteger('property_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_one_id', 'user_two_id']);
            $table->index('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rs_conversations');
        Schema::dropIfExists('rs_messages');
        Schema::dropIfExists('rs_property_reviews');
        Schema::dropIfExists('rs_saved_properties');
    }
};
