<?php

namespace App\Repositories\Mahasiswa;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\TitleSubmission;
use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\TitleSubmissionCreateRequest;
use App\Http\Requests\TitleSubmissionUpdateRequest;
use App\Repositories\Interface\TitleSubmissionInterface;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\TitleSubmissionRequest;
use App\Http\Requests\Mahasiswa\TitleSubmissionCheckOutRequest;
use App\Http\Requests\Mahasiswa\TitleSubmissionStoreRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TitleSubmissionRepository
{

    /**
     * @var \App\Models\TitleSubmission
     */
    protected $titleSubmission;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\TitleSubmission  $titleSubmission
     */
    public function __construct(
        TitleSubmission $titleSubmission,
    ) {
        $this->titleSubmission = $titleSubmission;
    }

    /**
     * @param  \App\Http\Requests\TitleSubmissionStoreRequest  $request
     * @return \App\Models\TitleSubmission
     */
    public function store(TitleSubmissionStoreRequest $request)
    {
        $input = $request->safe([
            'title', 'dok_pengajuan_judul', 'konsentrasi_ilmu'
        ]);

        $photo = $request->file('dok_pengajuan_judul');
        if ($photo instanceof UploadedFile) {
            $rawPath = $photo->store('public/dokumen/pengajuan_judul');
            $path = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user()->id;
        $submission = $this->titleSubmission->create([
            'title'         => $input['title'],
            'status'        => $this->titleSubmission::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'mahasiswas_id' => $mahasiswa,
            'konsentrasi_ilmu'      => $input['konsentrasi_ilmu'],
            'dok_pengajuan_judul'   => $path ?? null,
        ]);

        Logging::log("CREATE PRODUCT", $submission);
        return $submission;
    }
    // /**
    //  * @param  \App\Http\Requests\TitleSubmissionUpdateRequest  $request
    //  * @param  \App\Models\TitleSubmission  $checkin
    //  * @return \App\Models\TitleSubmission
    //  * @throws \Illuminate\Validation\ValidationException
    //  */
    // public function update(TitleSubmissionUpdateRequest $request, TitleSubmission $checkin)
    // {
    //     $input = $request->safe([
    //         'name',
    //         'cycleTime',
    //     ]);

    //     $checkin->update([
    //         'name'          => $input['name'] ?? $checkin->name,
    //         'cycle_time'    => $input['cycleTime'] ?? $checkin->cycle_time,
    //     ]);


    //     return $checkin;
    // }

    // /**
    //  * @param  \App\Contracts\Request  $request
    //  * @param  \App\Models\TitleSubmission  $checkin
    //  * @return \App\Models\TitleSubmission
    //  */
    // public function delete(Request $request, TitleSubmission $checkin)
    // {
    //     $checkin->delete();


    //     return $checkin;
    // }
}
