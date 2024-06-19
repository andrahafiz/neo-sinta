<?php

namespace App\Repositories\Mahasiswa;

use App\Contracts\Logging;
use App\Models\SeminarLiteratur;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SeminarLiteraturStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarLiteraturUpdateRequest;

class SeminarLiteraturRepository
{

    /**
     * @var \App\Models\SeminarLiteratur
     */
    protected $seminarLiteratur;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarLiteratur  $seminarLiteratur
     */
    public function __construct(
        SeminarLiteratur $seminarLiteratur,
    ) {
        $this->seminarLiteratur = $seminarLiteratur;
    }

    /**
     * @param  \App\Http\Requests\SeminarLiteraturStoreRequest  $request
     * @return \App\Models\SeminarLiteratur
     */
    public function store(SeminarLiteraturStoreRequest $request)
    {
        $input = $request->safe([
            'file_ppt', 'file_literatur'
        ]);

        $file_ppt = $request->file('file_ppt');
        if ($file_ppt instanceof UploadedFile) {
            $rawPath = $file_ppt->store('public/dokumen/seminarliteratur');
            $path_ppt = str_replace('public/', '', $rawPath);
        }

        $file_literatur = $request->file('file_literatur');
        $path_literatur = array();
        foreach ($file_literatur as $photo) {
            if ($photo instanceof UploadedFile) {
                $rawPath = $photo->store('public/dokumen/seminarliteratur');
                $path_literatur[] = str_replace('public/', '', $rawPath);
            }
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user()->id;
        $seminarLiteratur = $this->seminarLiteratur->create([
            'status'        => $this->seminarLiteratur::STATUS_PROPOSED,
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa,
            'check_in_ppt'  => $path_ppt ?? null,
            'check_in_literatur' => json_encode($path_literatur) ?? null,
        ]);

        Logging::log("CREATE SEMINAR LITERARUR", $seminarLiteratur);
        return $seminarLiteratur;
    }

    /**
     * @param  \App\Http\Requests\SeminarLiteraturUpdateRequest  $request
     * @param  \App\Models\SeminarLiteratur  $seminar_literatur
     * @return \App\Models\SeminarLiteratur
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SeminarLiteraturUpdateRequest $request, SeminarLiteratur $seminar_literatur)
    {
        $input = $request->safe([
            'file_ppt', 'file_literatur'
        ]);

        if ($seminar_literatur->status == SeminarLiteratur::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat diubah dikarenakan telah diapprove']);
        }

        $document = $request->file('file_ppt');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_literatur->check_in_ppt;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePPT = $document->store('public/dokumen/seminarliteratur');
            $filenamePPT = str_replace('public/', '', $filenamePPT);
        } else {
            $filenamePPT = $seminar_literatur->check_in_ppt;
        };

        $file_literatur = $request->file('file_literatur');
        if ($request->has('file_literatur')) {
            $literatur = json_decode($seminar_literatur->check_in_literatur);
            foreach ($literatur as $row) {
                $file_path = storage_path() . '/app/public/' . $row;
                if (File::exists($file_path)) {
                    unlink($file_path);
                }
            }

            $file_literatur = $request->file('file_literatur');
            $path_literatur = array();
            foreach ($file_literatur as $photo) {
                if ($photo instanceof UploadedFile) {
                    $rawPath = $photo->store('public/dokumen/seminarliteratur');
                    $path_literatur[] = str_replace('public/', '', $rawPath);
                }
            }
        }

        $seminar_literatur->update([
            'status'        => $this->seminarLiteratur::STATUS_PROPOSED,
            'date'          => now(),
            'check_in_ppt'  => $filenamePPT,
            'check_in_literatur' => json_encode($path_literatur),
        ]);

        Logging::log("EDIT SEMINAR LITERATUR", $seminar_literatur);
        return $seminar_literatur;
    }

    /**
     * @param  \App\Models\SeminarLiteratur  $seminarLiteratur
     * @return \App\Models\SeminarLiteratur
     */
    public function delete(SeminarLiteratur $seminarLiteratur): bool
    {
        Logging::log("DELETE SEMINAR LITERATUR", $seminarLiteratur);
        if ($seminarLiteratur->status == SeminarLiteratur::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat dihapus dikarenakan telah diapprove']);
        }

        $seminarLiteratur = $seminarLiteratur->delete();
        return $seminarLiteratur;
    }
}
