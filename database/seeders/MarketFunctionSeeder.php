<?php

namespace Database\Seeders;

use App\Models\MarketFunction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketFunctions = [
            ['name' => 'login'],
            ['name' => 'signup'],
            ['name' => 'waiver'],
            ['name' => 'cashback'],
            ['name' => 'wallet', 'enable' => 0],
            ['name' => 'withdraw', 'enable' => 0],
            ['name' => 'deposit', 'enable' => 0],
            ['name' => 'storekey'],
            ['name' => 'api', 'enable' => 0],
        ];

        foreach ($marketFunctions as $type) {
            MarketFunction::create($type);
        }
    }
}
