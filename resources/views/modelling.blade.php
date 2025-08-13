@extends('layouts.layout')
@section('modelling', 'active-page')
@section('content')
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Modelling</h1>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModellingDataModal">Modelling Data</button>
                        <div class="modal fade" id="ModellingDataModal" tabindex="-1" aria-labelledby="ModellingDataModalTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ModellingDataModalTitle">Modelling Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('modelling.process') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Start Modelling.</p>
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
                                        <th class="">Model Name</th>
                                        <th class="">Positive Sentiment</th>
                                        <th class="">Netral Sentiment</th>
                                        <th class="">Negative Sentiment</th>
                                        <th class="">Total Sentiment</th>
                                        <th class="">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modellings as $data)
                                        <tr>
                                            <td class="">{{ $data->model_name }}</td>
                                            <td class="">{{ $data->positive_labels }}</td>
                                            <td class="">{{ $data->netral_labels }}</td>
                                            <td class="">{{ $data->negative_labels }}</td>
                                            <td class="">{{ $data->positive_labels + $data->netral_labels + $data->negative_labels }}</td>
                                            <td class="">
                                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#DeleteDataModal{{ $data->id }}">Delete Data</button>
                                                <div class="modal fade" id="DeleteDataModal{{ $data->id }}" tabindex="-1" aria-labelledby="DeleteDataModalTitle" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="DeleteDataModalTitle">Modelling Data</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('modelling.destroy', $data->id) }}" method="POST">
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
                                            </td>
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
