<?php

declare(strict_types = 1);

namespace App\Models;

use App\Casts\FloatIntCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    public $incrementing = false;

    protected $fillable = [
        'balance',
    ];

    protected $casts = [
        'balance' => FloatIntCast::class,
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'id');
    }
}
