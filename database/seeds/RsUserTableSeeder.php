<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Enums\RoleType;

class RsUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin user
        DB::table('rs_users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@skymaxservices.com',
            'role_id' => RoleType::ADMIN,
            'phone_number' => '8001234567',
            'pin' => '0000',
            'password' => bcrypt('admin123'),
            'address_1' => '123 Admin Street',
            'country' => 'United States',
            'state' => 'NY',
            'city' => 'New York',
            'zipcode' => '10001'
        ]);

        DB::table('rs_users')->insert([
            'first_name' => 'Junior',
            'last_name' => 'BROKER',
            'email' => 'broker@skymaxservices.com',
            'role_id' => RoleType::BROKER,
            'phone_number' => '8095095537',
            'pin' => '1234',
            'stripe_customer_id' => 'cus_H8QKl7h5CmzBkM',
            'password' => bcrypt('123456'),
            'address_1' => '232 Bedford Ave',
            'country' => 'United State',
            'state' => 'NY',
            'city' => 'Brooklyn',
            'zipcode' => '11011',
            'broker_cut' => 10
        ]);

        DB::table('rs_users')->insert([
            'first_name' => 'Junior',
            'last_name' => 'CUSTOMER',
            'email' => 'customer1@skymaxservices.com',
            'role_id' => RoleType::CUSTOMER,
            'phone_number' => '8095018303',
            'stripe_customer_id' => 'cus_H8QTfzskokFsM1',
            'pin' => '1234',
            'password' => bcrypt('123456')
        ]);

        DB::table('rs_users')->insert([
            'first_name' => 'Junior',
            'last_name' => 'OWNER',
            'email' => 'owner1@skymaxservices.com',
            'role_id' => RoleType::OWNER,
            'phone_number' => '8098231553',
            'stripe_customer_id' => 'cus_H8Qd1IQLgxBi3f',
            'pin' => '1234',
            'password' => bcrypt('123456'),
            'commission' => 5
        ]);

        DB::table('rs_users')->insert([
            'first_name' => 'Junior 2',
            'last_name' => 'OWNER',
            'email' => 'owner2@skymaxservices.com',
            'role_id' => RoleType::OWNER,
            'phone_number' => '8095985689',
            'stripe_customer_id' => 'cus_H8QdyYyoW31u5K',
            'pin' => '1234',
            'password' => bcrypt('123456'),
            'commission' => 5
        ]);

        DB::table('rs_users')->insert([
            'first_name' => 'Junior 2',
            'last_name' => 'CUSTOMER',
            'email' => 'customer2@skymaxservices.com',
            'role_id' => RoleType::CUSTOMER,
            'phone_number' => '8095098303',
            'stripe_customer_id' => 'cus_HC1Kn2pvJ1rP94',
            'pin' => '1234',
            'password' => bcrypt('123456')
        ]);
    }
}
