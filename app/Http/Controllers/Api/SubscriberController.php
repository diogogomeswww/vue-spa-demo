<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\Subscriber\AddSubscriberJob;
use App\Models\Subscriber;
use App\Rules\EmailHostIsActiveRule;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Subscriber::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /** @var AddSubscriberJob $cmd */
        $cmd = AddSubscriberJob::dispatchNow(
            $request->only('name', 'email', 'fields')
        );

        if ($cmd->getValidation()->count()) {
            $errors = $cmd->getValidation()->all();

            return response()->json(compact('errors'), 422);
        }

        return response()->json($cmd->getSubscriber()->fresh(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Subscriber $subscriber)
    {
        return response()->json($subscriber->toArray(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        logger(__METHOD__, $subscriber->toArray());

        $validator = validator()->make($request->only('name', 'email'), [
            'email' => ['bail', 'sometimes', 'required', 'email', new EmailHostIsActiveRule],
            'name' => 'sometimes|required',
            'fields' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            $errors = $validator->getMessageBag()->all();

            return response()->json(compact('errors'), 422);
        }

        $subscriber->update($request->only('email', 'name'));

        foreach($request->input('fields', []) as $id => $value) {
            $subscriber->syncField($id, $value);
        }

        return response()->json($subscriber->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Subscriber $subscriber
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->json([], 200);
    }
}
