@extends('layouts.ntpmanager') 

@section('content')
<div class="preview-content" style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 1.8rem; font-weight: 600; color: #333; text-align: center; margin-bottom: 1rem;">
        <i class="fas fa-certificate" style="color: #4caf50; margin-right: 8px;"></i> Certificate Preview
    </h2>

    <p style="font-size: 1rem; margin-bottom: 0.5rem;">
        <i class="fas fa-hospital" style="color: #4caf50; margin-right: 8px;"></i>
        <strong>Facility Name:</strong> {{ $application->facility }}
    </p>
    <p style="font-size: 1rem; margin-bottom: 0.5rem;">
        <i class="fas fa-calendar-check" style="color: #4caf50; margin-right: 8px;"></i>
        <strong>Renewal Date:</strong> {{ $renewalDate ?? 'Not available' }}
    </p>
    <p style="font-size: 1rem; margin-bottom: 0.5rem;">
        <i class="fas fa-calendar-times" style="color: #dc3545; margin-right: 8px;"></i>
        <strong>Expiry Date:</strong> {{ $expiryDate ?? 'Not available' }}
    </p>
    <p style="font-size: 1rem; margin-bottom: 0.5rem;">
        <i class="fas fa-id-card" style="color: #007bff; margin-right: 8px;"></i>
        <strong>Registration No.:</strong> {{ $application->registration_no }}
    </p>
    <p style="font-size: 1rem; margin-bottom: 1.5rem;">
        <i class="fas fa-user-tie" style="color: #007bff; margin-right: 8px;"></i>
        <strong>Regional Director:</strong> {{ $regionalDirector }}
    </p>

    <div style="text-align: center;">
        <form action="{{ route('ntpmanager.applications.download', $application) }}" method="POST" class="d-inline" id="downloadForm" style="display: inline-block; margin-right: 10px;">
            @csrf
            <button type="submit" class="btn btn-success" id="downloadButton" style="font-size: 1rem; padding: 0.6rem 1.5rem;">
                <i class="fas fa-download" style="margin-right: 5px;"></i> Confirm & Download Certificate
            </button>
        </form>
        <a href="{{ route('ntpmanager.applications.index') }}" class="btn btn-secondary" style="font-size: 1rem; padding: 0.6rem 1.5rem;">
            <i class="fas fa-times-circle" style="margin-right: 5px;"></i> Cancel
        </a>
    </div>
</div>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmButton = document.querySelector('#downloadButton');

        confirmButton.addEventListener('click', function(e) {
            e.preventDefault();
            confirmButton.disabled = true;

            Swal.fire({
                title: 'Generating Certificate',
                text: 'Please wait while we generate your certificate...',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false
            });

            $.ajax({
                url: "{{ route('ntpmanager.applications.download', $application) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
                    const link = document.createElement('a');
                    const safeFileName = "{{ addslashes($application->facility) }}"
                                          .replace(/[^a-zA-Z0-9\s_-]/g, '')
                                          .trim()
                                          .replace(/\s+/g, '_');

                    link.href = window.URL.createObjectURL(blob);
                    link.download = `certificate_${safeFileName}.docx`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Certificate has been successfully downloaded!',
                    });

                    confirmButton.disabled = false;
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while generating the certificate. Please try again.',
                    });

                    confirmButton.disabled = false;
                }
            });
        });
    });
</script>

@endsection
