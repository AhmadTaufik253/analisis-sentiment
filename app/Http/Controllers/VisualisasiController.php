<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Testing;
use App\Models\Labelling;
use Illuminate\Support\Facades\DB;

class VisualisasiController extends Controller
{
    // public function index()
    // {
    //     // ==== Contoh data: ganti dengan hasil perhitungan asli ====
    //     // Ringkasan metrik keseluruhan
    //     $metrics = [
    //         'accuracy' => 0.842,     // 84.2%
    //         'precision_macro' => 0.835,
    //         'recall_macro'    => 0.828,
    //         'f1_macro'        => 0.831,
    //     ];

    //     // Distribusi sentimen (total di dataset uji/seluruh data)
    //     // $distribution = [
    //     //     'labels' => ['Positif', 'Netral', 'Negatif'],
    //     //     'values' => [230, 180, 90],
    //     // ];
    //     // Hitung jumlah tiap label
    //     $labelCounts = Labelling::select('sentiment', DB::raw('COUNT(*) as total'))
    //                             ->groupBy('sentiment')
    //                             ->pluck('total', 'sentiment'); // hasil: ['Positif' => 230, 'Netral' => 180, 'Negatif' => 90]
    //     // dd($labelCounts);
    //     // Pastikan urutan sesuai
    //     $distribution = [
    //         'labels' => ['Positif', 'Netral', 'Negatif'],
    //         'values' => [
    //             $labelCounts->get('positif', 0),
    //             $labelCounts->get('netral', 0),
    //             $labelCounts->get('negatif', 0),
    //         ],
    //     ];

    //     // Confusion matrix 3x3 (urut label sama dengan $distribution['labels'])
    //     // rows = actual, cols = predicted
    //     $confusion = [
    //         [180, 30, 20], // actual Positif -> pred [Pos, Net, Neg]
    //         [25, 130, 25], // actual Netral
    //         [10, 20, 60],  // actual Negatif
    //     ];

    //     // Metrik per kelas
    //     $perClass = [
    //         'labels' => ['Positif', 'Netral', 'Negatif'],
    //         'precision' => [0.88, 0.79, 0.84],
    //         'recall'    => [0.78, 0.72, 0.80],
    //         'f1'        => [0.83, 0.75, 0.82],
    //     ];

    //     // Contoh 10 prediksi (gunakan data nyata kamu)
    //     $samples = [
    //         ['text' => 'Pelayanan sangat memuaskan!', 'true' => 'Positif', 'pred' => 'Positif'],
    //         ['text' => 'Biasa saja sih.', 'true' => 'Netral', 'pred' => 'Netral'],
    //         ['text' => 'Buruk sekali pengirimannya', 'true' => 'Negatif', 'pred' => 'Negatif'],
    //         ['text' => 'Lumayan tapi bisa lebih cepat', 'true' => 'Netral', 'pred' => 'Positif'],
    //         ['text' => 'Suka banget! kualitas mantap', 'true' => 'Positif', 'pred' => 'Positif'],
    //         ['text' => 'Tidak sesuai ekspektasi', 'true' => 'Negatif', 'pred' => 'Negatif'],
    //         ['text' => 'Ok lah', 'true' => 'Netral', 'pred' => 'Netral'],
    //         ['text' => 'Parah kecewa', 'true' => 'Negatif', 'pred' => 'Netral'],
    //         ['text' => 'Top!', 'true' => 'Positif', 'pred' => 'Positif'],
    //         ['text' => 'Standar', 'true' => 'Netral', 'pred' => 'Netral'],
    //     ];

    //     return view('visualisasi-data', compact(
    //         'metrics','distribution','confusion','perClass','samples'
    //     ));
    //     // return view('visualisasi-data');
    // }

    public function index()
    {
        // Ambil record terbaru dari tabel result
        $result = DB::table('result')->latest('created_at')->first();

        if (!$result) {
            return back()->with('error', 'Data hasil pengujian belum tersedia.');
        }

        // Labels konsisten
        $labels = ['Positif', 'Netral', 'Negatif'];

        // === Confusion Matrix ===
        // Urutannya: actual positif, netral, negatif
        $confusion = [
            // Actual Positif
            [$result->tp_positive, $result->fp_netral ?? 0, $result->fp_negative ?? 0],
            // Actual Netral
            [$result->fp_positive ?? 0, $result->tp_netral, $result->fp_negative ?? 0],
            // Actual Negatif
            [$result->fp_positive ?? 0, $result->fp_netral ?? 0, $result->tp_negative],
        ];

        // Kalau FP/FN sudah jelas di kolom, bisa langsung susun manual:
        $confusion = [
            // Actual Positif
            [$result->tp_positive, $result->fp_positive, $result->fn_positive],
            // Actual Netral
            [$result->fp_netral, $result->tp_netral, $result->fn_netral],
            // Actual Negatif
            [$result->fp_negative, $result->fn_negative, $result->tp_negative],
        ];

        // === Perhitungan precision, recall, f1 per kelas ===
        $precision = [];
        $recall = [];
        $f1 = [];

        foreach (range(0, 2) as $i) {
            $TP = $confusion[$i][$i];
            $FP = array_sum(array_column($confusion, $i)) - $TP;
            $FN = array_sum($confusion[$i]) - $TP;

            $prec = $TP + $FP > 0 ? $TP / ($TP + $FP) : 0;
            $rec  = $TP + $FN > 0 ? $TP / ($TP + $FN) : 0;
            $f1sc = ($prec + $rec) > 0 ? 2 * ($prec * $rec) / ($prec + $rec) : 0;

            $precision[] = $prec;
            $recall[]    = $rec;
            $f1[]        = $f1sc;
        }

        // === Metrik keseluruhan (macro) ===
        $metrics = [
            'accuracy'        => array_sum(array_map(function($i) use ($confusion){ return $confusion[$i][$i]; }, [0,1,2])) / array_sum(array_map('array_sum', $confusion)),
            'precision_macro' => array_sum($precision) / count($labels),
            'recall_macro'    => array_sum($recall) / count($labels),
            'f1_macro'        => array_sum($f1) / count($labels),
        ];

        // === Distribusi sentimen ===
        $distribution = [
            'labels' => $labels,
            'values' => [
                $result->predict_positive ?? 0,
                $result->predict_netral ?? 0,
                $result->predict_negative ?? 0,
            ]
        ];

        // === Per kelas chart ===
        $perClass = [
            'labels'    => $labels,
            'precision' => $precision,
            'recall'    => $recall,
            'f1'        => $f1,
        ];

        // === Contoh prediksi ===
        // Kalau mau ambil dari tabel prediksi asli, ganti query ini
        $samples = [
            ['text' => 'Contoh teks 1', 'true' => 'Positif', 'pred' => 'Positif'],
            ['text' => 'Contoh teks 2', 'true' => 'Netral', 'pred' => 'Negatif'],
            // ...
        ];

        return view('visualisasi-data', compact(
            'metrics','distribution','confusion','perClass','samples'
        ));
    }
}
