<?php

namespace App\Http\Controllers\Dosen;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\TitleSubmission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitleSubmissionResource;
use App\Http\Resources\TitleSubmissionCollection;
use App\Repositories\Dosen\TitleSubmissionRepository;
use App\Http\Requests\Dosen\TitleSubmissionStoreRequest;
use App\Http\Requests\Dosen\TitleSubmissionUpdateRequest;

class PengajuanJudulController extends Controller
{
    /**
     * @var \App\Models\TitleSubmission
     */
    protected $titleSubmissionModel;

    /**
     * @var \App\Repositories\Dosen\TitleSubmissionRepository
     */
    protected $titleSubmissionRepository;

    /**
     * @param  \App\Models\TitleSubmission  $titleSubmissionModel
     * @param  \App\Repositories\TitleSubmissionRepository  $titleSubmissionRepository
     */
    public function __construct(
        TitleSubmission $titleSubmissionModel,
        TitleSubmissionRepository $titleSubmissionRepository
    ) {
        $this->titleSubmissionModel = $titleSubmissionModel;
        $this->titleSubmissionRepository = $titleSubmissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $submission = $this->titleSubmissionModel->with('mahasiswa')
            ->orderByDesc('created_at')->paginate($request->query('show'));

        return Response::json(new TitleSubmissionCollection($submission));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TitleSubmission $pengajuan_judul)
    {
        $pengajuan_judul->load(['mahasiswa', 'lecture']);
        return Response::json(new TitleSubmissionResource($pengajuan_judul));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\TitleSubmissionUpdateRequest  $request
     * @param  \App\Models\TitleSubmission $pengajuan_judul
     * @return \Illuminate\Http\Response
     */
    public function update(TitleSubmissionUpdateRequest $request, TitleSubmission $pengajuan_judul)
    {
        $updatedTitleSubmission = DB::transaction(function () use ($request, $pengajuan_judul) {
            $updatedTitleSubmission = $this->titleSubmissionRepository
                ->update($request, $pengajuan_judul);
            return $updatedTitleSubmission;
        });
        return Response::json(new TitleSubmissionResource($updatedTitleSubmission));
    }
}
