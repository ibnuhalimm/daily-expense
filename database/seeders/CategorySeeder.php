<?php

namespace Database\Seeders;

use App\Models\Category;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table((new Category())->getTable())->truncate();

        DB::table((new Category())->getTable())->insert([
            [
                'name' => 'Uncategorized',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Asuransi',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Bensin',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Biaya Parkir',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Fashion',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Gadget & Elektronik',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Hiburan & Film',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Investasi',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Jalan-jalan',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Kafe',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Kesehatan',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Listrik & Air',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Makanan dan Minuman',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Olahraga',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Pendidikan',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Renovasi',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Servis Kendaraan',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Sewa Rumah & Kos',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Telepon & Internet',
                'created_at' => new DateTime()
            ],
            [
                'name' => 'Lainnya',
                'created_at' => new DateTime()
            ],
        ]);
    }
}
