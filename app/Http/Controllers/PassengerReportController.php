<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\User;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;


class PassengerReportController extends Controller
{
    private $reportRepository;


    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
    public function index()
    {

         return view('passenger.reportpassenger.index', $this->reportRepository->index());
    }
}
