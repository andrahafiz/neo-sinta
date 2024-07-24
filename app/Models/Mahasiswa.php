<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Mahasiswa
 *
 * @property int $id
 * @property string $name
 * @property string|null $nim
 * @property string $email
 * @property string $password
 * @property bool $is_active
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|Sitin[] $sitins
 *
 * @package App\Models
 */
class Mahasiswa  extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;
    protected $table = 'mahasiswas';

    protected $casts = [
        'is_active' => 'bool'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'is_active',
        'remember_token'
    ];

    public function sitins()
    {
        return $this->hasMany(Sitin::class, 'mahasiswas_id');
    }


}
