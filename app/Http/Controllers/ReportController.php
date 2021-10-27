<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\User;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\Report;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use PDF;
use App\Http\Controllers\App;

class ReportController extends Controller
{
    //
     private $reportRepository;


    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
    public function index()
    {

         return view('admin.report.index', $this->reportRepository->index());
    }
    public function indexpassenger()
    {
        
        return view('passenger.reportpassenger.index', $this->reportRepository->index());
    }

    public function download()
    {
        $report = $this->reportRepository->index();
        $pdf = PDF::loadView('admin.report.index', compact('report'));
        return $pdf->download('MyTransactions.pdf');


            
    
    }

}
