@extends('layouts.pdoholayout')

@section('content')
    <div class="main-content">
        <h2>Applications List</h2>
        <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Ongoing Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-orange">{{ $ongoingCount }}</h5>
                    </div>
                </div>
            </div>    
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Pending Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $pendingCount }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Denied Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-danger">{{ $deniedCount }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        Verified Requests
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-success">{{ $verifiedCount }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <form id="search-form" method="GET" action="{{ route('applications.index') }}">
    <div class="row mb-3">
        <div class="col-md-2">
            <select id="status-filter" name="status" class="form-control">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Search by ID or other fields..." />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

     @if($applications->isEmpty())
        <p>No applications found.</p>
    @else
        <div class="table-responsive">
            <table id="applications-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Facility</th>
                        <th>Province/City</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>View Letter</th>
                        <th>View Assessment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="applications-body">
                    @foreach ($applications as $application)
                        <tr id="application-{{ $application->id }}">
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->facility }}</td>
                            <td>{{ $application->province_city }}</td>
                            <td>{{ $application->address }}</td>
                            <td>{{ $application->contact_number }}</td>
                            <td>{{ $application->designation }}</td>
                            <td>{{ $application->email }}</td>
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
                                                {{ $application->status === 'denied' ? 'text-danger' : '' }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    @if($application->status === 'ongoing')
                                        <small>
                                            <u>{{ $application->visit_date ? \Carbon\Carbon::parse($application->visit_date)->format('F j, Y') : 'Set Visitation Date' }}</u>
                                        </small>
                                    @endif
                                </strong>
                            </td>

                            <td>
                                <div class="btn-group">
                                    @if($application->status === 'pending')
                                        <button class="btn btn-success set-schedule" data-id="{{ $application->id }}"><i class="fas fa-calendar-check"></i> Set Schedule</button>
                                        <button class="btn btn-danger deny-button" data-id="{{ $application->id }}" data-toggle="modal" data-target="#deny-modal"><i class="fas fa-ban"></i> Deny</button>
                                        <button class="btn btn-secondary" disabled>Edit</button>
                                    @elseif($application->status === 'ongoing')
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-edit"></i> Edit</button>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item verify" data-id="{{ $application->id }}">Verify</button>
                                            <button class="dropdown-item deny-button" data-id="{{ $application->id }}" data-toggle="modal" data-target="#deny-modal">Deny</button>
                                        </div>
                                    @else
                                        <button class="btn btn-secondary" disabled><i class="fas fa-edit"></i> Edit</button>
                                    @endif
                                    <!-- Delete Button -->
                                    <form action="{{ route('applications.destroy', $application->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination pagination-sm justify-content-center">
            {{ $applications->links() }}
        </div>
    @endif
</div>
<!-- Deny Application Modal -->
<div class="modal fade" id="deny-modal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="denyModalLabel">Deny Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deny-form">
                    <input type="hidden" id="deny-id" name="application_id" value="">
                    <div class="form-group">
                        <label for="reason">Reason for Denial</label>
                        <textarea id="reason" name="remarks" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Deny Application</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for DateTime Picker -->
<div class="modal fade" id="schedule-modal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Set Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="schedule-form">
                    <input type="hidden" id="schedule-id" name="application_id" value="">
                    <div class="form-group">
                        <label for="datetime">Select Date and Time</label>
                        <input type="datetime-local" id="datetime" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Set Schedule</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#status-filter').change(function() {
        const status = $(this).val();
        const search = $('#search').val();
        let url = '{{ route('applications.index') }}?';

        if (status && status !== 'all') {
            url += 'status=' + status + '&';
        }
        if (search) {
            url += 'search=' + search + '&';
        }

        window.location.href = url; // Redirect to filtered URL
    });

    $(document).on('click', '.deny-button', function() {
        const applicationId = $(this).data('id');
        const confirmation = confirm("Are you sure you want to deny this application?");
        if (confirmation) {
            $('#deny-id').val(applicationId); // Ensure the application ID is set
            $('#deny-modal').modal('show');
        }
    });

    $('#deny-form').on('submit', function(e) {
        e.preventDefault();
        const applicationId = $('#deny-id').val();
        const reason = $('#reason').val();

        $.ajax({
            url: `/applications/${applicationId}/deny`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                remarks: reason
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error denying application: ' + xhr.responseText);
            }
        });
    });

    $(document).on('click', '.set-schedule', function() {
        const applicationId = $(this).data('id');
        $('#schedule-id').val(applicationId);
        $('#schedule-modal').modal('show');
    });

    $('#schedule-form').on('submit', function(e) {
        e.preventDefault();
        const applicationId = $('#schedule-id').val();
        const dateTime = $('#datetime').val();

        if (dateTime) {
            $.ajax({
                url: `/applications/${applicationId}/set-schedule`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    visit_date: dateTime
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error setting schedule: ' + xhr.responseText);
                }
            });
        } else {
            alert("Please select a date and time.");
        }
    });

    $(document).on('click', '.verify', function() {
        const applicationId = $(this).data('id');
        const confirmation = confirm("Are you sure you want to verify this application?");
        if (confirmation) {
            $.ajax({
                url: `/applications/${applicationId}/verify`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error verifying application: ' + xhr.responseText);
                }
            });
        }
    });

    // Delete Application
    $('form[action*="destroy"]').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'DELETE',
            url: form.attr('action'),
            data: {_token: '{{ csrf_token() }}'},
            success: function(data) {
                if (data.success) {
                    toastr.success('Application deleted successfully!');
                    form.closest('tr').remove();
                } else {
                    toastr.error('Failed to delete application!');
                }
            }
        });
    });

    $(document).ready(function() {
        $('#search').on('input', function() {
            // Automatically submit the form when the input changes
            $('#search-form').submit();
        });
    });
</script>
@endsection