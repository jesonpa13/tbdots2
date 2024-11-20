@extends('layouts.userlayout')

@section('content')
    <h3 class=" mb-4" style="font-weight:bold">SEND REQUEST</h3>

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

    <form id="requestForm" action="{{ route('client.sendrequest.store') }}" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="province_city" class="form-label">Province</label>
            <input type="text" name="province_city" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cityt" class="form-label">City</label>
            <input type="text" name="city" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="facility" class="form-label">Facility</label>
            <input type="text" name="facility" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="head_of_unit" class="form-label">Head of Unit</label>
            <input type="text" name="head_of_unit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="designation" class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>

        <div class="mb-3 ">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="intent_upload" class="form-label">Letter of Intent</label>
            <input type="file" id="intent_upload" name="intent_upload" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="assessment_upload" class="form-label">Self Assessment Form</label>
            <input type="file" id="assessment_upload" name="assessment_upload" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Submit Request</button>
    </form>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const form = document.getElementById('requestForm');
        const intentFileInput = document.getElementById('intent_upload');
        const assessmentFileInput = document.getElementById('assessment_upload');

        // Function to check file size
    function checkFileSize(input, fileLimit) {
        const fileSize = input.files[0]?.size; // Use optional chaining to avoid errors
        if (fileSize > fileLimit) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: `File size is too large. Please select a file with a size of ${fileLimit / (1024 * 1024)}MB or less.`,
            });
            input.value = ''; // Reset the file input
        }
    }

    // Check file size for Letter of Intent
    intentFileInput.addEventListener('change', function() {
        const fileLimit = 8 * 1024 * 1024; // 8MB
        checkFileSize(this, fileLimit);
    });

    // Check file size for Self Assessment Form
    assessmentFileInput.addEventListener('change', function() {
        const fileLimit = 8 * 1024 * 1024; // 8MB
        checkFileSize(this, fileLimit);
    });
        // Confirmation before form submission
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to send this request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    </script>
@endsection