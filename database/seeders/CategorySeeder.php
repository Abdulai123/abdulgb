<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fraud'],
            ['name' => 'Hacking & Spam'],
            ['name' => 'Malware'],
            ['name' => 'Drugs & Chemicals'],
            ['name' => 'Services'],
            ['name' => 'Security & Hosting'],
            ['name' => 'Guides & Tutorials'],
            ['name' => 'Software'],
            ['name' => 'Digital Items'],
            ['name' => 'Website & Graphic'],
            ['name' => 'Jewels & Precious Metals'],
            ['name' => 'Counterfeit Items'],
            ['name' => 'Carded Items'],
            ['name' => 'Automotive Items'],
            ['name' => 'Legitimate Items'],
            ['name' => 'Nfts & Tokens'],
            ['name' => 'DeepFake & Web3'],
            ['name' => 'Others'],

            ['name' => 'Hacking & Spam', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
            ['name' => 'Fraud', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
            ['name' => 'Malware', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
            ['name' => 'Carded Items', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
            ['name' => 'Counterfeit Items', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
            ['name' => 'Security', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
            ['name' => 'Others', 'parent_category_id' => 1, 'category' => 'CyberSecurity'],
           
            ['name' => 'Guides & Tutorials', 'parent_category_id' => 2, 'category' => 'Educational Resources'],
            ['name' => 'Software', 'parent_category_id' => 2, 'category' => 'Educational Resources'],
            ['name' => 'Others', 'parent_category_id' => 2, 'category' => 'Educational Resources'],
           
            ['name' => 'Hosting', 'parent_category_id' => 3, 'category' => 'Web Development & Design'],
            ['name' => 'Websites', 'parent_category_id' => 3, 'category' => 'Web Development & Design'],
            ['name' => 'Graphics Design', 'parent_category_id' => 3, 'category' => 'Web Development & Design'],
            ['name' => 'Others', 'parent_category_id' => 3, 'category' => 'Web Development & Design'],
            
            ['name' => 'Jewels', 'parent_category_id' => 4, 'category' => 'Precious Commodities'],
            ['name' => 'Gold & Precious Metals', 'parent_category_id' => 4, 'category' => 'Precious Commodities'],
            ['name' => 'Others', 'parent_category_id' => 4, 'category' => 'Precious Commodities'],
           
            ['name' => 'Cannabis', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Opiates', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Stimulants', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Psychedelics', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Ecstasy', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Benzodiazepines', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Psychedelics', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Prescriptions', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Steroids', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Dissociatives', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Designer drugs', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Chemicals', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Tobacco', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Alcohols', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
            ['name' => 'Others', 'parent_category_id' => 5, 'category' => 'Drugs & Chemicals'],
           
            ['name' => 'Legitimate Items', 'parent_category_id' => 6, 'category' => 'Goods & Services'],
            ['name' => 'Automotive Items', 'parent_category_id' => 6, 'category' => 'Goods & Services'],
            ['name' => 'Digital Products', 'parent_category_id' => 6, 'category' => 'Goods & Services'],
            ['name' => 'Services', 'parent_category_id' => 6, 'category' => 'Goods & Services'],
            ['name' => 'Others', 'parent_category_id' => 6, 'category' => 'Goods & Services'],

        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}