@extends('layouts.layout')
@section('labelling', 'active-page')
@section('content')
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Labelling Data</h1>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#LabellingDataModal">Labelling Data</button>
                        <div class="modal fade" id="LabellingDataModal" tabindex="-1" aria-labelledby="LabellingDataModalTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="LabellingDataModalTitle">Labelling Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('labelling.process') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Start Labelling.</p>
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
                                        <h5 class="modal-title" id="DeleteDataModalTitle">Labelling Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('labelling.destroy') }}" method="POST">
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
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable1" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="">Real Text</th>
                                        <th class="">Clean Text</th>
                                        <th class="">Sentiment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($labelling_data as $data)
                                        <tr>
                                            <td class="">{{ $data->full_text }}</td>
                                            <td class="">{{ $data->processed_text }}</td>
                                            <td class="">{{ $data->sentiment }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
