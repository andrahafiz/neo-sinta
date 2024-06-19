<?php

namespace App\Repositories\Mahasiswa;

use App\Contracts\Logging;
use App\Models\SeminarProposal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SeminarProposalStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarProposalUpdateRequest;

class SeminarProposalRepository
{

    /**
     * @var \App\Models\SeminarProposal
     */
    protected $seminarProposal;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarProposal  $seminarProposal
     */
    public function __construct(
        SeminarProposal $seminarProposal,
    ) {
        $this->seminarProposal = $seminarProposal;
    }

    /**
     * @param  \App\Http\Requests\SeminarProposalStoreRequest  $request
     * @return \App\Models\SeminarProposal
     */
    public function store(SeminarProposalStoreRequest $request)
    {
        $input = $request->safe([
            'draf_pro', 'pro_ppt', 'dok_persetujuan_pro'
        ]);

        $file_draf_pro = $request->file('draf_pro');
        if ($file_draf_pro instanceof UploadedFile) {
            $rawPath = $file_draf_pro->store('public/dokumen/seminarproposal');
            $path_draf_pro = str_replace('public/', '', $rawPath);
        }

        $file_ppt = $request->file('pro_ppt');
        if ($file_ppt instanceof UploadedFile) {
            $rawPath = $file_ppt->store('public/dokumen/seminarproposal');
            $path_ppt = str_replace('public/', '', $rawPath);
        }
        $file_dok_persetujuan_pro = $request->file('dok_persetujuan_pro');
        if ($file_dok_persetujuan_pro instanceof UploadedFile) {
            $rawPath = $file_dok_persetujuan_pro->store('public/dokumen/seminarproposal');
            $path_dok_persetujuan_pro = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user();
        $seminarProposal = $this->seminarProposal->create([
            'title'         => 'SEMINAR PROPOSAL ' . strtoupper($mahasiswa->name),
            'status'        => $this->seminarProposal::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa->id,
            'draf_pro'  => $path_draf_pro ?? null,
            'pro_ppt'   => $path_ppt ?? null,
            'dok_persetujuan_pro'  => $path_dok_persetujuan_pro ?? null,
        ]);

        Logging::log("CREATE SEMINAR PROPOSAL", $seminarProposal);
        return $seminarProposal;
    }

    /**
     * @param  \App\Http\Requests\SeminarProposalUpdateRequest  $request
     * @param  \App\Models\SeminarProposal  $seminar_proposal
     * @return \App\Models\SeminarProposal
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SeminarProposalUpdateRequest $request, SeminarProposal $seminar_proposal)
    {
        $input = $request->safe([
            'draf_pro', 'pro_ppt', 'dok_persetujuan_pro'
        ]);

        if ($seminar_proposal->status == SeminarProposal::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat diubah dikarenakan telah diapprove']);
        }

        $document = $request->file('draf_pro');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_proposal->draf_pro;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenameDraf = $document->store('public/dokumen/seminarproposal');
            $filenameDraf = str_replace('public/', '', $filenameDraf);
        } else {
            $filenameDraf = $seminar_proposal->draf_pro;
        };

        $document = $request->file('pro_ppt');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_proposal->pro_ppt;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePPT = $document->store('public/dokumen/seminarproposal');
            $filenamePPT = str_replace('public/', '', $filenamePPT);
        } else {
            $filenamePPT = $seminar_proposal->pro_ppt;
        };

        $document = $request->file('dok_persetujuan_pro');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_proposal->dok_persetujuan_pro;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePersetujuan = $document->store('public/dokumen/seminarproposal');
            $filenamePersetujuan = str_replace('public/', '', $filenamePersetujuan);
        } else {
            $filenamePersetujuan = $seminar_proposal->dok_persetujuan_pro;
        };

        $seminar_proposal->update([
            'draf_pro'  => $filenameDraf ?? $seminar_proposal->draf_pro,
            'pro_ppt'   => $filenamePPT ?? $seminar_proposal->pro_ppt,
            'dok_persetujuan_pro'  => $filenamePersetujuan ?? $seminar_proposal->dok_persetujuan_pro,
        ]);

        Logging::log("EDIT SEMINAR PRAPROPOSAL", $seminar_proposal);
        return $seminar_proposal;
    }

    /**
     * @param  \App\Models\SeminarProposal  $seminarProposal
     * @return \App\Models\SeminarProposal
     */
    public function delete(SeminarProposal $seminar_proposal): bool
    {
        if ($seminar_proposal->status == SeminarProposal::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat dihapus dikarenakan telah diapprove']);
        }

        Logging::log("DELETE SEMINAR PRAPROPOSAL", $seminar_proposal);
        $seminarProposal = $seminar_proposal->delete();
        return $seminarProposal;
    }
}
