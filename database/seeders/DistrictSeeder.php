<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = array(
			array('name' => 'Taplejung', 'province_id' => 1),
            array('name' => 'Sankhuwasabha', 'province_id' => 1),
            array('name' => 'Solukhumbu', 'province_id' => 1),
            array('name' => 'Udayapur', 'province_id' => 1),
            array('name' => 'Morang', 'province_id' => 1),
            array('name' => 'Ilam', 'province_id' => 1),
            array('name' => 'Jhapa', 'province_id' => 1),
            array('name' => 'Khotang', 'province_id' => 1),
            array('name' => 'Bhojpur', 'province_id' => 1),
            array('name' => 'Sunsari', 'province_id' => 1),
            array('name' => 'Panchthar', 'province_id' => 1),
            array('name' => 'Okhaldhunga', 'province_id' => 1),
            array('name' => 'Dhankuta', 'province_id' => 1),
            array('name' => 'Tehrathum', 'province_id' => 1),
            array('name' => 'Mahottari', 'province_id' => 2),
            array('name' => 'Rautahat', 'province_id' => 2),
            array('name' => 'Dhanusha', 'province_id' => 2),
            array('name' => 'Siraha', 'province_id' => 2),
            array('name' => 'Bara', 'province_id' => 2),
            array('name' => 'Sarlahi', 'province_id' => 2),
            array('name' => 'Parsa', 'province_id' => 2),
            array('name' => 'Saptari', 'province_id' => 2),
            array('name' => 'Bhaktapur', 'province_id' => 3),
            array('name' => 'Lalitpur', 'province_id' => 3),
            array('name' => 'Kathmandu', 'province_id' => 3),
            array('name' => 'Nuwakot', 'province_id' => 3),
            array('name' => 'Kavrepalanchok', 'province_id' => 3),
            array('name' => 'Rasuwa', 'province_id' => 3),
            array('name' => 'Ramechhap', 'province_id' => 3),
            array('name' => 'Dhading', 'province_id' => 3),
            array('name' => 'Dolakha', 'province_id' => 3),
            array('name' => 'Chitwan', 'province_id' => 3),
            array('name' => 'Makwanpur', 'province_id' => 3),
            array('name' => 'Sindhuli', 'province_id' => 3),
            array('Sindhupalchok' => 'Hello', 'province_id' => 3),
            array('name' => 'Parbat', 'province_id' => 4),
            array('name' => 'Nawalparasi', 'province_id' => 4),
            array('name' => 'Syangja', 'province_id' => 4),
            array('name' => 'Tanahun', 'province_id' => 4),
            array('name' => 'Lamjung', 'province_id' => 4),
            array('name' => 'Baglung', 'province_id' => 4),
            array('name' => 'Kaski', 'province_id' => 4),
            array('name' => 'Manang', 'province_id' => 4),
            array('name' => 'Myagdi', 'province_id' => 4),
            array('name' => 'Mustang', 'province_id' => 4),
            array('name' => 'Gorkha', 'province_id' => 4),
            array('name' => 'Nawalparasi', 'province_id' => 5),
            array('name' => 'Gulmi', 'province_id' => 5),
            array('name' => 'Eastern Rukum', 'province_id' => 5),
            array('name' => 'Arghakhanchi', 'province_id' => 5),
            array('name' => 'Pyuthan', 'province_id' => 5),
            array('name' => 'Palpa', 'province_id' => 5),
            array('name' => 'Kapilvastu', 'province_id' => 5),
            array('name' => 'Rolpa', 'province_id' => 5),
            array('name' => 'Bardiya', 'province_id' => 5),
            array('name' => 'Banke', 'province_id' => 5),
            array('name' => 'Western Rukum', 'province_id' => 5),
            array('name' => 'Salyan', 'province_id' => 6),
            array('name' => 'Dailekh', 'province_id' => 6),
            array('name' => 'Kalikot', 'province_id' => 6),
            array('name' => 'Jajarkot', 'province_id' => 6),
            array('name' => 'Surkhet', 'province_id' => 6),
            array('name' => 'Jumla', 'province_id' => 6),
            array('name' => 'Mugu', 'province_id' => 6),
            array('name' => 'Humla', 'province_id' => 6),
            array('name' => 'Dolpa', 'province_id' => 6),
            array('name' => 'Baitadi', 'province_id' => 7),
            array('name' => 'Dadeldhura', 'province_id' => 7),
            array('name' => 'Kanchanpur', 'province_id' => 7),
            array('name' => 'Achham', 'province_id' => 7),
            array('name' => 'Doti', 'province_id' => 7),
            array('name' => 'Bajura', 'province_id' => 7),
            array('name' => 'Darchula', 'province_id' => 7),
            array('name' => 'Kailali', 'province_id' => 7),
            array('name' => 'Bajhang', 'province_id' => 7)

		);

		DB::table('districts')->insert($districts);

    }
}
