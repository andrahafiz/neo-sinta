<?php

namespace App\Http\Controllers\Dosen;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarLiteratur;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarLiteraturResource;
use App\Http\Resources\SeminarLiteraturCollection;
use App\Repositories\Dosen\SeminarLiteraturRepository;
use App\Http\Requests\Dosen\SeminarLiteraturStoreRequest;
use App\Http\Requests\Dosen\SeminarLiteraturUpdateRequest;


class SeminarLiteraturController extends Controller
{
    /**
     * @var \App\Models\SeminarLiteratur
     */
    protected $seminarLiteraturModel;

    /**
     * @var \App\Repositories\Dosen\SeminarLiteraturRepository
     */
    protected $seminarLiteraturRepository;

    protected $user;

    /**
     * @param  \App\Models\SeminarLiteratur  $seminarLiteraturModel
     * @param  \App\Repositories\SeminarLiteraturRepository  $seminarLiteraturRepository
     */
    public function __construct(
        SeminarLiteratur $seminarLiteraturModel,
        SeminarLiteraturRepository $seminarLiteraturRepository
    ) {
        $this->seminarLiteraturModel = $seminarLiteraturModel;
        $this->seminarLiteraturRepository = $seminarLiteraturRepository;
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
        $submission = $this->seminarLiteraturModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->paginate($request->query('show'));
        return Response::json(new SeminarLiteraturCollection($submission));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SeminarLiteratur $seminar_literatur)
    {
        $seminar_literatur->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarLiteraturResource($seminar_literatur));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SeminarLiteraturUpdateRequest  $request
     * @param  \App\Models\SeminarLiteratur $seminar_literatur
     * @return \Illuminate\Http\Response
     */
    public function confirm(SeminarLiteraturUpdateRequest $request, SeminarLiteratur $seminar_literatur)
    {
        $updatedSeminarLiteratur = DB::transaction(function () use ($request, $seminar_literatur) {
            $updatedSeminarLiteratur = $this->seminarLiteraturRepository
                ->confirm($request, $seminar_literatur);
            return $updatedSeminarLiteratur;
        });
        return Response::json(new SeminarLiteraturResource($updatedSeminarLiteratur));
    }
}
