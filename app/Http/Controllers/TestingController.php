<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelling;
use App\Models\Testing;
use App\Models\Result;
use App\Helpers\Tokenizer;
use App\Helpers\Vectorizer;
use App\Helpers\TfIdfTransformer;
use App\Helpers\NaiveBayes;

class TestingController extends Controller
{
    public function index()
    {
        $data_model = Modelling::select('model_name', 'positif_sentiment', 'negatif_sentiment', 'netral_sentiment')->get();
        $total_data_testing = Testing::count();
        return view('testing', compact('data_model','total_data_testing'));
    }

    public function process(Request $request)
    {
        $namaModel = $request->input('namaModel');
        $modelPath = storage_path("app/models/{$namaModel}");
        
        if (!file_exists($modelPath)) {
            return back()->with('error', 'Model file tidak ditemukan.');
        }

        $modelData = file_get_contents($modelPath);
        [$vectorizer, $tfidfTransformer, $naiveBayes] = unserialize($modelData);

        if (!method_exists($naiveBayes, 'predict')) {
            return back()->with('error', 'Model tidak valid.');
        }

        $testings = Testing::all();

        // Statistik
        $truePositive = $positiveNetral = $falseNegative = 0;
        $trueNegative = $negativeNetral = $falsePositive = 0;
        $trueNetral = $netralPositive = $netralNegative = 0;
        $predictPositive = $predictNegative = $predictNetral = 0;

        foreach ($testings as $index => $testing) {
            $newTextArray = [$testing->real_text];
            $vector = $vectorizer->transform($newTextArray);
            $tfidfVector = $tfidfTransformer->transform($vector);
            $prediction = $naiveBayes->predict($tfidfVector[0]);

            if ($testing->sentiment === "positif") {
                if ($prediction === "positif") {
                    $truePositive++; $predictPositive++;
                } elseif ($prediction === "netral") {
                    $positiveNetral++; $predictNetral++;
                } else {
                    $falseNegative++; $predictNegative++;
                }
            } elseif ($testing->sentiment === "negatif") {
                if ($prediction === "negatif") {
                    $trueNegative++; $predictNegative++;
                } elseif ($prediction === "netral") {
                    $negativeNetral++; $predictNetral++;
                } else {
                    $falsePositive++; $predictPositive++;
                }
            } elseif ($testing->sentiment === "netral") {
                if ($prediction === "netral") {
                    $trueNetral++; $predictNetral++;
                } elseif ($prediction === "positif") {
                    $netralPositive++; $predictPositive++;
                } elseif ($prediction === "negatif") {
                    $netralNegative++; $predictNegative++;
                }
            }
        }

        // Hitung TF-IDF untuk mendapatkan vocabulary dan weight
        $dataUji = $testings->pluck('real_text')->toArray();
        $tokenizer = new Tokenizer();
        $vectorizer2 = new Vectorizer($tokenizer);
        $vectorizer2->fit($dataUji);
        $vocab = $vectorizer2->getVocabulary();

        $transformer = new TfIdfTransformer();
        $tfidfVectors = $transformer->transform($vectorizer2->transform($dataUji));

        $weight = array_map('array_sum', $tfidfVectors);

        $data = [
            'created_at' => now(),
            'data_training' => $request->input('jumlahSentimen'),
            'data_training_positive' => $request->input('trainingPositif'),
            'data_training_negative' => $request->input('trainingNegatif'),
            'data_training_netral' => $request->input('trainingNetral'),
            'data_testing' => count($testings),
            'tp_positive' => $truePositive,
            'fp_positive' => $falsePositive,
            'fn_positive' => $falseNegative,
            'tp_negative' => $trueNegative,
            'fp_negative' => $negativeNetral,
            'fn_negative' => $negativeNetral,
            'tp_netral' => $trueNetral,
            'fp_netral' => $netralPositive,
            'fn_netral' => $netralNegative,
            'predict_positive' => $predictPositive,
            'predict_negative' => $predictNegative,
            'predict_netral' => $predictNetral,
            'vocabulary' => json_encode($vocab),
            'vocab_weight' => json_encode($weight),
        ];

        if (Result::count() === 0) {
            Result::create($data);
        } else {
            Result::query()->update($data);
        }

        return redirect()->back()->with('success', 'Berhasil Pengujian!');
    }
}
