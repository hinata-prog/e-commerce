<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = array(
			array('name' => 'Province no 1'),
			array('name' => 'Province no 2'),
			array('name' => 'Province no 3'),
			array('name' => 'Province no 4'),
			array('name' => 'Province no 5'),
			array('name' => 'Province no 6'),
			array('name' => 'Province no 7'),

		);

		DB::table('provinces')->insert($provinces);

    }
}
