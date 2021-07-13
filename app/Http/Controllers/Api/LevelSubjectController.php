<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LevelSubject;
use App\Http\Resources\LevelSubject as LevelSubjectResource;

/**
 * @resource LevelSubjects
 *
 */
class LevelSubjectController extends Controller
{
    /**
     * Display a listing of the levelSubject.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LevelSubjectResource::collection(LevelSubject::all());
    }

}