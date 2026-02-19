@extends('layouts.app')

@section('title', 'Undangan PDF')
@section('icon', 'mdi-email-outline')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Undangan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="mdi mdi-email-outline text-info" style="font-size: 80px;"></i>
                <h3 class="mt-3">Generate Undangan PDF</h3>
                <p class="text-muted">Format: Portrait A4 dengan Header</p>
                <p class="text-muted mb-4">Klik tombol di bawah untuk download undangan</p>
                
                <a href="{{ route('pdf.undangan.generate') }}" class="btn btn-gradient-info btn-lg">
                    <i class="mdi mdi-download"></i> Download Undangan PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection