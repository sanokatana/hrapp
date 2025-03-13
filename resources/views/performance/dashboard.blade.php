@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Performance</div>
                <h2 class="page-title">Contract Dashboard</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Summary Cards -->
        <div class="row row-cards mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M9 15l2 2l4 -4" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $totalActiveContracts }}</div>
                                <div class="text-secondary">Active Contracts</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M10 16l4 0" />
                                        <path d="M12 14l0 4" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $newContractsThisMonth }}</div>
                                <div class="text-secondary">New Contracts This Month</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19.823 19.824a2 2 0 0 1 -1.823 1.176h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 1.175 -1.823m3.825 -.177h9a2 2 0 0 1 2 2v9" />
                                        <path d="M16 3v4" />
                                        <path d="M8 3v1" />
                                        <path d="M4 11h7m4 0h5" />
                                        <path d="M3 3l18 18" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $contractsEndingDecember }}</div>
                                <div class="text-secondary">Ending in December</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                        <path d="M12 8v4" />
                                        <path d="M12 16h.01" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $expiredContracts }}</div>
                                <div class="text-secondary">Expired Contracts</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-4">
            <!-- Monthly Contract Trends -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Contract Trends</h3>
                    </div>
                    <div class="card-body">
                        <div id="contractTrends" style="height: 300px"></div>
                    </div>
                </div>
            </div>

            <!-- Contract Status Distribution -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Contract Status Distribution</h3>
                    </div>
                    <div class="card-body">
                        <div id="contractStatusPie" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Contracts Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Contracts</h3>
                    </div>
                    <div class="card-table table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Contract Number</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentContracts as $contract)
                                <tr>
                                    <td>{{ $contract->nama_lengkap }}</td>
                                    <td>{{ $contract->no_kontrak }}</td>
                                    <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($contract->end_date)->format('d M Y') }}</td>
                                    <td>{{ $contract->position }}</td>
                                    <td>
                                        @if($contract->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($contract->status == 'Expired')
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($contract->status == 'Extended')
                                            <span class="badge bg-info">Extended</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $contract->status }}</span>
                                        @endif
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
@endsection

@push('myscript')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Monthly Contract Trends Chart
    var options = {
        chart: {
            type: 'area',
            height: 335,
            zoom: {
                enabled: false
            },
        },
        series: [{
            name: 'New Contracts',
            data: @json($monthlyTrends['new'])
        }, {
            name: 'Ending Contracts',
            data: @json($monthlyTrends['ending'])
        }],
        xaxis: {
            categories: @json($monthlyTrends['months'])
        },
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
            }
        },
        colors: ['#206bc4', '#f59f00']
    };

    var chart = new ApexCharts(document.querySelector("#contractTrends"), options);
    chart.render();

    // Contract Status Distribution Pie Chart
    var pieOptions = {
        chart: {
            type: 'donut',
            height: 300
        },
        series: @json($statusStats['counts']),
        labels: @json($statusStats['statuses']),
        colors: ['#206bc4', '#f59f00', '#d63939', '#2fb344'],
        legend: {
            position: 'bottom',
            formatter: function(seriesName, opts) {
                return seriesName + ' - ' + opts.w.globals.series[opts.seriesIndex];
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + ' contracts';
                }
            }
        }
    };

    var pieChart = new ApexCharts(document.querySelector("#contractStatusPie"), pieOptions);
    pieChart.render();
});
</script>
@endpush