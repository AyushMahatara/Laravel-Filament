<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i =1; $i <= 10; $i++)
        {
            $data->push([
                'name' => "Std {$i}",
                'class_number' => ,
            ])

        }
    }
}
