<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Enums\ReservationActivityType;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Models\RsProperty;
use App\RsUser;

class RsReservationTableSeeder extends Seeder
{
    protected $reservations = [
        [
            'date_start' => '2020-05-04',
            'date_end' => '2020-05-07',
            'price' => 10.52,
            'guest_count' => 4,
            'night_count' => 3,
            'cancellation_cut' => 0.00,
            'broker_cut' => 10.00,
            'total_price' => 41.56,
            'confirmation_number' => '12545869'
        ],
        [
            'date_start' => '2020-05-11',
            'date_end' => '2020-05-15',
            'price' => 10.52,
            'guest_count' => 2,
            'night_count' => 4,
            'cancellation_cut' => 0.00,
            'broker_cut' => 10.00,
            'total_price' => 52.08,
            'confirmation_number' => '12588696'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = RsUser::active()->where('role_id', RoleType::CUSTOMER)->first();
        $broker = RsUser::active()->where('role_id', RoleType::BROKER)->first();
        $property = RsProperty::active()->first();

        $start_dates = [
            Carbon::today()->addDay(1),
            Carbon::today()->addDay(7),
        ];

        foreach ($this->reservations as $key => $reservation) {
            $reservation['date_start'] = $start_dates[$key]->format('Y-m-d');
            $reservation['date_end'] = $start_dates[$key]->addDay($reservation['night_count'])->format('Y-m-d');

            $reservation['customer_id'] = $customer->id;
            $reservation['broker_id'] = $broker->id;
            $reservation['property_id'] = $property->id;

            $reservation['created_at'] = new \DateTime();
            $reservation['updated_at'] = new \DateTime();

            $reservation_id = DB::table('rs_reservations')->insertGetId($reservation);

            DB::table('rs_reservation_payments')->insert([
                'reservation_id' => $reservation_id,
                'stripe_brand' => 'Visa',
                'stripe_last4' => '4242',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime()
            ]);

            DB::table('rs_reservation_activities')->insert([
                'user_id' => $customer->id,
                'reservation_id' => $reservation_id,
                'activity' => ReservationActivityType::CUSTOMER_CREATED,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]);

            DB::table('rs_transactions')->insert([
                'reservation_id' => $reservation_id,
                'user_id' => $property->owner_id,
                'amount' => $reservation['total_price'],
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime()
            ]);
        }
    }
}