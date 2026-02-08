<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // rs_users table
        Schema::create('rs_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->integer('role_id')->default(1);
            $table->string('phone_number')->nullable();
            $table->string('pin', 10)->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('password');
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->decimal('broker_cut', 10, 2)->nullable();
            $table->decimal('commission', 10, 2)->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('activated')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // rs_properties table
        Schema::create('rs_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->string('title');
            $table->string('zipcode_id', 20)->nullable();
            $table->string('street_name')->nullable();
            $table->string('house_number', 20)->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('guest_count')->default(1);
            $table->integer('bed_count')->default(1);
            $table->integer('bedroom_count')->default(1);
            $table->integer('bathroom_count')->default(1);
            $table->integer('property_type')->default(1);
            $table->integer('cancellation_type')->nullable();
            $table->decimal('cancellation_cut', 10, 2)->nullable();
            $table->string('map_lat', 50)->nullable();
            $table->string('map_lng', 50)->nullable();
            $table->text('map_address')->nullable();
            $table->text('additional_luxury')->nullable();
            $table->text('additional_information')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_property_images table
        Schema::create('rs_property_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->unsigned();
            $table->string('image_url');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_reservations table
        Schema::create('rs_reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('broker_id')->unsigned()->nullable();
            $table->integer('property_id')->unsigned();
            $table->date('date_start');
            $table->date('date_end');
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('guest_count')->default(1);
            $table->integer('night_count')->default(1);
            $table->decimal('cancellation_cut', 10, 2)->default(0);
            $table->decimal('broker_cut', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('confirmation_number', 50)->nullable();
            $table->integer('status')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_reservation_payments table
        Schema::create('rs_reservation_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->string('stripe_brand', 50)->nullable();
            $table->string('stripe_last4', 10)->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_reservation_activities table
        Schema::create('rs_reservation_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reservation_id')->unsigned();
            $table->integer('activity')->default(1);
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_transactions table
        Schema::create('rs_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('type')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_incoming_reports table
        Schema::create('rs_incoming_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('reservation_total', 10, 2)->default(0);
            $table->decimal('commission_total', 10, 2)->default(0);
            $table->decimal('cancellation_fee_total', 10, 2)->default(0);
            $table->decimal('refund_total', 10, 2)->default(0);
            $table->decimal('broker_fee_total', 10, 2)->default(0);
            $table->decimal('payment_total', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_user_notifications table
        Schema::create('rs_user_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->boolean('read')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_user_fee_configurations table
        Schema::create('rs_user_fee_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rs_user_id')->unsigned();
            $table->decimal('broker_fee', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('cleaning_fee', 10, 2)->default(0);
            $table->decimal('tax_rate', 10, 2)->default(0);
            $table->boolean('stripe_account_completed')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_credit_cards table
        Schema::create('rs_credit_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rs_user_id')->unsigned();
            $table->string('stripe_card_id')->nullable();
            $table->string('brand', 50)->nullable();
            $table->string('last4', 10)->nullable();
            $table->integer('exp_month')->nullable();
            $table->integer('exp_year')->nullable();
            $table->string('status', 50)->default('active');
            $table->boolean('verified')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_menus table
        Schema::create('rs_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_menu_items table
        Schema::create('rs_menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('target', 20)->default('_self');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_actions table
        Schema::create('rs_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_criterion_types table
        Schema::create('rs_criterion_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_criteria table
        Schema::create('rs_criteria', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('criterion_type_id')->unsigned();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_property_criteria table
        Schema::create('rs_property_criteria', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->unsigned();
            $table->integer('criterion_id')->unsigned();
            $table->text('value')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // rs_last_searches table
        Schema::create('rs_last_searches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rs_user_id')->unsigned()->nullable();
            $table->string('cid')->nullable();
            $table->string('search_type')->default('property');
            $table->integer('property_id')->unsigned()->nullable();
            $table->string('search_query')->nullable();
            $table->text('search_params')->nullable();
            $table->string('client_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rs_last_searches');
        Schema::dropIfExists('rs_property_criteria');
        Schema::dropIfExists('rs_criteria');
        Schema::dropIfExists('rs_criterion_types');
        Schema::dropIfExists('rs_actions');
        Schema::dropIfExists('rs_menu_items');
        Schema::dropIfExists('rs_menus');
        Schema::dropIfExists('rs_credit_cards');
        Schema::dropIfExists('rs_user_fee_configurations');
        Schema::dropIfExists('rs_user_notifications');
        Schema::dropIfExists('rs_incoming_reports');
        Schema::dropIfExists('rs_transactions');
        Schema::dropIfExists('rs_reservation_activities');
        Schema::dropIfExists('rs_reservation_payments');
        Schema::dropIfExists('rs_reservations');
        Schema::dropIfExists('rs_property_images');
        Schema::dropIfExists('rs_properties');
        Schema::dropIfExists('rs_users');
    }
}
