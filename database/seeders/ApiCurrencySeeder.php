<?php

namespace Database\Seeders;

use App\Models\ApiCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api_currency = [
            ['fiat' => 'USD'],
            ['fiat' => 'EUR', 'rate' => '0.92', 'BTC' => '61,438.34', 'XMR' => '131.42'],
            ['fiat' => 'JPY', 'rate' => '147.95', 'BTC' => '9,876,685.13', 'XMR' => '21,162.04'],
            ['fiat' => 'GBP', 'rate' => '0.78', 'BTC' => '52,375.44', 'XMR' => '112.21'],
            ['fiat' => 'CHF', 'rate' => '0.88', 'BTC' => '58,824.65', 'XMR' => '125.99'],
            ['fiat' => 'CAD', 'rate' => '1.35', 'BTC' => '90,200.25', 'XMR' => '192.97'],
            ['fiat' => 'AUD', 'rate' => '1.52', 'BTC' => '101,166.38', 'XMR' => '216.46'],
            ['fiat' => 'CNY', 'rate' => '7.20', 'BTC' => '480,461.33', 'XMR' => '1,028.40'],
            ['fiat' => 'SEK', 'rate' => '10.29', 'BTC' => '686,991.02', 'XMR' => '1,468.73'],
            ['fiat' => 'NZD', 'rate' => '1.62', 'BTC' => '108,397.98', 'XMR' => '231.81'],
        ];

        foreach ($api_currency as $api) {
            ApiCurrency::create($api);
        }
    }
}
