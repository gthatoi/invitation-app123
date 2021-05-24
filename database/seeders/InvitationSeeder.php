<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InvitationSeeder extends Seeder implements FixturesInterface
{
    public function run(): void
    {
        foreach ($this->getMockData() as $mockData) {
            DB::table('invitations')->insert($mockData);
        }
    }

    public function getMockData(): array
    {
        return [
            [
                'reference' => 'IV16525',
                'title' => 'Daily Standup',
                'description' => 'is of 15mins',
                'meeting_link' => 'https://meet.google.com/ifc-oszg-cnp',
                'scheduled_date' => '2021-05-25',
                'scheduled_time' => json_encode([
                    'from' => '0800',
                    'to' => '1000',
                ]),
                'is_cancelled' => false,
                'organizer_id' => 1,
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'reference' => 'TESTING_INVITATION',
                'title' => 'Testing',
                'description' => 'detailed description',
                'meeting_link' => 'https://meet.google.com/ifc-oszg-cnp',
                'scheduled_date' => '2021-05-30',
                'scheduled_time' => json_encode([
                    'from' => '0800',
                    'to' => '1000',
                ]),
                'is_cancelled' => false,
                'organizer_id' => 2,
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
            [
                'reference' => 'ORGANIZER_CAN_CANCEL',
                'title' => 'Testing',
                'description' => 'detailed description',
                'meeting_link' => 'https://meet.google.com/ifc-oszg-cnp',
                'scheduled_date' => '2021-05-30',
                'scheduled_time' => json_encode([
                    'from' => '0800',
                    'to' => '1000',
                ]),
                'is_cancelled' => false,
                'organizer_id' => 3,
                'created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime('-5 days'))->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
