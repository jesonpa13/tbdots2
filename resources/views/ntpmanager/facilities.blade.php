@extends('layouts.ntpmanager')

@section('content')
    <div class="container text-center">
        <!-- Print Button -->
        <div class="d-flex justify-content-end mb-4">
            <a href="#" class="btn btn-danger me-2" id="printPdfButton">
                <i class="fas fa-file-pdf"></i> Print PDF
            </a>
        </div>

        <!-- Filter Form with Dropdowns for Province and Status -->
        <div class="mb-4 d-flex justify-content-end">
            <form method="GET" action="{{ route('ntpmanager.facilities') }}" class="form-inline">
                <!-- Filter Guide -->
                <span class="me-3" style="font-weight: bold; color: #5A5A5A;">
                    Filter by Province and Status:
                </span>
                
                <!-- Province Dropdown -->
                <select name="province_city" class="form-control me-2">
                    <option value="">Province </option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" {{ $selectedProvince == $province ? 'selected' : '' }}>
                            {{ $province }}
                        </option>
                    @endforeach
                </select>

                <!-- Status Dropdown -->
                <select name="status" class="form-control me-2">
                    <option value="all" {{ $selectedStatus == 'all' ? 'selected' : '' }}>Show All</option>
                    <option value="passed" {{ $selectedStatus == 'passed' ? 'selected' : '' }}>Passed Only</option>
                    <option value="expired" {{ $selectedStatus == 'expired' ? 'selected' : '' }}>Expired Only</option>
                </select>

                <!-- Filter and Reset Buttons -->
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('ntpmanager.facilities', ['province_city' => '', 'status' => 'all']) }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </form>
        </div>

        <!-- Display Heading with Current Date -->
        <h2 class="mb-4">CENTER FOR HEALTH DEVELOPMENT XII - SOCCKSARGEN REGION DOTS CERTIFICATION REGISTRY</h2>
        <h5 class="mb-4">{{ \Carbon\Carbon::now()->format('F d, Y') }}</h5>

        <!-- Section for Facilities based on Filtered Status -->
        @if ($selectedStatus == 'passed' || $selectedStatus == 'all')
            <h3 class="mb-4">Facilities that Passed Application</h3>
            @foreach ($facilities->where('status', 'passed')->groupBy('province_city') as $provinceCity => $provinceFacilities)
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h4>{{ strtoupper($provinceCity) }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>DOTS Facility</th>
                                    <th>Registration No.</th>
                                    <th>Date Renewal</th>
                                    <th>Date Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($provinceFacilities as $application)
                                    <tr>
                                        <td>{{ $application->facility }}</td>
                                        <td>{{ $application->registration_no }}</td>
                                        <td>
                                            @if (!empty($application->date_renewal) && strtotime($application->date_renewal) !== false)
                                                {{ \Carbon\Carbon::parse($application->date_renewal)->format('m-d-Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($application->date_renewal) && strtotime($application->date_renewal) !== false)
                                                {{ \Carbon\Carbon::parse($application->date_renewal)->addYears(3)->format('m-d-Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif

        @if ($selectedStatus == 'expired' || $selectedStatus == 'all')
            <h3 class="mb-4">Expired Facilities</h3>
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4>Expired Facilities List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>DOTS Facility</th>
                                <th>Registration No.</th>
                                <th>Date Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facilities->where('status', 'passed')->filter(function ($facility) {
                                return !empty($facility->date_renewal) && strtotime($facility->date_renewal) !== false && \Carbon\Carbon::parse($facility->date_renewal)->addYears(3)->isPast();
                            }) as $facility)
                                @if ($selectedProvince === '' || $facility->province_city === $selectedProvince)
                                    <tr>
                                        <td>{{ $facility->facility }}</td>
                                        <td>{{ $facility->registration_no }}</td>
                                        <td>
                                            @if (!empty($facility->date_renewal) && strtotime($facility->date_renewal) !== false)
                                                {{ \Carbon\Carbon::parse($facility->date_renewal)->addYears(3)->format('m-d-Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- JavaScript to handle PDF print -->
    <script>
        document.getElementById('printPdfButton').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default link behavior
            // Create a new window for the PDF
            const pdfWindow = window.open("{{ route('ntpmanager.export.pdf') }}?province_city={{ $selectedProvince }}&status={{ $selectedStatus }}", "_blank");

            // Check if the window opened successfully
            if (pdfWindow) {
                pdfWindow.onload = function () {
                    pdfWindow.print(); // Automatically trigger print when PDF loads
                };
            } else {
                alert('Please allow popups for this website to enable PDF printing.');
            }
        });
    </script>
@endsection
