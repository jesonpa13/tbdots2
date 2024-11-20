@extends('layouts.ntpmanager')

@section('content')

<div class="main-content">
    <h2 class="mb-4 text-center">Application Details</h2>

    <!-- Application Details Card -->
    <div class="card shadow p-4 mb-4">
        <div class="card-body">
            <!-- Facility -->
            <div class="mb-3">
                <label class="form-label">Facility</label>
                <input type="text" class="form-control" value="{{ $application->facility }}" readonly>
            </div>
            <!-- Head of Unit -->
            <div class="mb-3">
                <label class="form-label">Head of Unit</label>
                <input type="text" class="form-control" value="{{ $application->head_of_unit }}" readonly>
            </div>
            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control 
                    @if ($application->status === 'passed') text-success @elseif ($application->status === 'failed') text-danger @else text-muted @endif" 
                    value="{{ ucfirst($application->status) }}" readonly>
            </div>
            <!-- Registration Number -->
            <div class="mb-3">
                <label class="form-label">Registration Number</label>
                <input type="text" class="form-control" value="{{ $application->registration_no }}" readonly>
            </div>
                            <!-- PDOHO Visit Date -->
                        
                <div class="mb-3">
                    <label class="form-label">PDOHO Visit Date</label>
                    @php
                        try {
                            // Attempt to use createFromFormat first
                            $visitDateFormatted = $application->visit_date 
                                ? \Carbon\Carbon::createFromFormat('m-d-Y', $application->visit_date)->format('F j, Y')
                                : 'Not scheduled';
                        } catch (\Exception $e) {
                            // If createFromFormat fails, fallback to parse method
                            try {
                                $visitDateFormatted = $application->visit_date 
                                    ? \Carbon\Carbon::parse($application->visit_date)->format('F j, Y') 
                                    : 'Not scheduled';
                            } catch (\Exception $e) {
                                // If parse fails as well, show error
                                $visitDateFormatted = 'Invalid date format';
                            }
                        }
                    @endphp
                    <input type="text" class="form-control" value="{{ $visitDateFormatted }}" readonly>
                </div>

                <!-- NTP Manager Visit Date -->
                <div class="mb-3">
                    <label class="form-label">NTP Manager Visit Date</label>
                    @php
                        try {
                            // Attempt to use createFromFormat first for ntp_visit_date
                            $ntpVisitDateFormatted = $application->ntp_visit_date 
                                ? \Carbon\Carbon::createFromFormat('m-d-Y', $application->ntp_visit_date)->format('F j, Y')
                                : 'Not scheduled';
                        } catch (\Exception $e) {
                            // If createFromFormat fails, fallback to parse method
                            try {
                                $ntpVisitDateFormatted = $application->ntp_visit_date 
                                    ? \Carbon\Carbon::parse($application->ntp_visit_date)->format('F j, Y') 
                                    : 'Not scheduled';
                            } catch (\Exception $e) {
                                // If parse fails as well, show error
                                $ntpVisitDateFormatted = 'Invalid date format';
                            }
                        }
                    @endphp
                    <input type="text" class="form-control" value="{{ $ntpVisitDateFormatted }}" readonly>
                </div>

                        <!-- Date of Renewal -->
                <div class="mb-3">
                    <label class="form-label">Date of Renewal</label>
                    @php
                        try {
                            // Try to parse using 'm-d-Y' format for date_renewal
                            $dateRenewalFormatted = $application->date_renewal 
                                ? \Carbon\Carbon::createFromFormat('m-d-Y', $application->date_renewal)->format('d-m-Y') 
                                : 'Not available';
                        } catch (\Exception $e) {
                            // If format doesn't match, display error message
                            $dateRenewalFormatted = 'Invalid date format';
                        }
                    @endphp
                    <input type="text" class="form-control" value="{{ $dateRenewalFormatted }}" readonly>
                </div>

                <!-- Date of Expiry -->
                <div class="mb-3">
                    <label class="form-label">Date of Expiry</label>
                    @php
                        try {
                            // Calculate the expiry date by adding 3 years to date_renewal
                            $dateExpiryFormatted = $application->date_renewal 
                                ? \Carbon\Carbon::createFromFormat('m-d-Y', $application->date_renewal)
                                    ->addYears(3)
                                    ->format('d-m-Y') 
                                : 'Not available';
                        } catch (\Exception $e) {
                            // If format doesn't match, display error message
                            $dateExpiryFormatted = 'Invalid date format';
                        }
                    @endphp
                    <input type="text" class="form-control" value="{{ $dateExpiryFormatted }}" readonly>
                </div>

            <!-- Intent Document -->
            <div class="mb-3">
                <label class="form-label">Intent Document</label>
                @if($application->intent_upload)
                    <a href="{{ asset('storage/' . $application->intent_upload) }}" target="_blank" class="btn btn-outline-success btn-sm">
                        View Intent Document
                    </a>
                @else
                    <input type="text" class="form-control" value="No file uploaded" readonly>
                @endif
            </div>

            <!-- Assessment Document -->
            <div class="mb-3">
                <label class="form-label">Assessment Document</label>
                @if($application->assessment_upload)
                    <a href="{{ asset('storage/' . $application->assessment_upload) }}" target="_blank" class="btn btn-outline-success btn-sm">
                        View Assessment Document
                    </a>
                @else
                    <input type="text" class="form-control" value="No file uploaded" readonly>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('ntpmanager.applications.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Applications
        </a>
        @if ($application->status === 'passed')
            <a href="{{ route('ntpmanager.applications.preview', $application) }}" class="btn btn-success">
                <i class="fas fa-file-alt"></i> Generate Document
            </a>
        @endif
    </div>
</div>
@endsection
