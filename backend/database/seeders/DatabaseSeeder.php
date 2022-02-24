<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        //シーダークラスを指定して呼出し
        $this->call([
            TruncateAllTables::class,
            PointTableSeeder::class,
            // UserTableSeeder::class,
            GroupTableSeeder::class,
        ]);
    }
}
