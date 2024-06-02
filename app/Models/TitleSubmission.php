<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TitleSubmission
 *
 * @property int $id
 * @property string $title
 * @property int|null $status
 * @property int|null $pic
 * @property int $mahasiswas_id
 * @property Carbon|null $proposed_at
 * @property Carbon|null $in_review_at
 * @property Carbon|null $approved_at
 * @property Carbon|null $declined_at
 * @property string $dok_pengajuan_judul
 * @property string $konsentrasi_ilmu
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Mahasiswa $mahasiswa
 * @property Lecture|null $lecture
 *
 * @package App\Models
 */
class TitleSubmission extends Model
{
    use SoftDeletes;
    protected $table = 'title_submission';

    public const STATUS_PROPOSED = 'proposed';

    protected $casts = [
        'pic' => 'int',
        'mahasiswas_id' => 'int',
        'proposed_at' => 'datetime',
        'in_review_at' => 'datetime',
        'approved_at' => 'datetime',
        'declined_at' => 'datetime'
    ];

    protected $fillable = [
        'title',
        'status',
        'pic',
        'mahasiswas_id',
        'proposed_at',
        'in_review_at',
        'approved_at',
        'declined_at',
        'dok_pengajuan_judul',
        'konsentrasi_ilmu'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'pic');
    }
}
