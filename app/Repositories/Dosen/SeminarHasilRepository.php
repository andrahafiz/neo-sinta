<?php

namespace App\Repositories\Dosen;

use App\Contracts\Logging;
use App\Models\SeminarHasil;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dosen\SeminarHasilStoreRequest;
use App\Http\Requests\Dosen\SeminarHasilUpdateRequest;

class SeminarHasilRepository
{

    /**
     * @var \App\Models\SeminarHasil
     */
    protected $seminarHasil;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarHasil  $seminarHasil
     */
    public function __construct(
        SeminarHasil $seminarHasil,
    ) {
        $this->seminarHasil = $seminarHasil;
    }

    /**
     * @param  \App\Http\Requests\Dosen\SeminarHasilUpdateRequest  $request
     * @param  \App\Models\SeminarHasil  $seminar_hasil
     * @return \App\Models\SeminarHasil
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(SeminarHasilUpdateRequest $request, SeminarHasil $seminar_hasil)
    {
        $input = $request->safe(['status', 'note']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $seminar_hasil->status;
        $now = now();

        $seminar_hasil->update([
            'status'      => $status,
            'pic'         => $userId,
            'approval_by' => $userId,
            'note'        => $input['note'] ?? $seminar_hasil->note,
            'approved_at' => $status == SeminarHasil::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == SeminarHasil::STATUS_DECLINE ? $now : null,
        ]);

        return $seminar_hasil;
    }
}
