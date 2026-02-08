<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('rs_user_notifications')->truncate();
        DB::table('rs_transactions')->truncate();
        DB::table('rs_incoming_reports')->truncate();
        DB::table('rs_reservation_activities')->truncate();
        DB::table('rs_reservation_payments')->truncate();
        DB::table('rs_reservations')->truncate();
        DB::table('rs_property_images')->truncate();
        DB::table('rs_properties')->truncate();
        DB::table('rs_users')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->call(RsUserTableSeeder::class);

        $this->call(RsPropertyTableSeeder::class);

        $this->call(RsReservationTableSeeder::class);
    }
}
