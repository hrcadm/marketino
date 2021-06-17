<?php

namespace Database\Seeders;

use App\Enum;
use App\Models;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Enum\UserRole::getValues() as $role_name) {
            Role::create(['name' => $role_name]);
        }

        Models\User::all()->each(static function ($user) {
            $user->assignRole(Enum\UserRole::SUPERADMIN);
            $user->save();
        });
    }
}
