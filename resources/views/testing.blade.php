@extends('layouts.layout')
@section('testing', 'active-page')
@section('content')
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Testing</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h4 class="header-title">Pilih Model :</h4>
                                <select class="form-control mt-2" name="model" id="model" onchange="onchangeModel(this.value)">
                                    <option hidden>-- Silahkan Pilih Model --</option>
                                    @foreach ($data_model as $model)
                                    @php
                                        $totalLabels = $model->positive_labels + $model->negative_labels + $model->netral_labels;
                                    @endphp
                                        <option value="{{ $totalLabels  }}, {{ $model->positive_labels }}, {{ $model->negative_labels }}, {{ $model->netral_labels }}, {{ $model->model_name }}">{{ $model->model_name }}</option>
                                    @endforeach
                                    {{-- @foreach ($models as $model)
                                        <option value='{!! json_encode([
                                            "total" => $model->total,
                                            "positive" => $model->positive,
                                            "negative" => $model->negative,
                                            "netral" => $model->netral,
                                            "name" => $model->model_name
                                        ]) !!}'>
                                            {{ $model->model_name }}
                                        </option>
                                    @endforeach --}}

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-primary">
                                    <i class="material-icons-outlined">paid</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Data Testing</span>
                                    <span class="widget-stats-amount">{{ $total_data_testing }}</span>
                                </div>
                                <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start">
                                    <i class="material-icons">keyboard_arrow_down</i> 4%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-primary">
                                    <i class="material-icons-outlined">paid</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Raw Data</span>
                                    <span class="widget-stats-amount" id="text_total">0</span>
                                </div>
                                <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start">
                                    <i class="material-icons">keyboard_arrow_down</i> 4%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-primary">
                                    <i class="material-icons-outlined">paid</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Sentiment Positif</span>
                                    <span class="widget-stats-amount" id="text_positif">0</span>
                                </div>
                                <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start">
                                    <i class="material-icons">keyboard_arrow_down</i> 4%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-primary">
                                    <i class="material-icons-outlined">paid</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Sentiment Netral</span>
                                    <span class="widget-stats-amount" id="text_netral">0</span>
                                </div>
                                <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start">
                                    <i class="material-icons">keyboard_arrow_down</i> 4%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-primary">
                                    <i class="material-icons-outlined">paid</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Sentiment Negatif</span>
                                    <span class="widget-stats-amount" id="text_negatif">0</span>
                                </div>
                                <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start">
                                    <i class="material-icons">keyboard_arrow_down</i> 4%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="button" id="pengujianButton" class="btn btn-primary w-100" style="width: 100%" data-bs-toggle="modal" data-bs-target="#TestingDataModal">Mulai Pengujian</button>
                </div>
            </div>
            <div class="modal fade" id="TestingDataModal" tabindex="-1" aria-labelledby="TestingDataModalTitle" style="display: none;" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">Pengujian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('testing.process') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <h6>Klik Mulai untuk mulai pengujian</h6>
                                <input type="text" name="namaModel" id="namaModel" value="" hidden>
                                <input type="text" name="jumlahSentimen" id="jumlahSentimen" value="" hidden>
                                <input type="text" name="trainingPositif" id="trainingPositif" value="" hidden>
                                <input type="text" name="trainingNegatif" id="trainingNegatif" value="" hidden>
                                <input type="text" name="trainingNetral" id="trainingNetral" value="" hidden>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Mulai</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
</div>

<script>
    function onchangeModel(value) {
        const myArray = value.split(",");
        console.log(myArray);
        
        document.getElementById("text_total").innerHTML = myArray[0];
        document.getElementById("text_positif").innerHTML = myArray[1];
        document.getElementById("text_negatif").innerHTML = myArray[2];
        document.getElementById("text_netral").innerHTML = myArray[3];
        document.getElementById("namaModel").value = myArray[4];
        document.getElementById("jumlahSentimen").value = myArray[0];
        document.getElementById("trainingPositif").value = myArray[1];
        document.getElementById("trainingNegatif").value = myArray[2];
        document.getElementById("trainingNetral").value = myArray[3];
        document.getElementById("pengujianButton").disabled = false;
    }
</script>

{{-- <script>
    function onchangeModel(value) {
        if (!value) return;

        // Ubah string JSON menjadi object JS
        const data = JSON.parse(value);
        console.log(data);

        // Tampilkan di text
        document.getElementById("text_total").innerHTML   = data.total;
        document.getElementById("text_positif").innerHTML = data.positive;
        document.getElementById("text_negatif").innerHTML = data.negative;
        document.getElementById("text_netral").innerHTML  = data.netral;

        // Isi ke hidden input
        document.getElementById("namaModel").value        = data.name;
        document.getElementById("jumlahSentimen").value   = data.total;
        document.getElementById("trainingPositif").value  = data.positive;
        document.getElementById("trainingNegatif").value  = data.negative;
        document.getElementById("trainingNetral").value   = data.netral;

        // Aktifkan tombol
        document.getElementById("pengujianButton").disabled = false;
    }
</script> --}}

@endsection
