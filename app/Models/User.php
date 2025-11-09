<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // ← add this
use Laravel\Sanctum\HasApiTokens;

use App\Models\Cabang;
use App\Models\Company;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** @return BelongsToMany<\App\Models\Company> */
    public function companies(): BelongsToMany // ← add return type
    {
        return $this->belongsToMany(Company::class, 'company_user')->withTimestamps();
    }

    /** @return BelongsToMany<\App\Models\Cabang> */
    public function cabang(): BelongsToMany // ← add return type
    {
        return $this->belongsToMany(Cabang::class, 'cabang_user')->withTimestamps();
    }
}
