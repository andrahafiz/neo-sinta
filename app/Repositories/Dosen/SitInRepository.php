<?php

namespace App\Repositories\Dosen;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\SitIn;
use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\SitInCreateRequest;
use App\Http\Requests\SitInUpdateRequest;
use App\Repositories\Interface\SitInInterface;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SitInRepository
{

    /**
     * @var \App\Models\SitIn
     */
    protected $sitInModel;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SitIn  $sitInModel
     */
    public function __construct(
        SitIn $sitInModel,
    ) {
        $this->sitInModel = $sitInModel;
    }


    /**
     * @return \App\Models\SitIn $sitIn
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(Request $request, SitIn $sitIn)
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,decline'],
        ]);
        $status = $data['action'] == 'approve' ? SitIn::STATUS_APPROVE : Sitin::STATUS_DECLINE;

        $sitIn->update([
            'status' => $status
        ]);

        return $sitIn;
    }

    // /**
    //  * @param  \App\Http\Requests\SitInUpdateRequest  $request
    //  * @param  \App\Models\SitIn  $checkin
    //  * @return \App\Models\SitIn
    //  * @throws \Illuminate\Validation\ValidationException
    //  */
    // public function update(SitInUpdateRequest $request, SitIn $checkin)
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
    //  * @param  \App\Models\SitIn  $checkin
    //  * @return \App\Models\SitIn
    //  */
    // public function delete(Request $request, SitIn $checkin)
    // {
    //     $checkin->delete();


    //     return $checkin;
    // }
}
