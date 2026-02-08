<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use SMD\Common\ReservationSystem\Enums\RoleType;
use App\RsUser;

$factory->define(RsUser::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $faker->unique()->phoneNumber,
        'role_id' => RoleType::OWNER,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'pin' => $faker->numberBetween(1000, 9999)
    ];
});
