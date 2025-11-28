<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'date', 'clock_in_time', 'clock_out_time', 
        'status', 'reason', 'overtime_start', 'overtime_end', 'overtime_reason'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
