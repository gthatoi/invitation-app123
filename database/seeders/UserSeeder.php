<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder implements FixturesInterface
{
    public function run(): void
    {
        foreach ($this->getMockData() as $mockData) {
            DB::table('users')->insert($mockData);
        }
    }

    public function getMockData(): array
    {
        return [
            [
                'name' => 'John',
                'email' => 'john@gmail.com',
                'password' => Hash::make('john123'),
            'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Joe',
                'email' => 'joe@gmail.com',
                'password' => Hash::make('joe123'),
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],            [
                'name' => 'Jack',
                'email' => 'jack@gmail.com',
                'password' => Hash::make('jack123'),
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
