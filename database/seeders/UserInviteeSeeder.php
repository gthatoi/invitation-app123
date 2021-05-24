<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserInviteeSeeder extends Seeder implements FixturesInterface
{
    public function run(): void
    {
        foreach ($this->getMockData() as $mockData) {
            DB::table('user_invitees')->insert($mockData);
        }
    }

    public function getMockData(): array
    {
        return [
            [
                'user_id' => 1,
                'invitation_id' => 1,
                'status' => 'yes',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'invitation_id' => 1,
                'status' => 'no',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 1,
                'invitation_id' => 3,
                'status' => 'yes',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'invitation_id' => 3,
                'status' => 'no',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3,
                'invitation_id' => 3,
                'status' => 'yes',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 1,
                'invitation_id' => 2,
                'status' => '',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'invitation_id' => 2,
                'status' => 'yes',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3,
                'invitation_id' => 2,
                'status' => '',
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
