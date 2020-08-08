<?php

namespace App\Http\Controllers\Statuses;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FetchStatusesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $statuses = Status::all();
        return response()->json($statuses);
    }
}
