@extends('layouts.layout')
@section('import-data', 'active-page')
@section('content')
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Import Data</h1>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ImportDataModal">Import Data</button>
                        <div class="modal fade" id="ImportDataModal" tabindex="-1" aria-labelledby="ImportDataModalTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ImportDataModalTitle">Import Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('import-data.store') }}" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <p>Input the dataset in the form of an Excel file.</p>
                                            @csrf
                                            <input type="file" class="form-control" name="file" id="file">
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
                                        <h5 class="modal-title" id="DeleteDataModalTitle">Import Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('import-data.destroy') }}" method="POST">
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
                                        <th class="col-3">Created At</th>
                                        <th class="col-9">Real Text</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($raw_data as $data)
                                        <tr>
                                            <td class="col-3">{{ $data->created_at }}</td>
                                            <td class="col-9">{{ $data->full_text }}</td>
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
