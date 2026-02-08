<?php

use Illuminate\Database\Seeder;
use SMD\Common\ReservationSystem\Models\RsZipCode;

class ZipCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample US zip codes - major cities
        $zipCodes = [
            // New York
            ['zipcode' => '10001', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7484, 'longitude' => -73.9967],
            ['zipcode' => '10002', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7157, 'longitude' => -73.9863],
            ['zipcode' => '10003', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7317, 'longitude' => -73.9892],
            ['zipcode' => '10004', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.6988, 'longitude' => -74.0389],
            ['zipcode' => '10005', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7069, 'longitude' => -74.0089],
            ['zipcode' => '10006', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7094, 'longitude' => -74.0131],
            ['zipcode' => '10007', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7135, 'longitude' => -74.0078],
            ['zipcode' => '10010', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7390, 'longitude' => -73.9826],
            ['zipcode' => '10011', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7418, 'longitude' => -74.0002],
            ['zipcode' => '10012', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7258, 'longitude' => -73.9981],
            ['zipcode' => '10013', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7209, 'longitude' => -74.0048],
            ['zipcode' => '10014', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7341, 'longitude' => -74.0054],
            ['zipcode' => '10016', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7449, 'longitude' => -73.9784],
            ['zipcode' => '10017', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7527, 'longitude' => -73.9728],
            ['zipcode' => '10018', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7549, 'longitude' => -73.9927],
            ['zipcode' => '10019', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7654, 'longitude' => -73.9853],
            ['zipcode' => '10020', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7587, 'longitude' => -73.9787],
            ['zipcode' => '10021', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7689, 'longitude' => -73.9590],
            ['zipcode' => '10022', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7585, 'longitude' => -73.9677],
            ['zipcode' => '10023', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7768, 'longitude' => -73.9828],
            ['zipcode' => '10024', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7899, 'longitude' => -73.9735],
            ['zipcode' => '10025', 'city' => 'New York', 'state' => 'NY', 'country' => 'US', 'latitude' => 40.7977, 'longitude' => -73.9681],
            
            // Los Angeles
            ['zipcode' => '90001', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 33.9425, 'longitude' => -118.2551],
            ['zipcode' => '90002', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 33.9490, 'longitude' => -118.2473],
            ['zipcode' => '90003', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 33.9640, 'longitude' => -118.2739],
            ['zipcode' => '90004', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0770, 'longitude' => -118.3093],
            ['zipcode' => '90005', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0592, 'longitude' => -118.3016],
            ['zipcode' => '90006', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0485, 'longitude' => -118.2924],
            ['zipcode' => '90007', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0292, 'longitude' => -118.2830],
            ['zipcode' => '90008', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0103, 'longitude' => -118.3415],
            ['zipcode' => '90010', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0605, 'longitude' => -118.3153],
            ['zipcode' => '90011', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0062, 'longitude' => -118.2573],
            ['zipcode' => '90012', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0621, 'longitude' => -118.2399],
            ['zipcode' => '90013', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0447, 'longitude' => -118.2417],
            ['zipcode' => '90014', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0434, 'longitude' => -118.2548],
            ['zipcode' => '90015', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0392, 'longitude' => -118.2722],
            ['zipcode' => '90016', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0309, 'longitude' => -118.3549],
            ['zipcode' => '90017', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0542, 'longitude' => -118.2655],
            ['zipcode' => '90018', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0284, 'longitude' => -118.3166],
            ['zipcode' => '90019', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0482, 'longitude' => -118.3401],
            ['zipcode' => '90020', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'US', 'latitude' => 34.0667, 'longitude' => -118.3092],
            
            // Chicago
            ['zipcode' => '60601', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8862, 'longitude' => -87.6186],
            ['zipcode' => '60602', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8829, 'longitude' => -87.6282],
            ['zipcode' => '60603', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8801, 'longitude' => -87.6270],
            ['zipcode' => '60604', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8780, 'longitude' => -87.6297],
            ['zipcode' => '60605', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8668, 'longitude' => -87.6163],
            ['zipcode' => '60606', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8827, 'longitude' => -87.6389],
            ['zipcode' => '60607', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8724, 'longitude' => -87.6556],
            ['zipcode' => '60608', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8522, 'longitude' => -87.6688],
            ['zipcode' => '60609', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8117, 'longitude' => -87.6540],
            ['zipcode' => '60610', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.9037, 'longitude' => -87.6358],
            ['zipcode' => '60611', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8928, 'longitude' => -87.6168],
            ['zipcode' => '60612', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8816, 'longitude' => -87.6887],
            ['zipcode' => '60613', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.9542, 'longitude' => -87.6563],
            ['zipcode' => '60614', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.9221, 'longitude' => -87.6513],
            ['zipcode' => '60615', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'US', 'latitude' => 41.8018, 'longitude' => -87.6009],
            
            // Houston
            ['zipcode' => '77001', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7523, 'longitude' => -95.3587],
            ['zipcode' => '77002', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7576, 'longitude' => -95.3662],
            ['zipcode' => '77003', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7446, 'longitude' => -95.3466],
            ['zipcode' => '77004', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7269, 'longitude' => -95.3626],
            ['zipcode' => '77005', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7176, 'longitude' => -95.4223],
            ['zipcode' => '77006', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7411, 'longitude' => -95.3874],
            ['zipcode' => '77007', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7733, 'longitude' => -95.4101],
            ['zipcode' => '77008', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7997, 'longitude' => -95.4199],
            ['zipcode' => '77009', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7933, 'longitude' => -95.3603],
            ['zipcode' => '77010', 'city' => 'Houston', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.7584, 'longitude' => -95.3577],
            
            // Phoenix
            ['zipcode' => '85001', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4484, 'longitude' => -112.0773],
            ['zipcode' => '85002', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4303, 'longitude' => -112.0893],
            ['zipcode' => '85003', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4491, 'longitude' => -112.0888],
            ['zipcode' => '85004', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4506, 'longitude' => -112.0667],
            ['zipcode' => '85005', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4584, 'longitude' => -112.1104],
            ['zipcode' => '85006', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4634, 'longitude' => -112.0503],
            ['zipcode' => '85007', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4397, 'longitude' => -112.1104],
            ['zipcode' => '85008', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4567, 'longitude' => -111.9944],
            ['zipcode' => '85009', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4528, 'longitude' => -112.1334],
            ['zipcode' => '85010', 'city' => 'Phoenix', 'state' => 'AZ', 'country' => 'US', 'latitude' => 33.4185, 'longitude' => -112.0343],
            
            // Philadelphia
            ['zipcode' => '19101', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9524, 'longitude' => -75.1636],
            ['zipcode' => '19102', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9526, 'longitude' => -75.1657],
            ['zipcode' => '19103', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9538, 'longitude' => -75.1760],
            ['zipcode' => '19104', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9582, 'longitude' => -75.2010],
            ['zipcode' => '19105', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9526, 'longitude' => -75.1636],
            ['zipcode' => '19106', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9481, 'longitude' => -75.1460],
            ['zipcode' => '19107', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9522, 'longitude' => -75.1588],
            ['zipcode' => '19108', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9526, 'longitude' => -75.1636],
            ['zipcode' => '19109', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9513, 'longitude' => -75.1624],
            ['zipcode' => '19110', 'city' => 'Philadelphia', 'state' => 'PA', 'country' => 'US', 'latitude' => 39.9519, 'longitude' => -75.1657],
            
            // San Antonio
            ['zipcode' => '78201', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4688, 'longitude' => -98.5254],
            ['zipcode' => '78202', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4346, 'longitude' => -98.4632],
            ['zipcode' => '78203', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4141, 'longitude' => -98.4646],
            ['zipcode' => '78204', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4095, 'longitude' => -98.5095],
            ['zipcode' => '78205', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4247, 'longitude' => -98.4915],
            ['zipcode' => '78206', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4352, 'longitude' => -98.4893],
            ['zipcode' => '78207', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4227, 'longitude' => -98.5271],
            ['zipcode' => '78208', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4495, 'longitude' => -98.4602],
            ['zipcode' => '78209', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.4852, 'longitude' => -98.4618],
            ['zipcode' => '78210', 'city' => 'San Antonio', 'state' => 'TX', 'country' => 'US', 'latitude' => 29.3982, 'longitude' => -98.4704],
            
            // San Diego
            ['zipcode' => '92101', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7194, 'longitude' => -117.1628],
            ['zipcode' => '92102', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7142, 'longitude' => -117.1187],
            ['zipcode' => '92103', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7463, 'longitude' => -117.1692],
            ['zipcode' => '92104', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7413, 'longitude' => -117.1294],
            ['zipcode' => '92105', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7417, 'longitude' => -117.0951],
            ['zipcode' => '92106', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7258, 'longitude' => -117.2330],
            ['zipcode' => '92107', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7421, 'longitude' => -117.2487],
            ['zipcode' => '92108', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7734, 'longitude' => -117.1417],
            ['zipcode' => '92109', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7959, 'longitude' => -117.2372],
            ['zipcode' => '92110', 'city' => 'San Diego', 'state' => 'CA', 'country' => 'US', 'latitude' => 32.7672, 'longitude' => -117.1985],
            
            // Dallas
            ['zipcode' => '75201', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.7876, 'longitude' => -96.7987],
            ['zipcode' => '75202', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.7813, 'longitude' => -96.8004],
            ['zipcode' => '75203', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.7483, 'longitude' => -96.8158],
            ['zipcode' => '75204', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.8008, 'longitude' => -96.7891],
            ['zipcode' => '75205', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.8385, 'longitude' => -96.7997],
            ['zipcode' => '75206', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.8316, 'longitude' => -96.7728],
            ['zipcode' => '75207', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.7824, 'longitude' => -96.8227],
            ['zipcode' => '75208', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.7475, 'longitude' => -96.8515],
            ['zipcode' => '75209', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.8453, 'longitude' => -96.8332],
            ['zipcode' => '75210', 'city' => 'Dallas', 'state' => 'TX', 'country' => 'US', 'latitude' => 32.7670, 'longitude' => -96.7552],
            
            // San Jose
            ['zipcode' => '95101', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3391, 'longitude' => -121.8947],
            ['zipcode' => '95103', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3391, 'longitude' => -121.8947],
            ['zipcode' => '95106', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3391, 'longitude' => -121.8947],
            ['zipcode' => '95108', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3391, 'longitude' => -121.8947],
            ['zipcode' => '95109', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3391, 'longitude' => -121.8947],
            ['zipcode' => '95110', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3476, 'longitude' => -121.9086],
            ['zipcode' => '95111', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.2870, 'longitude' => -121.8273],
            ['zipcode' => '95112', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3552, 'longitude' => -121.8901],
            ['zipcode' => '95113', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3339, 'longitude' => -121.8910],
            ['zipcode' => '95114', 'city' => 'San Jose', 'state' => 'CA', 'country' => 'US', 'latitude' => 37.3391, 'longitude' => -121.8947],
            
            // Austin
            ['zipcode' => '78701', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2711, 'longitude' => -97.7431],
            ['zipcode' => '78702', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2616, 'longitude' => -97.7137],
            ['zipcode' => '78703', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2976, 'longitude' => -97.7665],
            ['zipcode' => '78704', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2442, 'longitude' => -97.7612],
            ['zipcode' => '78705', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2919, 'longitude' => -97.7387],
            ['zipcode' => '78708', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.3072, 'longitude' => -97.7417],
            ['zipcode' => '78709', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2131, 'longitude' => -97.8265],
            ['zipcode' => '78710', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.3505, 'longitude' => -97.7565],
            ['zipcode' => '78711', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2711, 'longitude' => -97.7431],
            ['zipcode' => '78712', 'city' => 'Austin', 'state' => 'TX', 'country' => 'US', 'latitude' => 30.2839, 'longitude' => -97.7344],
            
            // Jacksonville
            ['zipcode' => '32099', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3322, 'longitude' => -81.6556],
            ['zipcode' => '32201', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3276, 'longitude' => -81.6577],
            ['zipcode' => '32202', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3261, 'longitude' => -81.6518],
            ['zipcode' => '32203', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3322, 'longitude' => -81.6556],
            ['zipcode' => '32204', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3200, 'longitude' => -81.6783],
            ['zipcode' => '32205', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3081, 'longitude' => -81.7194],
            ['zipcode' => '32206', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3558, 'longitude' => -81.6386],
            ['zipcode' => '32207', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.2892, 'longitude' => -81.6361],
            ['zipcode' => '32208', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3850, 'longitude' => -81.6684],
            ['zipcode' => '32209', 'city' => 'Jacksonville', 'state' => 'FL', 'country' => 'US', 'latitude' => 30.3536, 'longitude' => -81.6950],
            
            // Miami
            ['zipcode' => '33101', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7617, 'longitude' => -80.1918],
            ['zipcode' => '33109', 'city' => 'Miami Beach', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7542, 'longitude' => -80.1308],
            ['zipcode' => '33125', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7774, 'longitude' => -80.2393],
            ['zipcode' => '33126', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7777, 'longitude' => -80.3086],
            ['zipcode' => '33127', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.8121, 'longitude' => -80.1959],
            ['zipcode' => '33128', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7745, 'longitude' => -80.1988],
            ['zipcode' => '33129', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7530, 'longitude' => -80.2016],
            ['zipcode' => '33130', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7677, 'longitude' => -80.2015],
            ['zipcode' => '33131', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7670, 'longitude' => -80.1881],
            ['zipcode' => '33132', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7798, 'longitude' => -80.1827],
            ['zipcode' => '33133', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7283, 'longitude' => -80.2418],
            ['zipcode' => '33134', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7507, 'longitude' => -80.2747],
            ['zipcode' => '33135', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7651, 'longitude' => -80.2306],
            ['zipcode' => '33136', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7848, 'longitude' => -80.2073],
            ['zipcode' => '33137', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.8147, 'longitude' => -80.1773],
            ['zipcode' => '33138', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.8506, 'longitude' => -80.1769],
            ['zipcode' => '33139', 'city' => 'Miami Beach', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.7809, 'longitude' => -80.1340],
            ['zipcode' => '33140', 'city' => 'Miami Beach', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.8097, 'longitude' => -80.1319],
            ['zipcode' => '33141', 'city' => 'Miami Beach', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.8470, 'longitude' => -80.1341],
            ['zipcode' => '33142', 'city' => 'Miami', 'state' => 'FL', 'country' => 'US', 'latitude' => 25.8166, 'longitude' => -80.2332],
        ];

        foreach ($zipCodes as $zipCode) {
            RsZipCode::updateOrCreate(
                ['zipcode' => $zipCode['zipcode']],
                $zipCode
            );
        }
    }
}
