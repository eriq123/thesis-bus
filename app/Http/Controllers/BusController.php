<?php

namespace App\Http\Controllers;

use App\Repositories\BusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BusController extends Controller
{
    private $DEFAULT_REDIRECT_ROUTE = 'buses.index';
    private $busRepository;

    public function __construct(BusRepository $busRepository)
    {
        $this->busRepository = $busRepository;
    }

    public function index()
    {
        return view('admin.bus.index', $this->busRepository->index());
    }

    public function store(Request $request)
    {
        $this->busRepository->store($request);
        return Redirect::route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->busRepository->update($request);
        return Redirect::route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        $this->busRepository->destroy($id);
        return Redirect::route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Deleted Successfully!');
    }
}
