<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\CoinService;

class CoinController extends Controller
{
    private $coinService;

    /**
     * The constructor function is called when the class is instantiated. It is used to initialize the
     * class
     */
    public function __construct()
    {
        $this->coinService = new CoinService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json($this->coinService->index($request));
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
        return response()->json($this->coinService->store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function show(Coin $coin)
    {
        return response()->json($this->coinService->show($coin));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function edit(Coin $coin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coin $coin)
    {
        return response()->json($this->coinService->update($request, $coin));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coin $coin)
    {
        return response()->json($this->coinService->destroy($coin));
    }


    /**
     * It takes a request and a coin, and returns a json response of the result of the active function in
     * the coin service.
     * 
     * @param Request request The request object
     * @param Coin coin The coin model
     * 
     * @return A JSON response with the result of the active method in the CoinService.
     */
    public function active(Request $request, Coin $coin)
    {
        return response()->json($this->coinService->active($request, $coin));
    }
}
