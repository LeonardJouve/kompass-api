<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Error;

class ErrorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $errors = Error::all();
        return response()->json($errors, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'values' => 'required|array',
            'url' => 'required|string',
            'status' => 'required|integer',
        ]);

        $result = Error::create($validator)->save();
        
        return $result ? response()->json([
            'data' => true,
        ], 201) : response()->json([
            'error' => true,
        ], 400);
    }
}
