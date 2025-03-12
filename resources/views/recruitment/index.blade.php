@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Recruitment</div>
                <h2 class="page-title">Dashboard</h2>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $totalRecruits }}</div>
                                <div class="text-secondary">Total Recruits</div>
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
                                <span class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hourglass" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6.5 7h11"></path>
                                        <path d="M6.5 17h11"></path>
                                        <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z"></path>
                                        <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $recruitsInProcess }}</div>
                                <div class="text-secondary">In Process</div>
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
                                <span class="bg-success text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $totalHired }}</div>
                                <div class="text-secondary">Total Hired</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6l-12 12"></path>
                                        <path d="M6 6l12 12"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $totalDeclined }}</div>
                                <div class="text-secondary">Total Declined</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month's Stats -->
        <div class="row row-cards mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-purple text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                        <path d="M16 3v4"></path>
                                        <path d="M8 3v4"></path>
                                        <path d="M4 11h16"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $recruitsThisMonth }}</div>
                                <div class="text-secondary">Recruits This Month</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                        <path d="M15 19l2 2l4 -4"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $hiredThisMonth }}</div>
                                <div class="text-secondary">Hired This Month</div>
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
                                <span class="bg-yellow text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-briefcase" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v11a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>
                                        <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2"></path>
                                        <path d="M12 12l0 .01"></path>
                                        <path d="M3 13a20 20 0 0 0 18 0"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $totalJobOpenings }}</div>
                                <div class="text-secondary">Total Job Openings</div>
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
                                <span class="bg-azure text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folder-open" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 19l2.757 -7.351a1 1 0 0 1 .936 -.649h12.307a1 1 0 0 1 .936 .649l2.764 7.351"></path>
                                        <path d="M4 5a1 1 0 0 1 1 -1h4l3 3h7a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $openJobOpenings }}</div>
                                <div class="text-secondary">Open Positions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recruitment Progress</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar bg-primary" style="width: {{ ($recruitsInProcess/$totalRecruits)*100 }}%">
                                In Process ({{ $recruitsInProcess }})
                            </div>
                            <div class="progress-bar bg-success" style="width: {{ ($totalHired/$totalRecruits)*100 }}%">
                                Hired ({{ $totalHired }})
                            </div>
                            <div class="progress-bar bg-danger" style="width: {{ ($totalDeclined/$totalRecruits)*100 }}%">
                                Declined ({{ $totalDeclined }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
    <!-- Monthly Trends -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Monthly Recruitment Trends</h3>
            </div>
            <div class="card-body">
                <div id="recruitmentTrends" style="height: 300px"></div>
            </div>
        </div>
    </div>

    <!-- Department Distribution -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Applications by Department</h3>
            </div>
            <div class="card-body">
                <div id="departmentPie" style="height: 300px"></div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Applications</h3>
            </div>
            <div class="card-table table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApplications as $application)
                        <tr>
                            <td>{{ $application->nama_candidate }}</td>
                            <td>{{ $application->nama_jabatan }}</td>
                            <td>{{ $application->nama_dept }}</td>
                            <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y') }}</td>
                            <td>
                                @if($application->status == 'In Process')
                                    <span class="badge bg-primary">In Process</span>
                                @elseif($application->status == 'Hired')
                                    <span class="badge bg-success">Hired</span>
                                @elseif($application->status == 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
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
    // Monthly Recruitment Trends Chart
    var options = {
        chart: {
            type: 'area',
            height: 335,
            zoom: {
                enabled: false
            },
        },
        series: [{
            name: 'Applications',
            data: @json($monthlyTrends['applications'])
        }, {
            name: 'Hired',
            data: @json($monthlyTrends['hired'])
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
    };

    var chart = new ApexCharts(document.querySelector("#recruitmentTrends"), options);
    chart.render();

    // Department Distribution Pie Chart
    var pieOptions = {
        chart: {
            type: 'donut',
            height: 380
        },
        dataLabels: {
          enabled: false
        },
        series: @json($departmentStats['counts']),
        labels: @json($departmentStats['departments']),
        legend: {
        position: 'bottom',
            formatter: function(seriesName, opts) {
                // Add count to legend
                return seriesName + ' - ' + opts.w.globals.series[opts.seriesIndex];
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + ' applications';
                }
            }
        }
    };

    var pieChart = new ApexCharts(document.querySelector("#departmentPie"), pieOptions);
    pieChart.render();
});
</script>
@endpush