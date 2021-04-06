<?php

namespace Database\Seeders;

use App\Models\Webshop;
use Illuminate\Database\Seeder;

class WebshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Webshop::insert([[
            'url' => "https://keyapplications.nl/",
            'key' => "ck_ed3b71604290785e08985ce82e781272a6fc9831",
            'secret' => "cs_dda1579e8a16b20c13de21f6f500ec7810432021",
            'type' => "WooCommerce",]
        ]);
    }
}
