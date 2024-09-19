<?php

declare(strict_types = 1);

namespace App\Models;

use App\Casts\FloatIntCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'value',
        'receipt',
        'status',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'value'  => FloatIntCast::class,
        'status' => 'boolean',
    ];
}
