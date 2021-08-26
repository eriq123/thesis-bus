<?php

namespace App\Http\Controllers;

use App\Repositories\BusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BusController extends Controller
{
    private $busRepository;

    public function __construct(BusRepository $busRepository)
    {
        $this->busRepository = $busRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.bus.index', $this->busRepository->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->busRepository->store($request);
        return Redirect::route('buses.index')->withSuccess('Added Successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->busRepository->update($request);
        return Redirect::route('buses.index')->withSuccess('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->busRepository->destroy($id);
        return Redirect::route('buses.index')->withSuccess('Deleted Successfully!');
    }
}
