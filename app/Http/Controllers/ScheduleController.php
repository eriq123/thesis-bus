<?php

namespace App\Http\Controllers;

use App\Repositories\ScheduleRepository;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private $DEFAULT_REDIRECT_ROUTE = 'buses.schedules.index';
    private $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function index()
    {
        return view('admin.bus.schedules', $this->scheduleRepository->index());
    }

    public function store(Request $request)
    {
        $this->scheduleRepository->store($request);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->scheduleRepository->update($request);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        $this->scheduleRepository->destroy($id);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Deleted Successfully!');
    }
}
