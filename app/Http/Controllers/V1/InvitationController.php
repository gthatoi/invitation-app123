<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Http\Service\ReferenceService;
use App\Models\Invitation;
use App\Models\User;
use App\Models\UserInvitee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    /**
     * @throws \Exception|\Throwable
     */
    public function create(Request $request, ReferenceService $referenceService)
    {
        $rules = [
            'title' => 'bail|required|string',
            'description' => 'bail|required|string',
            'guests' => [
                'bail',
                'required',
                'array',
                function ($attribute, $values, $fail) {
                    foreach ($values as $email) {
                        if (!filter_var( $email, FILTER_VALIDATE_EMAIL )) {
                            $fail(sprintf('%s has invalid emails', $attribute));
                        }
                    }

                    return true;
                },
                function ($attribute, $values, $fail) {
                    foreach ($values as $email) {
                        if (!User::firstWhere('email', $email)) {
                            $fail(sprintf('%s email doesnt exists', $email));
                        }
                    }

                    return true;
                },
            ],
            'scheduled_date' => 'bail|required|date',
            'scheduled_time' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    $from = $value['from'];
                    $to = $value['to'];

                    if ((int) $from > (int) $to) {
                        $fail('From time cannot be later than to time');
                    }

                    return true;
                }
            ],
            'meeting_link' => 'bail|required|url',
            'organizer_id' => [
                'bail',
                'required',
                'numeric',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($request) {
                    $organizer = User::find($value);
                    if (!$request->get('guests') || in_array($organizer->email, $request->get('guests'))) {
                        $fail('Organizer cannot be in the guest list');
                    }

                    return true;
                }
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->all();
        $invitation = Invitation::buildEntityFromArray($data);

        $invitation->reference = $referenceService->getReference(Invitation::class);
        $invitation->scheduled_time = json_encode($data['scheduled_time']);

        try {
            DB::beginTransaction();
            $invitation->saveOrFail();

            $this->saveUserInvite($invitation->organizer_id, $invitation->id, UserInvitee::STATUS_YES);

            foreach ($data['guests'] as $guest) {
                $user = User::firstWhere('email', $guest);

                $this->saveUserInvite($user->id, $invitation->id);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->errorResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(new InvitationResource($invitation), true);
    }

    public function cancel(Request $request, $reference)
    {
        try {
            $invitation = Invitation::firstWhere('reference', $reference);
            if (!$invitation) {
                throw new NotFoundException();
            }

            if ($invitation->is_cancelled) {
                return $this->emptySuccessResponse();
            }

            $data = $request->all();
            if ($data['organizer_id'] !== $invitation->organizer_id) {
                throw new ApiException('Only organizer can cancel the event');
            }

            $invitation->is_cancelled = true;
            $invitation->save();

            return response()->json()->setStatusCode(Response::HTTP_OK);

        } catch (NotFoundException|ApiException $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getHttpStatusCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function respond(Request $request, $reference)
    {
        try {
            $invitation = Invitation::firstWhere('reference', $reference);
            if (!$invitation) {
                throw new NotFoundException();
            }

            $data = $request->all();

            // check if the user exists in the invitation list
            $userInvitee = UserInvitee::where([
                'invitation_id' => $invitation->id,
                'user_id' => $data['user_id']
            ])->first();

            if (!$userInvitee) {
                throw new ApiException('User not found in the invitation guest list.');
            }

            $rules = [
                'status' => [
                    'required',
                    'string',
                    'in:' . implode(',', [UserInvitee::STATUS_YES, UserInvitee::STATUS_NO]),
                ],
            ];

            $validator = Validator::make($request->all(), $rules)->stopOnFirstFailure(true);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $userInvitee->status = $data['status'];
            $userInvitee->save();

            return $this->emptySuccessResponse();

        } catch (NotFoundException|ApiException $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getHttpStatusCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    private function saveUserInvite(int $userId, int $invitationId, string $status = ''): bool
    {
        $userInvitee = UserInvitee::buildEntityFromArray([
            'user_id' => $userId,
            'invitation_id' => $invitationId,
            'status' => $status
        ]);

        return $userInvitee->save();
    }
}
