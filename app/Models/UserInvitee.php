<?php

namespace App\Models;

use App\Http\Traits\BuildTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvitee extends Model
{
    use HasFactory;
    use BuildTrait;

    public const STATUS_YES = 'yes';
    public const STATUS_NO = 'no';

//    protected $table = 'user_invitees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'invitation_id',
        'status',
    ];
}
