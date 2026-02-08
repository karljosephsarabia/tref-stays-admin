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
            $table->boolean('show_on_homepage')->default(false);
            $table->integer('homepage_order')->nullable();
            $table->string('featured_badge')->nullable();
            $table->string('highlight_color')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->string('banner_image_url')->nullable();
            $table->string('display_layout')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->text('spotlight_message')->nullable();
            $table->boolean('allow_instant_booking')->default(false);
            $table->boolean('is_luxury_tier')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rs_properties', function (Blueprint $table) {
            $table->dropColumn([
                'show_on_homepage',
                'homepage_order',
                'featured_badge',
                'highlight_color',
                'hero_title',
                'hero_subtitle',
                'hero_cta_text',
                'hero_cta_url',
                'banner_image_url',
                'display_layout',
                'custom_css',
                'custom_js',
                'seo_title',
                'seo_description',
                'seo_keywords',
                'spotlight_message',
                'allow_instant_booking',
                'is_luxury_tier',
            ]);
        });
    }
};
