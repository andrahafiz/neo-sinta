<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarProyek;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarProyekResource;
use App\Http\Resources\SeminarProyekCollection;
use App\Repositories\Mahasiswa\SeminarProyekRepository;
use App\Http\Requests\Mahasiswa\SeminarProyekStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarProyekUpdateRequest;

class SeminarProyekController extends Controller
{
    /**
     * @var \App\Models\SeminarProyek
     */
    protected $seminarProyekModel;

    /**
     * @var \App\Repositories\Mahasiswa\SeminarProyekRepository
     */
    protected $seminarProyekRepository;

    protected $user;

    /**
     * @param  \App\Models\SeminarProyek  $seminarProyekModel
     * @param  \App\Repositories\SeminarProyekRepository  $seminarProyekRepository
     */
    public function __construct(
        SeminarProyek $seminarProyekModel,
        SeminarProyekRepository $seminarProyekRepository
    ) {
        $this->seminarProyekModel = $seminarProyekModel;
        $this->seminarProyekRepository = $seminarProyekRepository;
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $submission = $this->seminarProyekModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->DataMahasiswa()
            ->paginate($request->query('show'));
        return Response::json(new SeminarProyekCollection($submission));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SeminarProyekStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeminarProyekStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->seminarProyekRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new SeminarProyekResource($submission),
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
    public function show(SeminarProyek $seminar_proyek)
    {
        $seminar_proyek->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarProyekResource($seminar_proyek));
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
     * @param  \App\Http\Requests\Mahasiswa\SeminarProyekUpdateRequest  $request
     * @param  \App\Models\SeminarProyek $seminar_proyek
     * @return \Illuminate\Http\Response
     */
    public function update(SeminarProyekUpdateRequest $request, SeminarProyek $seminar_proyek)
    {
        $updatedSeminarProyek = DB::transaction(function () use ($request, $seminar_proyek) {
            $updatedSeminarProyek = $this->seminarProyekRepository
                ->update($request, $seminar_proyek);
            return $updatedSeminarProyek;
        });
        return Response::json(new SeminarProyekResource($updatedSeminarProyek));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeminarProyek $seminar_proyek)
    {
        $deleteSeminarProyek = DB::transaction(function () use ($seminar_proyek) {
            $deleteSeminarProyek = $this->seminarProyekRepository
                ->delete($seminar_proyek);
            return $deleteSeminarProyek;
        });

        return Response::noContent();
    }
}
