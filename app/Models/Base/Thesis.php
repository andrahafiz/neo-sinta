<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Lecture;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Thesis
 *
 * @property int $id
 * @property string $judul_thesis
 * @property string $konsentrasi_ilmu
 * @property string $deskripsi
 * @property int $mahasiswas_id
 * @property int $pembimbing_1
 * @property int $pembimbing_2
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Mahasiswa $mahasiswa
 * @property Lecture $lecture
 *
 * @package App\Models\Base
 */
class Thesis extends Model
{
    use SoftDeletes;
    protected $table = 'theses';

    protected $casts = [
        'mahasiswas_id' => 'int',
        'pembimbing_1' => 'int',
        'pembimbing_2' => 'int',
        'is_active' => 'bool'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
    }

    public function pembimbing1()
    {
        return $this->belongsTo(Lecture::class, 'pembimbing_1');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(Lecture::class, 'pembimbing_2');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
