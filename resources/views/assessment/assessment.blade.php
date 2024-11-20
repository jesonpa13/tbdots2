@extends('layouts/userlayout')

@section('content')
        <a href="{{ route('download.selfAssessmentForm') }}" class="btn btn-primary">Download Self Assessment Form here</a>
        <a href="{{ route('download.selfAssessmentTool') }}" class="btn btn-primary">Download Self Assessment Tool and Guide here</a>
@endsection
