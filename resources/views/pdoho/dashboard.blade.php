@extends('layouts.pdoholayout')

@section('content')
<h4><STRONG>WELCOME TO PDOHO DASHBOARD</STRONG></h4>

<div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Ongoing Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-orange">{{ $ongoingCount }}</h5>
                    </div>
                </div>
            </div>    
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Pending Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $pendingCount }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Denied Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-danger">{{ $deniedCount }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Verified Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-success">{{ $verifiedCount }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection