<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities Report</title>
    <style>
        /* Basic styling for the PDF */
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2, h3, h4 { text-align: center; margin-bottom: 5px; }
        h5 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>CENTER FOR HEALTH DEVELOPMENT XII - SOCCKSARGEN REGION DOTS CERTIFICATION REGISTRY</h2>
    <h5>{{ \Carbon\Carbon::now()->format('F d, Y') }}</h5>

    @if ($selectedStatus == 'passed' || $selectedStatus == 'all')
        <h3>Facilities that Passed Application</h3>
        @foreach ($applications->where('status', 'passed')->groupBy('province_city') as $provinceCity => $provinceApplications)
            @if ($provinceApplications->isNotEmpty())
                <div>
                    <h4>{{ strtoupper($provinceCity) }}</h4>

                    <table>
                        <thead>
                            <tr>
                                <th>DOTS Facility</th>
                                <th>Registration No.</th>
                                <th>Date Renewal</th>
                                <th>Date Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($provinceApplications as $application)
                                <tr>
                                    <td>{{ $application->facility }}</td>
                                    <td>{{ $application->registration_no }}</td>
                                    <td>
    @if (!empty($application->date_renewal))
        {{ \Carbon\Carbon::createFromFormat('m-d-Y', $application->date_renewal)->format('m-d-Y') }}
    @else
        N/A
    @endif
</td>
<td>
    @if (!empty($application->date_renewal))
        {{ \Carbon\Carbon::createFromFormat('m-d-Y', $application->date_renewal)->addYears(3)->format('m-d-Y') }}
    @else
        N/A
    @endif
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    @endif

    @if ($selectedStatus == 'expired' || $selectedStatus == 'all')
        <h3>Expired Facilities</h3>
        @foreach ($applications->filter(function ($application) {
            return !empty($application->date_renewal) && strtotime($application->date_renewal) !== false && \Carbon\Carbon::parse($application->date_renewal)->addYears(3)->isPast();
        })->groupBy('province_city') as $provinceCity => $expiredApplications)
            @if ($expiredApplications->isNotEmpty())
                <div>
                    <h4>{{ strtoupper($provinceCity) }}</h4>

                    <table>
                        <thead>
                            <tr>
                                <th>DOTS Facility</th>
                                <th>Registration No.</th>
                                <th>Date Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expiredApplications as $facility)
                                <tr>
                                    <td>{{ $facility->facility }}</td>
                                    <td>{{ $facility->registration_no }}</td>
                                    <td>
                                        @if (!empty($facility->date_renewal))
                                            {{ \Carbon\Carbon::parse($facility->date_renewal)->addYears(3)->format('m-d-Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    @endif
</body>
</html>
