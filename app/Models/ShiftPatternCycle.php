<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftPatternCycle extends Model
{
    use HasFactory;

    protected $table = "shift_pattern_cycle";

    protected $fillable = [
        'id',
        'pattern_id',
        'cycle_day',
        'shift_id',
        'day_name',
    ];

    public $timestamps = false;
}
