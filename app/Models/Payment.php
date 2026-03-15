<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_scheme_id',
        'payment_id',
        'current_gold_rate',
        'payable_amount',
        'due_date',
        'next_due_date',
        'grace_start_date',
        'grace_end_date',
        'payment_status',
        'grace_extension_status',
        'grace_extension_reason',
    ];

    protected $casts = [
        'due_date' => 'date',
        'next_due_date' => 'date',
        'grace_start_date' => 'date',
        'grace_end_date' => 'date',
    ];

    public function userScheme()
    {
        return $this->belongsTo(UserScheme::class);
    }
}
