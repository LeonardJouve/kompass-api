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
            'message' => 'required|string',
            'values' => 'sometimes|nullable|json',
            'url' => 'required|string',
            'status' => 'required|integer',
        ]);

        $error = new Error();
        $error->message = $validator['message'];
        $error->values = $validator['values'] ?? null;
        $error->url = $validator['url'];
        $error->status = $validator['status'];
        
        return $error->save() ? response()->json([
            'data' => true,
        ], 201) : response()->json([
            'error' => true,
        ], 400);
    }
}
