<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportData;
use App\Models\Preprocessing;
use App\Models\Labelling;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRawData = ImportData::count();
        $totalPreprocessingData = Preprocessing::count();
        $totalLabellingData = Labelling::count();
        
        return view('dashboard', compact('totalRawData', 'totalPreprocessingData', 'totalLabellingData'));
    }

    public function chartData()
    {
        $data = DB::table('labelling')
            ->select('sentiment', DB::raw('COUNT(*) as total'))
            ->groupBy('sentiment')
            ->get();
        \Log::info($data);
        // Bikin array untuk label dan data
        $categories = $data->pluck('sentiment');
        $values = $data->pluck('total');

        return response()->json([
            'categories' => $categories,
            'values' => $values
        ]);
    }
}
