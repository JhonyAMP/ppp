<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $companies = Company::factory(5)->create();

        $users = User::role('Usuario')->get();

        foreach ($users as $user) {

            $assignedCompanies = $companies->random(rand(1, 3));

            $user->empresas()->attach($assignedCompanies);

        }

    }
}
