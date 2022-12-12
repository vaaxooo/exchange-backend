<?php

namespace App\Http\Controllers\Admin;

use App\Models\PayMethod;
use Illuminate\Http\Request;
use App\Services\Admin\PayMethodService;
use App\Http\Controllers\Controller;

class PayMethodController extends Controller
{
    private $payMethodService;

    public function __construct()
    {
        $this->payMethodService = new PayMethodService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->payMethodService->index());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->payMethodService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PayMethod  $payMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PayMethod $payMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PayMethod  $payMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(PayMethod $payMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PayMethod  $payMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PayMethod $payMethod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PayMethod  $payMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayMethod $payMethod)
    {
        return response()->json($this->payMethodService->destroy($payMethod));
    }

    /**
     * It activates the pay method.
     * 
     * @param PayMethod payMethod The PayMethod object to be updated.
     * 
     * @return A JSON response with the result of the active method.
     */
    public function active(PayMethod $payMethod)
    {
        return response()->json($this->payMethodService->active($payMethod));
    }
}
