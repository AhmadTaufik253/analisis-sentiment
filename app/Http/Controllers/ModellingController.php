<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelling;
use App\Models\Training;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ModellingController extends Controller
{
    public function index()
    {
        $modellings = Modelling::all();
        return view('modelling', compact('modellings'));
    }

    public function process()
    {
        set_time_limit(300);
        // Ambil data training dari database
        $dataTraining = Training::select('real_text', 'sentiment')->get();

        $documents = [];
        $labels = [];
        foreach ($dataTraining as $data) {
            $documents[] = $data->real_text;
            $labels[] = $data->sentiment;
        }

        // Inisialisasi komponen
        $tokenizer = new \App\Helpers\Tokenizer();
        $vectorizer = new \App\Helpers\Vectorizer($tokenizer);
        $tfidfTransformer = new \App\Helpers\TfIdfTransformer();
        $naiveBayes = new \App\Helpers\NaiveBayes();

        // Latih model
        $vectorizer->fit($documents);
        $vectors = $vectorizer->transform($documents);
        $tfidfTransformer->fit($vectors);
        $tfidfVectors = $tfidfTransformer->transform($vectors);
        $naiveBayes->train($tfidfVectors, $labels);

        // Prediksi
        $predictions = [];
        foreach ($tfidfVectors as $vector) {
            $predictions[] = $naiveBayes->predict($vector);
        }

        // Hitung metrics
        // $metrics = \App\Helpers\MetricsCalculator::calculateMetrics($labels, $predictions);

        // Hitung distribusi label
        $totalPositive = collect($labels)->filter(fn($x) => $x === 'positif')->count();
        $totalNegative = collect($labels)->filter(fn($x) => $x === 'negatif')->count();
        $totalNetral   = collect($labels)->filter(fn($x) => $x === 'netral')->count();

        // Simpan model ke file (serialized)
        $modelData = serialize([$vectorizer, $tfidfTransformer, $naiveBayes]);
        $modelName = 'model_' . Carbon::now()->format('d-m-Y_h-i-s-A') . '.model';
        Storage::put('models/' . $modelName, $modelData);

        // Simpan informasi model ke DB
        Modelling::insert([
            'model_name' => $modelName,
            'model_path' => 'models/' . $modelName,
            'positive_labels' => $totalPositive,
            'negative_labels' => $totalNegative,
            'netral_labels'   => $totalNetral,
            'created_at' => now()
        ]);

        // Kirim respon ke UI
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data_model = Modelling::find($id);
        if ($data_model) {
            $data_model->delete();
            return redirect()->route('modelling')->with('success', 'Data model deleted successfully.');
        } else {
            return redirect()->route('modelling')->with('error', 'Data model not found.');
        }
    }
}
