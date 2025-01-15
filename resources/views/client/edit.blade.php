@extends('layouts.userlayout')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Application</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('update.application', $application->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="province_city" class="form-label">Province</label>
                <input type="text" name="province_city" class="form-control" value="{{ old('province_city', $application->province_city) }}" required>
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $application->city) }}" required>
            </div>

            <div class="mb-3">
                <label for="facility" class="form-label">Facility</label>
                <input type="text" name="facility" class="form-control" value="{{ old('facility', $application->facility) }}" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $application->address) }}" required>
            </div>

            <div class="mb-3">
                <label for="head_of_unit" class="form-label">Head of Unit</label>
                <input type="text" name="head_of_unit" class="form-control" value="{{ old('head_of_unit', $application->head_of_unit) }}" required>
            </div>

            <div class="mb-3">
                <label for="designation" class="form-label">Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ old('designation', $application->designation) }}" required>
            </div>

            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $application->contact_number) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $application->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="intent_upload" class="form-label">Letter of Intent</label>
                <input type="file" id="intent_upload" name="intent_upload" class="form-control">
                <small class="form-text text-muted">Leave blank to keep the current file.</small>
            </div>

            <div class="mb-3">
                <label for="assessment_upload" class="form-label">Self Assessment Form</label>
                <input type="file" id="assessment_upload" name="assessment_upload" class="form-control">
                <small class="form-text text-muted">Leave blank to keep the current file.</small>
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
        </form>
    </div>
@endsection
