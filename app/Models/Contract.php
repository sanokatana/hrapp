<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'kontrak';

    protected $fillable = [
        'nik',
        'no_kontrak',
        'hari_kerja',
        'start_date',
        'end_date',
        'contract_type',
        'position',
        'reasoning',
        'contract_file',
        'created_by',
        'status',
    ];

    // Disable automatic timestamps
    public $timestamps = false;
}
