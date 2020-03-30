<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Field::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
            'type' => ['required', Rule::in(Field::TYPES)]
        ]);

        return response()->json(Field::create($validated), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Field $field)
    {
        return response()->json($field->toArray(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Field $field
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Field $field)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
        ]);

        $field->update($validated);

        return response()->json($field->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Field $field
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Field $field)
    {
        $field->delete();

        return response()->json([], 200);
    }
}
