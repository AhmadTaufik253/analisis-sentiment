@extends('layouts.layout')
@section('split-data', 'active-page')
@section('content')
<style>
    #chartjs4 {
        max-width: 1000px;
        max-height: 1000px;
    }
</style>
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Split Data</h1>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SplitDataModal">Split Data</button>
                        <div class="modal fade" id="SplitDataModal" tabindex="-1" aria-labelledby="SplitDataModalTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="SplitDataModalTitle">Split Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('split-data.process') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Start Split.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#DeleteDataModal">Delete Data</button>
                        <div class="modal fade" id="DeleteDataModal" tabindex="-1" aria-labelledby="DeleteDataModalTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="DeleteDataModalTitle">Split Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('split-data.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the data?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="card-title">Hasil Split Data</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tipe Data</th>
                                                <th scope="col">Jumlah Data</th>
                                                <th scope="col">Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">Data Latih</th>
                                                <td>{{ $totalDataTraining ?? 0 }}</td>
                                                <td>{{ $persentaseTraining ?? 0 }}%</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Data Uji</th>
                                                <td>{{ $totalDataTesting ?? 0 }}</td>
                                                <td>{{ $persentaseTesting ?? 0 }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="chartjs4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="card-title">Preview Data</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="splitDataTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="train-tab" data-bs-toggle="tab" data-bs-target="#train" type="button" role="tab">Data Latih</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="test-tab" data-bs-toggle="tab" data-bs-target="#test" type="button" role="tab">Data Uji</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="splitDataTabsContent">
                                <div class="tab-pane fade show active" id="train" role="tabpanel">
                                    <div class="table-responsive">
                                        <table id="datatable1" class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="col-9">Real Teks</th>
                                                    <th class="col-9">Clean Teks</th>
                                                    <th class="col-3">Label Sentiment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($dataTraining ?? [] as $index => $row)
                                                    <tr>
                                                        <td class="col-5">{{ $row->real_text }}</td>
                                                        <td class="col-5">{{ $row->clean_text }}</td>
                                                        <td class="col-4">{{ ucfirst($row->sentiment) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Belum ada data latih.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="test" role="tabpanel">
                                    <div class="table-responsive">
                                        <table id="datatable1-1" class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="col-5">Real Teks</th>
                                                    <th class="col-5">Clean Teks</th>
                                                    <th class="col-4">Label Sentiment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($dataTesting ?? [] as $index => $row)
                                                    <tr>
                                                        <td class="col-5">{{ $row->real_text }}</td>
                                                        <td class="col-5">{{ $row->clean_text }}</td>
                                                        <td class="col-4">{{ ucfirst($row->sentiment) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Belum ada data uji.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/plugins/chartjs/chart.bundle.min.js') }}"></script>
<script>
    const ctx = document.getElementById("chartjs4").getContext("2d");

    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Data Training", "Data Testing"],
            datasets: [{
                label: "Persentase Data",
                data: [
                    {{ $totalDataTraining ?? 0 }},
                    {{ $totalDataTesting ?? 0 }}
                ],
                backgroundColor: [
                    "rgb(54, 162, 235)",
                    "rgb(255, 205, 86)"
                ]
            }]
        }
    });
</script>
@endsection
