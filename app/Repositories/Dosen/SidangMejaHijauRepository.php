<?php

namespace App\Repositories\Dosen;

use App\Contracts\Logging;
use App\Models\SidangMejaHijau;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dosen\SidangMejaHijauStoreRequest;
use App\Http\Requests\Dosen\SidangMejaHijauUpdateRequest;

class SidangMejaHijauRepository
{

    /**
     * @var \App\Models\SidangMejaHijau
     */
    protected $sidangMejaHijau;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SidangMejaHijau  $sidangMejaHijau
     */
    public function __construct(
        SidangMejaHijau $sidangMejaHijau,
    ) {
        $this->sidangMejaHijau = $sidangMejaHijau;
    }

    /**
     * @param  \App\Http\Requests\Dosen\SidangMejaHijauUpdateRequest  $request
     * @param  \App\Models\SidangMejaHijau  $sidang_meja_hijau
     * @return \App\Models\SidangMejaHijau
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(SidangMejaHijauUpdateRequest $request, SidangMejaHijau $sidang_meja_hijau)
    {
        $input = $request->safe(['status', 'note']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $sidang_meja_hijau->status;
        $now = now();

        $sidang_meja_hijau->update([
            'status'      => $status,
            'pic'         => $userId,
            'approval_by' => $userId,
            'note'        => $input['note'] ?? $sidang_meja_hijau->note,
            'approved_at' => $status == SidangMejaHijau::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == SidangMejaHijau::STATUS_DECLINE ? $now : null,
        ]);

        return $sidang_meja_hijau;
    }
}
