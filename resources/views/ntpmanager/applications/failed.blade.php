@extends('layouts.ntpmanager')

@section('content')
<div class="container">
    <h1>Update Application Status to "Failed"</h1>
    <p>Inform the client to apply for a new request.</p>

    <form action="{{ route('ntpmanager.applications.update.failed', $application) }}" method="POST">
        @csrf
        <button type="submit" name="status" value="failed" class="btn btn-danger">
            <i class="fas fa-times"></i> Confirm
        </button>
    </form>
</div>
@endsection
