<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Labelling;
use App\Models\Testing;
use App\Models\Training;

class SplitDataController extends Controller
{
    public function index()
    {
        $totalDataTraining = Training::count();
        $totalDataTesting = Testing::count();
        $totalData = $totalDataTraining + $totalDataTesting;

        // Hindari pembagian nol
        $persentaseTraining = $totalData > 0 ? round(($totalDataTraining / $totalData) * 100) : 0;
        $persentaseTesting = $totalData > 0 ? round(($totalDataTesting / $totalData) * 100) : 0;

        $dataTraining = Training::all();
        $dataTesting = Testing::all();
        return view('split-data', compact('totalDataTraining', 'totalDataTesting', 'dataTraining', 'dataTesting', 'persentaseTraining', 'persentaseTesting'));
    }

    public function process()
    {
        $data = Labelling::all()->toArray();

        if (empty($data)) {
            return back()->with('error', 'Data labelling kosong!');
        }

        $data = collect($data)->shuffle()->values();
        $testSize = 0.2;
        $testCount = (int) round($data->count() * $testSize);

        $testData = $data->slice(0, $testCount);
        $trainData = $data->slice($testCount);

        $trainingInsert = [];
        foreach ($trainData as $item) {
            $trainingInsert[] = [
                'real_text'  => $item['real_text'],
                'clean_text' => $item['clean_text'],
                'sentiment'  => $item['sentiment'],
            ];
        }

        $testingInsert = [];
        foreach ($testData as $item) {
            $testingInsert[] = [
                'real_text'  => $item['real_text'],
                'clean_text' => $item['clean_text'],
                'sentiment'  => $item['sentiment'],
            ];
        }

        Training::insert($trainingInsert);
        Testing::insert($testingInsert);

        return redirect()->back();
    }

    public function destroy()
    {
        Testing::truncate();
        Training::truncate();

        return redirect()->route('split-data')->with('success', 'Data berhasil dihapus.');
    }
}
