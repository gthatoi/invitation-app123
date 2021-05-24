<?php

namespace App\Models;

use App\Http\Traits\BuildTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    use BuildTrait;

    protected $casts = [
        'scheduled_time' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'title',
        'description',
        'scheduled_date',
        'scheduled_time',
        'organizer_id',
        'meeting_link',
    ];
}
