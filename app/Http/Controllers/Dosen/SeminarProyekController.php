<?php

namespace App\Http\Controllers\Dosen;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarProyek;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarProyekResource;
use App\Http\Resources\SeminarProyekCollection;
use App\Repositories\Dosen\SeminarProyekRepository;
use App\Http\Requests\Dosen\SeminarProyekStoreRequest;
use App\Http\Requests\Dosen\SeminarProyekUpdateRequest;


class SeminarProyekController extends Controller
{
    /**
     * @var \App\Models\SeminarProyek
     */
    protected $seminarProyekModel;

    /**
     * @var \App\Repositories\Dosen\SeminarProyekRepository
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
            ->paginate($request->query('show'));
        return Response::json(new SeminarProyekCollection($submission));
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SeminarProyekUpdateRequest  $request
     * @param  \App\Models\SeminarProyek $seminar_proyek
     * @return \Illuminate\Http\Response
     */
    public function confirm(SeminarProyekUpdateRequest $request, SeminarProyek $seminar_proyek)
    {
        $updatedSeminarProyek = DB::transaction(function () use ($request, $seminar_proyek) {
            $updatedSeminarProyek = $this->seminarProyekRepository
                ->confirm($request, $seminar_proyek);
            return $updatedSeminarProyek;
        });
        return Response::json(new SeminarProyekResource($updatedSeminarProyek));
    }
}
