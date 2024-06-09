<?php

namespace App\Repositories\Mahasiswa;

use App\Contracts\Logging;
use App\Models\SeminarPraProposal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SeminarPraProposalStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarPraProposalUpdateRequest;

class SeminarPraProposalRepository
{

    /**
     * @var \App\Models\SeminarPraProposal
     */
    protected $seminarPraProposal;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarPraProposal  $seminarPraProposal
     */
    public function __construct(
        SeminarPraProposal $seminarPraProposal,
    ) {
        $this->seminarPraProposal = $seminarPraProposal;
    }

    /**
     * @param  \App\Http\Requests\SeminarPraProposalStoreRequest  $request
     * @return \App\Models\SeminarPraProposal
     */
    public function store(SeminarPraProposalStoreRequest $request)
    {
        $input = $request->safe([
            'draf_pra_pro', 'pra_pro_ppt', 'dok_persetujuan_pra_pro'
        ]);

        $file_draf_pra_pro = $request->file('draf_pra_pro');
        if ($file_draf_pra_pro instanceof UploadedFile) {
            $rawPath = $file_draf_pra_pro->store('public/dokumen/seminarpraproposal');
            $path_draf_pra_pro = str_replace('public/', '', $rawPath);
        }

        $file_ppt = $request->file('pra_pro_ppt');
        if ($file_ppt instanceof UploadedFile) {
            $rawPath = $file_ppt->store('public/dokumen/seminarpraproposal');
            $path_ppt = str_replace('public/', '', $rawPath);
        }
        $file_dok_persetujuan_pra_pro = $request->file('dok_persetujuan_pra_pro');
        if ($file_dok_persetujuan_pra_pro instanceof UploadedFile) {
            $rawPath = $file_dok_persetujuan_pra_pro->store('public/dokumen/seminarpraproposal');
            $path_dok_persetujuan_pra_pro = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user();
        $seminarPraProposal = $this->seminarPraProposal->create([
            'title'         => 'SEMINAR PRA PROPSAL ' . strtoupper($mahasiswa->name),
            'status'        => $this->seminarPraProposal::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa->id,
            'draf_pra_pro'  => $path_draf_pra_pro ?? null,
            'pra_pro_ppt'   => $path_ppt ?? null,
            'dok_persetujuan_pra_pro'  => $path_dok_persetujuan_pra_pro ?? null,
        ]);

        Logging::log("CREATE SEMINAR PRAPROPOSAL", $seminarPraProposal);
        return $seminarPraProposal;
    }

    /**
     * @param  \App\Http\Requests\SeminarPraProposalUpdateRequest  $request
     * @param  \App\Models\SeminarPraProposal  $seminar_praproposal
     * @return \App\Models\SeminarPraProposal
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SeminarPraProposalUpdateRequest $request, SeminarPraProposal $seminar_praproposal)
    {
        $input = $request->safe([
            'draf_pra_pro', 'pra_pro_ppt', 'dok_persetujuan_pra_pro'
        ]);
        $document = $request->file('draf_pra_pro');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_praproposal->draf_pra_pro;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenameDraf = $document->store('public/dokumen/seminarpraproposal');
            $filenameDraf = str_replace('public/', '', $filenameDraf);
        } else {
            $filenameDraf = $seminar_praproposal->check_in_ppt;
        };

        $document = $request->file('pra_pro_ppt');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_praproposal->pra_pro_ppt;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePPT = $document->store('public/dokumen/seminarpraproposal');
            $filenamePPT = str_replace('public/', '', $filenamePPT);
        } else {
            $filenamePPT = $seminar_praproposal->check_in_ppt;
        };

        $document = $request->file('dok_persetujuan_pra_pro');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_praproposal->dok_persetujuan_pra_pro;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePersetujuan = $document->store('public/dokumen/seminarpraproposal');
            $filenamePersetujuan = str_replace('public/', '', $filenamePersetujuan);
        } else {
            $filenamePersetujuan = $seminar_praproposal->check_in_ppt;
        };

        $seminar_praproposal->update([
            'draf_pra_pro'  => $filenameDraf ?? $seminar_praproposal->draf_pra_pro,
            'pra_pro_ppt'   => $filenamePPT ?? $seminar_praproposal->pra_pro_ppt,
            'dok_persetujuan_pra_pro'  => $filenamePersetujuan ?? $seminar_praproposal->dok_persetujuan_pra_pro,
        ]);

        Logging::log("EDIT SEMINAR PRAPROPOSAL", $seminar_praproposal);
        return $seminar_praproposal;
    }

    /**
     * @param  \App\Models\SeminarPraProposal  $seminarPraProposal
     * @return \App\Models\SeminarPraProposal
     */
    public function delete(SeminarPraProposal $seminar_praproposal): bool
    {
        if ($seminar_praproposal->status == SeminarPraProposal::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat dihapus dikarenakan telah diapprove']);
        }

        Logging::log("DELETE SEMINAR PRAPROPOSAL", $seminar_praproposal);
        $seminarPraProposal = $seminar_praproposal->delete();
        return $seminarPraProposal;
    }
}
