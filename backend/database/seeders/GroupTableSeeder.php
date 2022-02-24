<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $group = Group::factory(5)->create();

        // 1件だけしか登録できない
        // $group = Group::factory()->create()->each(function($group) {
            // $group->users()->save(User::factory()->make());
        // });
        $group = Group::factory()->create()->each(function($group) {
            $group->users()->save(User::factory()->make());
        });
    }
}
