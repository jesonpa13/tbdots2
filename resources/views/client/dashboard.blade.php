@extends('layouts.userlayout')

@section('content')
    <h2><strong>WELCOME TO USER DASHBOARD</strong></h2>

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

        <!-- Modal for Additional Information -->
        @if ($hasAdditionalInformation === false)
            <div class="modal fade" id="additionalInfoModal" tabindex="-1" role="dialog" aria-labelledby="additionalInfoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="additionalInfoModalLabel">Additional Information Required</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('additional-info.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="province_city" class="form-label">Province</label>
                                    <input type="text" name="province_city" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="facility" class="form-label">Facility</label>
                                    <input type="text" name="facility" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="head_of_unit" class="form-label">Head Of Unit</label>
                                    <input type="text" name="head_of_unit" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation</label>
                                    <input type="text" name="designation" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#additionalInfoModal').modal('show');
                });
            </script>
        @endif
    </div>
@endsection
