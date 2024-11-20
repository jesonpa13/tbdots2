@extends('layouts.ntpmanager')

@section('content')

<div class="main-content" style="background: #f7f7f7; padding: 2rem;">
    <h2 style="color: #4caf50;">List Of Applications</h2>

    @if ($applications->isEmpty())
        <p>No applications found with the selected statuses.</p>
    @else
        <table class="table table-hover table-striped table-bordered" style="border-color: #333333;">
            <thead class="table-light">
                <tr style="background-color: #4caf50; color: white;">
                    <th>ID</th>
                    <th>Facility</th>
                    <th>Head of Unit</th>
                    <th>Status</th>
                    <th>PDOHO Visit Date</th>
                    <th>NTP Visit Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr style="background-color: white; border: 1px solid #333333;">
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->facility }}</td>
                        <td>{{ $application->head_of_unit }}</td>
                        <td>
                        <span class="badge" 
                      style="font-size: 1.0rem; padding: 0.6rem 1rem; border-radius: 1.5rem; 
                             color: 
                                @if($application->status === 'verified') #2196f3 
                                @elseif($application->status === 'passed') #28a745 
                                @elseif($application->status === 'failed') #dc3545 
                                @endif;">
                    {{ ucfirst($application->status) }}
                </span>
                        </td>
                        <td>
                            {{ $application->visit_date ? \Carbon\Carbon::parse($application->visit_date)->format('F j, Y') : 'Not scheduled' }}
                        </td>
                        <td>
                            {{ $application->ntp_visit_date ? \Carbon\Carbon::parse($application->ntp_visit_date)->format('F j, Y') : 'Not scheduled' }}
                        </td>
                        <td>
                            <!-- Actions Dropdown -->
                            <div class="btn-group">
                            <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cogs"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-success" href="{{ route('ntpmanager.applications.show', $application) }}"><i class="fas fa-eye"></i> View</a></li>

                                @if ($application->status === 'verified')
                                    @if (!$application->ntp_visit_date)
                                        <li><a class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#scheduleModal{{ $application->id }}"><i class="fas fa-calendar-plus"></i> Set Schedule</a></li>
                                    @else
                                        <li><a class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#scheduleModal{{ $application->id }}"><i class="fas fa-calendar-alt"></i> Update Schedule</a></li>
                                        <li>
                                        <form id="statusForm{{ $application->id }}" action="{{ route('ntpmanager.applications.updateStatus', $application) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="status" value="">
                                            <textarea name="remarks" class="d-none"></textarea>

                                            <button type="button" onclick="confirmStatusUpdate('passed', '{{ $application->facility }}', {{ $application->id }})" class="dropdown-item text-success">
                                                <i class="fas fa-check"></i> Passed
                                            </button>
                                            <button type="button" onclick="confirmStatusUpdate('failed', '{{ $application->facility }}', {{ $application->id }})" class="dropdown-item text-danger">
                                                <i class="fas fa-times"></i> Failed
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                @elseif ($application->status === 'passed')
                                    <li><a class="dropdown-item text-success" href="{{ route('ntpmanager.applications.preview', $application) }}"><i class="fas fa-file-alt"></i> Generate</a></li>
                                @elseif ($application->status === 'failed')
                                    <li><a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#remarksModal{{ $application->id }}"><i class="fas fa-comment-dots"></i> View Remarks</a></li>
                                @endif
                            </ul>
                        </div>

                        <!-- Schedule Modal -->
                        <div class="modal fade" id="scheduleModal{{ $application->id }}" tabindex="-1" aria-labelledby="scheduleModalLabel{{ $application->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="scheduleModalLabel{{ $application->id }}">
                                            {{ $application->ntp_visit_date ? 'Update Visit Schedule' : 'Set Visit Schedule' }} for {{ $application->facility }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('ntpmanager.applications.setSchedule', $application) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="visit_date">Select Visit Date</label>
                                                <input type="date" name="visit_date" class="form-control" value="{{ $application->ntp_visit_date ? \Carbon\Carbon::parse($application->ntp_visit_date)->format('Y-m-d') : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-calendar-check"></i> {{ $application->ntp_visit_date ? 'Update Schedule' : 'Set Schedule' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Remarks Modal for Failed Applications -->
                        <div class="modal fade" id="remarksModal{{ $application->id }}" tabindex="-1" aria-labelledby="remarksModalLabel{{ $application->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="remarksModalLabel{{ $application->id }}">
                                            View Remarks for Failed Application
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="statusForm{{ $application->id }}" action="{{ route('ntpmanager.applications.updateStatus', $application) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="status" value="failed">
                                            <div class="form-group">
                                                <label for="ntp_remarks">Remarks:</label>
                                                <textarea name="ntp_remarks" class="form-control" rows="4" required readonly>{{ $application->ntp_remarks ?? '' }}</textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- SweetAlert2 and JQuery -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Update Confirmation Function
function confirmStatusUpdate(status, facility, applicationId) {
    if (status === 'passed') {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to mark the application for ${facility} as Passed.`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark as Passed',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#statusForm' + applicationId).find('input[name="status"]').val('passed');
                $('#statusForm' + applicationId).submit();
            }
        });
    } else if (status === 'failed') {
        Swal.fire({
            title: 'Add Remarks',
            html: `
                <textarea id="remarksInput" class="swal2-textarea" placeholder="Enter remarks for failure" rows="4"></textarea>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Submit Remarks',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const remarks = document.getElementById('remarksInput').value.trim();
                if (!remarks) {
                    Swal.showValidationMessage('Remarks are required!');
                }
                return remarks;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('#statusForm' + applicationId).find('input[name="status"]').val('failed');
                $('#statusForm' + applicationId).find('textarea[name="remarks"]').val(result.value);
                $('#statusForm' + applicationId).submit();
            }
        });
    }
}
</script>
@endsection
