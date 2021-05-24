<?php

namespace Tests\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;

class InvitationsControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected $reference;


    /**
     * @param array $payload
     * @param int $expectedStatusCode
     * @dataProvider createInvitationValidationDataProvider
     */
    public function testCreateInvitationValidation(array $payload, int $expectedStatusCode, array $expectedResponse)
    {
        $response = $this->json('post', '/v1/invitations', $payload);
        $response->assertStatus($expectedStatusCode)
            ->assertJson($expectedResponse);
    }

    /**
     * @param array $payload
     * @param array $expectedResponseStructure
     * @param int $expectedTotalInvitees
     * @dataProvider createInvitationDataProvider
     */
    public function testCreateInvitation(array $payload, array $expectedResponseStructure, int $expectedTotalInvitees)
    {
        $response = $this->json('post', '/v1/invitations', $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure($expectedResponseStructure);

        $lastInsertedReference = $response->json()['reference'];
        $this->reference = $lastInsertedReference;
        $whereClause = [
            'reference' => $lastInsertedReference
        ];
        $this->assertDatabaseHas('invitations', $whereClause);

        $invitation = $this->getConnection()->table('invitations')->where($whereClause)->first();

        $this->assertEquals($expectedTotalInvitees, $this->getConnection()->table('user_invitees')->where([
            'invitation_id' => $invitation->id
        ])->count());
    }

    /**
     * @param string $reference
     * @param array $payload
     * @param int $expectedStatusCode
     * @dataProvider cancelInvitationDataProvider
     */
    public function testCancelInvitation(string $reference, array $payload, int $expectedStatusCode)
    {
        $response = $this->json('post', sprintf('/v1/invitations/%s/cancel', $reference), $payload);
        $response->assertStatus($expectedStatusCode);
    }

    /**
     * @param string $reference
     * @param array $payload
     * @param int $expectedStatusCode
     * @dataProvider respondToInvitationDataProvider
     */
    public function testRespondToInvitation(string $reference, array $payload, int $expectedStatusCode)
    {
        $response = $this->json('post', sprintf('/v1/invitations/%s/respond', $reference), $payload);
        $response->assertStatus($expectedStatusCode);
    }

    public function createInvitationValidationDataProvider(): array
    {
        return [
            0 => [
                [],
                Response::HTTP_BAD_REQUEST,
                [
                    'error' => json_encode([
                        'title' => [
                            'The title field is required.'
                        ],
                        'description' => [
                            'The description field is required.'
                        ],
                        'guests' => [
                            'The guests field is required.'
                        ],
                        'scheduled_date' => [
                            'The scheduled date field is required.'
                        ],
                        'scheduled_time' => [
                            'The scheduled time field is required.'
                        ],
                        'meeting_link' => [
                            'The meeting link field is required.'
                        ],
                        'organizer_id' => [
                            'The organizer id field is required.'
                        ],
                    ])
                ]
            ],
            1 => [
                [
                    'title' => 'Test title',
                    'description' => 'Test description',
                    'guests' => [
                        '#$%#$%#$%@gmail.com'
                    ],
                    'scheduled_date' => '2021-05-31',
                    'scheduled_time' => [
                        'from' => '0800',
                        'to' => '1000',
                    ],
                    'meeting_link' => 'https://meet.google.com/ifc-oszg-cnp',
                    'organizer_id' => 21,
                ],
                Response::HTTP_BAD_REQUEST,
                [
                    'error' => json_encode([
                        'guests' => [
                            '#$%#$%#$%@gmail.com email doesnt exists'
                        ],
                        'organizer_id' => [
                            'The selected organizer id is invalid.'
                        ],
                    ])
                ]
            ],
            2 => [
                [
                    'title' => 'Test title',
                    'description' => 'Test description',
                    'guests' => [
                        'john@gmail.com',
                        'invalid email',
                    ],
                    'scheduled_date' => '2021-05-40',
                    'scheduled_time' => [
                        'from' => '1200',
                        'to' => '1000',
                    ],
                    'meeting_link' => 'testing123',
                    'organizer_id' => 2,
                ],
                Response::HTTP_BAD_REQUEST,
                [
                    'error' => json_encode([
                        'guests' => [
                            'guests has invalid emails'
                        ],
                        'scheduled_date' => [
                            'The scheduled date is not a valid date.'
                        ],
                        'scheduled_time' => [
                            'From time cannot be later than to time'
                        ],
                        'meeting_link' => [
                            'The meeting link format is invalid.'
                        ],
                    ])
                ]
            ],
        ];
    }

    public function createInvitationDataProvider(): array
    {
        return [
            [
                [
                    'title' => 'Team event',
                    'description' => 'description',
                    'guests' => [
                        'john@gmail.com',
                        'jack@gmail.com',
                    ],
                    'scheduled_date' => '2021-06-04',
                    'scheduled_time' => [
                        'from' => '1600',
                        'to' => '2000',
                    ],
                    'meeting_link' => 'https://meet.google.com/ifc-oszg-cnp',
                    'organizer_id' => 2,
                ],
                [
                    'reference',
                    'title',
                    'description',
                    'meeting_link',
                    'scheduled_date',
                    'scheduled_time',
                    'is_cancelled',
                    'organizer_id',
                ],
                3
            ],
        ];
    }

    public function cancelInvitationDataProvider(): array
    {
        return [
            [
                'NOTFOUND',
                [
                    'organizer_id' => 233,
                ],
                Response::HTTP_NOT_FOUND
            ],
            [
                'ORGANIZER_CAN_CANCEL',
                [
                    'organizer_id' => 12,
                ],
                Response::HTTP_NOT_FOUND,
            ],
            [
                'TESTING_INVITATION',
                [
                    'organizer_id' => 2,
                ],
                Response::HTTP_OK
            ],
        ];
    }

    public function respondToInvitationDataProvider(): array
    {
        return [
            [
                'NOTFOUND',
                [
                    'organizer_id' => 233,
                    'status' => 'yes'
                ],
                Response::HTTP_NOT_FOUND,
            ],
            [
                'ORGANIZER_CAN_CANCEL',
                [
                    'user_id' => 233,
                    'status' => 'yes'
                ],
                Response::HTTP_NOT_FOUND,
            ],
            [
                'TESTING_INVITATION',
                [
                    'user_id' => 3,
                    'status' => 'maybe'
                ],
                Response::HTTP_BAD_REQUEST,
            ],
            [
                'TESTING_INVITATION',
                [
                    'user_id' => 3,
                    'status' => 'no'
                ],
                Response::HTTP_OK,
            ],
        ];
    }
}
