@extends('layouts.ntpmanager')

@section('content')
    <div class="container mt-5">
        <p class="text-center font-weight-bold" style="font-size: 1.5rem;">Welcome to the NTP Manager Dashboard!</p>

        <!-- Dashboard Stats -->
        <div class="row justify-content-center">
            <!-- Total Applications -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-success">
                    <div class="card-body text-center">
                        <!-- Icon and Title -->
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5 class="card-title text-success">Total Verified Applications</h5>
                        <p class="card-text display-4">{{ $totalApplications }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Facilities -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body text-center">
                        <!-- Icon and Title -->
                        <i class="fas fa-building fa-4x text-primary mb-3"></i>
                        <h5 class="card-title text-primary">Total Passed Applications</h5>
                        <p class="card-text display-4">{{ $totalFacilities }}</p>
                    </div>
                </div>
            </div>
        </div>

        

    </div>
@endsection
