<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\TitleSubmission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitleSubmissionCollection;
use App\Repositories\Mahasiswa\TitleSubmissionRepository;
use App\Http\Requests\Mahasiswa\TitleSubmissionStoreRequest;
use App\Http\Resources\TitleSubmissionResource;

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
    public function show($id)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
