<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        DB::table('roles')->insert([
            'name' => 'customer',
        ]);
        DB::table('roles')->insert([
            'name' => 'ba',
        ]);

        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => 'ba@aspireapp.com',
            'password' => Hash::make('password'),
        ]);

        $customer = User::where('email', 'user@gmail.com')->first();
        $customerRole = Role::where('name', 'customer')->first();
        DB::table('user_role')->insert([
            'user_id' => $customer->id,
            'role_id' => $customerRole->id,
        ]);

        $ba = User::where('email', 'ba@aspireapp.com')->first();
        $baRole = Role::where('name', 'ba')->first();
        DB::table('user_role')->insert([
            'user_id' => $ba->id,
            'role_id' => $baRole->id,
        ]);
//        $user = User::with('roles')->where('email', 'ba@aspireapp.com')->first();
//        var_dump($user->roles->pluck('name')->toArray());
    }
}
