@extends('layouts.userlayout')

@section('content')
    <div class="">
        <h2 class="mb-4">Application Status</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-hover table-striped table-bordered" style="border-color: #333333;">
            <thead class="table-light">
            <tr style="background-color: #4caf50; color: white;">
                <th>ID</th>
                <th>Facility</th>
                <th>Province</th>
                <th>City</th>
                <th>Contact Number</th>
                <th>View Letter</th>
                <th>View Assessment</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @if ($statuses->isEmpty())
                <tr>
                    <td colspan="9" class="text-center">No applications found</td>
                </tr>
            @else
                @foreach ($statuses as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->facility }}</td>
                        <td>{{ $application->province_city }}</td>
                        <td>{{ $application->city }}</td>
                        <td>{{ $application->contact_number }}</td>
                        <td>
                            <a href="{{ url('storage/' . $application->intent_upload) }}" target="_blank">View Letter</a>
                        </td>
                        <td>
                            <a href="{{ url('storage/' . $application->assessment_upload) }}" target="_blank">View Assessment</a>
                        </td>
                        <td>
                            <strong>
                                <span class="{{ $application->status === 'ongoing' ? 'text-orange' : '' }} 
                                    {{ $application->status === 'verified' ? 'text-success' : '' }} 
                                     {{ $application->status === 'passed' ? 'text-success' : '' }} 
                                    {{ $application->status === 'denied' ? 'text-danger' : '' }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                                @if($application->status === 'ongoing' && $application->visit_date)
                                    <br>
                                    <small class="text-muted">Visitation Date: {{ \Carbon\Carbon::parse($application->visit_date)->format('F j, Y') }}</small>
                                @endif
                            </strong>
                        </td>
                        <td>
                            @if ($application->status === 'pending')
                                <a href="{{ route('edit.application', ['id' => $application->id]) }}" class="btn btn-primary">
                                    Edit
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    Edit
                                </button>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-success view-remarks" 
                                data-remarks="{{ (trim($application->remarks) === 'No Remarks' || empty($application->remarks)) 
                                    ? ($application->ntp_remarks ?? 'No remarks available') 
                                    : $application->remarks }}">
                                View Remarks
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <!-- Modal for Viewing Remarks -->
    <div class="modal fade" id="remarks-modal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Inspector Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="remarks-content">No remarks available.</p>
                </div>
                <div class="modal-footer">
                    <button type=" button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     <!-- Include SweetAlert2 -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Handle the click event for the "View Remarks" button
            $('.view-remarks').on('click', function() {
                // Get the remarks from the data attribute
                const remarks = $(this).data('remarks');
                // Set the remarks content in the modal
                $('#remarks-content').text(remarks);
                // Show the modal
                $('#remarks-modal').modal('show');
            });
        });
    </script>
@endsection
