@extends('layouts.layout')
@section('visualisasi', 'active-page')
@section('content')
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Visualisasi</h1>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="card-title">Distribusi Sentiment</h4>
                        </div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="card-title">Akurasi Model</h4>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="card-title">Confusion Matrix</h4>
                        </div>
                        <div class="card-body">
                       
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                {{-- Sidebar contoh (jika layout-mu sudah ada sidebar, abaikan bagian ini) --}}
                {{-- <div class="col-12 col-lg-2"> ... </div> --}}

                <div class="col-12">
                    {{-- Header --}}
                    {{-- <div class="d-flex justify-content-between align-items-center my-3">
                        <h4 class="mb-0">Visualisasi Hasil</h4>
                        <div class="text-muted">Analisis Sentimen</div>
                    </div> --}}

                    {{-- Ringkasan Metrik --}}
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="fw-semibold text-muted">Accuracy</div>
                                    <div class="display-6">{{ number_format($metrics['accuracy']*100, 2) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="fw-semibold text-muted">Precision (Macro)</div>
                                    <div class="display-6">{{ number_format($metrics['precision_macro']*100, 2) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="fw-semibold text-muted">Recall (Macro)</div>
                                    <div class="display-6">{{ number_format($metrics['recall_macro']*100, 2) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="fw-semibold text-muted">F1-Score (Macro)</div>
                                    <div class="display-6">{{ number_format($metrics['f1_macro']*100, 2) }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Chart: Distribusi & Per Kelas --}}
                    <div class="row g-3 mt-1">
                        <div class="col-lg-5">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white fw-semibold">Distribusi Sentimen</div>
                                <div class="card-body">
                                    <div style="height:340px">
                                        <canvas id="distChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white fw-semibold">Metrik Per Kelas</div>
                                <div class="card-body">
                                    <div style="height:340px">
                                        <canvas id="perClassChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Confusion Matrix (Table + Heat) --}}
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-white fw-semibold">Confusion Matrix</div>
                        <div class="card-body">
                            @php
                                $labels = $distribution['labels'];
                                $maxVal = 0;
                                foreach ($confusion as $r) { $maxVal = max($maxVal, ...$r); }
                                // helper fungsi heat (0..1)
                                function heatColor($val, $max) {
                                    if ($max == 0) return 'rgba(0,0,0,0)';
                                    $alpha = max(0.1, $val / $max); // 0.1 - 1
                                    // warna biru muda -> biru tua
                                    return "rgba(13,110,253,{$alpha})";
                                }
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-start">Actual \ Predicted</th>
                                            @foreach($labels as $lab)
                                                <th>{{ $lab }}</th>
                                            @endforeach
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($confusion as $i => $row)
                                            @php $rowSum = array_sum($row); @endphp
                                            <tr>
                                                <th class="text-start">{{ $labels[$i] }}</th>
                                                @foreach($row as $val)
                                                    <td style="background: {{ heatColor($val,$maxVal) }}; color:#fff;">
                                                        <strong>{{ $val }}</strong>
                                                    </td>
                                                @endforeach
                                                <th>{{ $rowSum }}</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            @for($c=0; $c<count($labels); $c++)
                                                @php $colSum = array_sum(array_column($confusion, $c)); @endphp
                                                <th>{{ $colSum }}</th>
                                            @endfor
                                            <th>{{ array_sum(array_map('array_sum', $confusion)) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <small class="text-muted d-block">Semakin gelap = jumlah lebih besar.</small>
                        </div>
                    </div>

                    {{-- Contoh Prediksi --}}
                    {{-- <div class="card shadow-sm mt-3 mb-5">
                        <div class="card-header bg-white fw-semibold">Contoh Prediksi (Sampel)</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:5%">#</th>
                                            <th style="width:55%">Teks</th>
                                            <th style="width:20%">Label Asli</th>
                                            <th style="width:20%">Prediksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($samples as $idx => $s)
                                            @php $ok = $s['true'] === $s['pred']; @endphp
                                            <tr class="{{ $ok ? 'table-success' : 'table-danger' }}">
                                                <td>{{ $idx+1 }}</td>
                                                <td>{{ $s['text'] }}</td>
                                                <td>{{ $s['true'] }}</td>
                                                <td>{{ $s['pred'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Baris hijau = prediksi benar, merah = salah.</small>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari controller
    const distLabels = @json($distribution['labels']);
    const distValues = @json($distribution['values']);

    const perClassLabels = @json($perClass['labels']);
    const perClassPrecision = @json($perClass['precision']);
    const perClassRecall = @json($perClass['recall']);
    const perClassF1 = @json($perClass['f1']);

    // Doughnut Distribusi
    // new Chart(document.getElementById('distChart'), {
    //     type: 'doughnut',
    //     data: {
    //         labels: distLabels,
    //         datasets: [{
    //             data: distValues,
    //             backgroundColor: ['#198754','#ffc107','#dc3545']
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         plugins: { legend: { position: 'bottom' } }
    //     }
    // });
    new Chart(document.getElementById("distChart"), {
        type: "doughnut",
        data: {
            labels: @json($distribution['labels']),
            datasets: [{
                data: @json($distribution['values']),
                backgroundColor: ["#4caf50", "#2196f3", "#f44336"]
            }]
        }
    });

    // Bar Metrik Per Kelas
    new Chart(document.getElementById('perClassChart'), {
        type: 'bar',
        data: {
            labels: perClassLabels,
            datasets: [
                { label: 'Precision', data: perClassPrecision.map(v => (v*100).toFixed(2)) },
                { label: 'Recall',    data: perClassRecall.map(v => (v*100).toFixed(2)) },
                { label: 'F1-Score',  data: perClassF1.map(v => (v*100).toFixed(2)) },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
            },
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection
