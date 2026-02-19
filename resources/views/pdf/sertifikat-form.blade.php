@extends('layouts.app')

@section('title', 'Sertifikat PDF')
@section('icon', 'mdi-certificate')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Sertifikat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="mdi mdi-certificate text-warning" style="font-size: 80px;"></i>
                <h3 class="mt-3">Generate Sertifikat PDF</h3>
                <p class="text-muted">Format: Landscape A4</p>
                <p class="text-muted mb-4">Klik tombol di bawah untuk download sertifikat</p>
                
                <a href="{{ route('pdf.sertifikat.generate') }}" class="btn btn-gradient-primary btn-lg">
                    <i class="mdi mdi-download"></i> Download Sertifikat PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection