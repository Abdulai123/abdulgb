<?php

namespace Database\Seeders;

use App\Models\StoreRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storeRules = [
            ['name' => '1', 'is_xmr' => true],
            ['name' => 'Wales Market imposes a 5% commission fee on successful sales.'],
            ['name' => 'Store fees are non-refundable to uphold market integrity.'],
            ['name' => 'Prohibited listings include guns, CP (Child pornography), Covid-19 vaccines, assassination services, explosives, fentanyl, poisons, acids, and any items intended for harm.'],
            ['name' => 'Stores focused on selling harmful products will be penalized.'],
            ['name' => 'Prohibited services include hitmans, murders, and snuffs.'],
            ['name' => 'Selling products that lead to harm or death is strictly forbidden.'],
            ['name' => 'Attempting to purchase your own product will result in automatic banning.'],
            ['name' => 'Store products must have clear descriptions and unique characteristics.'],
            ['name' => 'Displaying contact information in listings is not allowed.'],
            ['name' => 'Off-market transactions are prohibited, and violators will face store bans.'],
            ['name' => 'Excessive bad reviews can lead to store escalation, returning escrow funds to various owners.'],
            ['name' => 'Escalated stores will have their products hidden, rendering the store inaccessible.'],
            ['name' => 'Orders auto conclude after 72 hours for "sent", "dispatched" or "delivered" statuses.'],
            ['name' => 'Verified stores with over 4.5 ratings and thousands of sales enjoy early finalization privileges.'],
            ['name' => 'The verification badge is granted to the top 10% of stores with over 6 months of operation and ratings above 4.0, symbolizing excellence.'],
            ['name' => 'Engaging in scams or manipulation may result in store banning based on valid user reports.'],
            ['name' => 'Market spam prevention: Limit your listings to 10 per day.'],
            ['name' => 'Stores with a rating below 2.5 face potential banning.'],
            ['name' => 'New vendors without established reputations must provide a picture of their product with their store name and "Whales Market" written on paper.'],
            ['name' => 'Promote love and respect; inactive stores for 2 week will be placed on vacation mode.'],
            ['name' => 'Rules are subject to change; any modifications will be communicated to all stores and users.'],
            ['name' => 'Thank you for reading, and stay safe.']
        ];

        foreach ($storeRules as $rule) {
            StoreRule::create($rule);
        }
    }
}
