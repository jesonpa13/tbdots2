@extends('layouts.userlayout')

@section('content')

        <div class="button-assessment">
                <a href="{{ route('download.selfAssessmentForm') }}" class="btn btn-success">Download Self Assessment Form here</a>
                <a href="{{ route('download.selfAssessmentTool') }}" class="btn btn-success">Download Self Assessment Tool and Guide here</a>
        </div>
@endsection
