<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lecture
 * 
 * @property int $id
 * @property string $name
 * @property string|null $nip
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
class Lecture extends Model
{
	use SoftDeletes;
	protected $table = 'lecture';

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'nip',
		'email',
		'password',
		'is_active',
		'remember_token'
	];

	public function sitins()
	{
		return $this->hasMany(Sitin::class, 'approval_by');
	}
}
