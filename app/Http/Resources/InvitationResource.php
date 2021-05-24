<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    public function toArray($request)
    {
        $scheduleTime = json_decode($this->scheduled_time, true);
        return [
            'reference' => $this->reference,
            'title' => $this->title,
            'description' => $this->description,
            'meeting_link' => $this->meeting_link,
            'scheduled_date' => $this->scheduled_date,
            'scheduled_time' => [
                'from' => $scheduleTime['from'],
                'to' => $scheduleTime['to'],
            ],
            'is_cancelled' => $this->is_cancelled ?? false,
            'organizer_id' => $this->organizer_id,
        ];
    }
}
