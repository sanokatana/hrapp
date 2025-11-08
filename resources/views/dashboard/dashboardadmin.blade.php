@extends('layouts.admin.tabler')
@section('content')
@php
@endphp
<div class="page-header d-print-none">
    <div class="container-xl mb-2">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle text-red">
                    {{ now()->format('l, d F Y') }}
                </div>
                <h2 class="page-title">Welcome back, {{ Auth::guard('user')->user()->name }}</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <h2 class="page-title mb-1" style="font-weight: normal;">Overview</h2>
            </div>
        </div>
    </div>
</div>
@endsection
