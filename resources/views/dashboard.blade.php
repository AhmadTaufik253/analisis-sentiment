@extends('layouts.layout')
@section('dashboard', 'active-page')
@section('content')
<div class="app-content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-description">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-primary">
                                    <i class="material-icons-outlined">save_alt</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Raw Data</span>
                                    <span class="widget-stats-amount">{{ $totalRawData }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-warning">
                                    <i class="material-icons-outlined">label</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Preprocessing Data</span>
                                    <span class="widget-stats-amount">{{ $totalPreprocessingData }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card widget widget-stats">
                        <div class="card-body">
                            <div class="widget-stats-container d-flex">
                                <div class="widget-stats-icon widget-stats-icon-danger">
                                    <i class="material-icons-outlined">file_download</i>
                                </div>
                                <div class="widget-stats-content flex-fill">
                                    <span class="widget-stats-title">Total Labelling Data</span>
                                    <span class="widget-stats-amount">{{ $totalLabellingData }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Label Sentiment Chart</h5>
                        </div>
                        <div class="card-body">
                            <div id="apex3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script>
    fetch('/dashboard-chart-data')
    .then(res => res.json())
    .then(res => {
        var options3 = {
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                    borderRadius: 10
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [{
                name: 'Jumlah Data',
                data: res.values
            }],
            xaxis: {
                categories: res.categories,
                labels: {
                    style: {
                        colors: 'rgba(94, 96, 110, .5)'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Total Data'
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: 'rgba(94, 96, 110, .5)',
                strokeDashArray: 4
            }
        };

        var chart3 = new ApexCharts(document.querySelector("#apex3"), options3);
        chart3.render();
    });

</script>
@endsection
