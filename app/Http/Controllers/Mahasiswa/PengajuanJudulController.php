<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\TitleSubmission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitleSubmissionResource;
use App\Http\Resources\TitleSubmissionCollection;
use App\Repositories\Mahasiswa\TitleSubmissionRepository;
use App\Http\Requests\Mahasiswa\TitleSubmissionStoreRequest;
use App\Http\Requests\Mahasiswa\TitleSubmissionUpdateRequest;

class PengajuanJudulController extends Controller
{
    /**
     * @var \App\Models\TitleSubmission
     */
    protected $titleSubmissionModel;

    /**
     * @var \App\Repositories\Mahasiswa\TitleSubmissionRepository
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\TitleSubmissionStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TitleSubmissionStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->titleSubmissionRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new TitleSubmissionResource($submission),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\TitleSubmissionUpdateRequest  $request
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
