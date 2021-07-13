<?php

namespace App\Http\Controllers\Api;

use App\Pack;
use App\Subscription;
use App\Token;
use App\Transaction;
use App\TransactionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pack as PackResource;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Pack
 *
 */
class PackController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    /**
     * Display a listing of the pack.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $packs = Pack::where('enabled', 1)->get();
        return PackResource::collection($packs);
    }

    /**
     * Display a listing of the pack by level Id.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function byLevel($levelId)
    {
        $packs = Pack::where('level_id', $levelId)->where('enabled', 1)->get();
        return PackResource::collection($packs);
    }
}
