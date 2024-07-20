<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timstamp = \Carbon\Carbon::now()->toDateString();
        DB::table('orderitems')->insert([
            'order_id' => 1,
            'product_id' =>1,
            'quantity' =>1,
            'created_at' => $timstamp,
            'updated_at' => $timstamp,
        ]);
    }
}