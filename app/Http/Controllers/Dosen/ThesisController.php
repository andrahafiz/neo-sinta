<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Thesis;
use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\ThesisResource;
use App\Repositories\Dosen\ThesisRepository;

class ThesisController extends Controller
{
    protected $thesisModel;
    protected $thesisRepository;

    public function __construct(
        Thesis $thesisModel,
        ThesisRepository $thesisRepository
    ) {
        $this->thesisModel = $thesisModel;
        $this->thesisRepository = $thesisRepository;
    }

    public function index(Request $request)
    {
        $pembimbing = QueryBuilder::for(Thesis::class)
            ->allowedFilters([
                AllowedFilter::partial('mahasiswa', 'mahasiswa.name'),
                AllowedFilter::partial('pembimbing1', 'pembimbing1.name'),
                AllowedFilter::partial('pembimbing2', 'pembimbing2.name'),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return Response::json(ThesisResource::collection($pembimbing));
    }

    public function assign_pembimbing(Request $request)
    {
        $data = $request->validate([
            'mahasiswa'     => ['required', 'exists:mahasiswas,id'],
            'pembimbing_1'   => ['required', 'exists:lecture,id'],
            'pembimbing_2'   => ['required', 'exists:lecture,id'],
        ]);

        $pembimbing = Thesis::updateOrCreate(
            ['mahasiswas_id' => $data['mahasiswa']],
            [
                'pembimbing_1' => $data['pembimbing_1'],
                'pembimbing_2' => $data['pembimbing_2']
            ]
        );

        return Response::json(new ThesisResource($pembimbing));
    }
}
