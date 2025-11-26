<?php

namespace App\Http\Controllers;

use App\Models\LaporanView;
use App\Models\User;
use App\Models\Aspirasi;
use App\Models\Voting;

class DashboardController extends Controller
{
    public function index()
    {
        $rekap = LaporanView::orderByDesc('total_suara')->get();

        $totalUser     = User::count();
        $totalAspirasi = Aspirasi::count();
        $totalVoting   = Voting::count();

        return view('admin.dashboard', compact('rekap', 'totalUser', 'totalAspirasi', 'totalVoting'));
    }
}
