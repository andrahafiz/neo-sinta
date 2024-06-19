<?php

namespace App\Http\Controllers\Dosen;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SidangMejaHijau;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SidangMejaHijauResource;
use App\Http\Resources\SidangMejaHijauCollection;
use App\Repositories\Dosen\SidangMejaHijauRepository;
use App\Http\Requests\Dosen\SidangMejaHijauStoreRequest;
use App\Http\Requests\Dosen\SidangMejaHijauUpdateRequest;


class SidangMejaHIjauController extends Controller
{
    /**
     * @var \App\Models\SidangMejaHijau
     */
    protected $sidangMejaHijauModel;

    /**
     * @var \App\Repositories\Dosen\SidangMejaHijauRepository
     */
    protected $sidangMejaHijauRepository;

    protected $user;

    /**
     * @param  \App\Models\SidangMejaHijau  $sidangMejaHijauModel
     * @param  \App\Repositories\SidangMejaHijauRepository  $sidangMejaHijauRepository
     */
    public function __construct(
        SidangMejaHijau $sidangMejaHijauModel,
        SidangMejaHijauRepository $sidangMejaHijauRepository
    ) {
        $this->sidangMejaHijauModel = $sidangMejaHijauModel;
        $this->sidangMejaHijauRepository = $sidangMejaHijauRepository;
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
        $submission = $this->sidangMejaHijauModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->paginate($request->query('show'));
        return Response::json(new SidangMejaHijauCollection($submission));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SidangMejaHijau $sidang_meja_hijau)
    {
        $sidang_meja_hijau->load(['mahasiswa', 'lecture']);
        return Response::json(new SidangMejaHijauResource($sidang_meja_hijau));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SidangMejaHijauUpdateRequest  $request
     * @param  \App\Models\SidangMejaHijau $sidang_meja_hijau
     * @return \Illuminate\Http\Response
     */
    public function confirm(SidangMejaHijauUpdateRequest $request, SidangMejaHijau $sidang_meja_hijau)
    {
        $updatedSidangMejaHijau = DB::transaction(function () use ($request, $sidang_meja_hijau) {
            $updatedSidangMejaHijau = $this->sidangMejaHijauRepository
                ->confirm($request, $sidang_meja_hijau);
            return $updatedSidangMejaHijau;
        });
        return Response::json(new SidangMejaHijauResource($updatedSidangMejaHijau));
    }
}
