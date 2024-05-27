<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;
    protected $guard = 'mahasiswa';
    protected $fillable = [
        'name',
        'email',
        'nim',
        'is_active',
        'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
