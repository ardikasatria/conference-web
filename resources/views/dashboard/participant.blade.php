@extends('layouts.vertical', ['title' => 'Participant Dashboard'])

@section('content')
<div class="container-xxl">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-sm-0">My Dashboard</h1>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active">Participant</li>
            </ol>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title">Welcome, {{ auth()->user()->name }}!</h5>
                    <p class="text-muted mb-0">Track your conference submissions and registration status.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        <!-- Registration Status -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Registration Status</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-success mb-2">Confirmed</h3>
                    <p class="text-muted mb-0">ICSSF 2024</p>
                </div>
            </div>
        </div>

        <!-- Submission Status -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Submission Status</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-info mb-2">Under Review</h3>
                    <p class="text-muted mb-0">1 paper submitted</p>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Payment Status</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-warning mb-2">Pending</h3>
                    <p class="text-muted mb-0">$150 due</p>
                </div>
            </div>
        </div>

        <!-- Sessions Attended -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Sessions</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-primary mb-2">3</h3>
                    <p class="text-muted mb-0">Sessions registered</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Submissions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">My Submissions</h4>
                    <a href="#" class="btn btn-sm btn-primary">Submit New Paper</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Submitted Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>AI Applications in Healthcare Systems</strong></td>
                                    <td><span class="badge bg-info">Under Review</span></td>
                                    <td>2026-02-15</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">View Conference Details</a>
                        <a href="#" class="btn btn-outline-info">Download Certificate</a>
                        <a href="#" class="btn btn-outline-success">Make Payment</a>
                        <a href="#" class="btn btn-outline-warning">Update Profile</a>
                    </div>
                </div>
            </div>

            <!-- Important Dates -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Important Dates</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-1"><strong>Conference Date:</strong></p>
                    <p class="mb-3">Sep 26-27, 2024</p>
                    <p class="text-muted mb-1"><strong>Payment Deadline:</strong></p>
                    <p class="mb-3">Aug 31, 2024</p>
                    <p class="text-muted mb-1"><strong>Final Submission:</strong></p>
                    <p>Jul 31, 2024</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registered Sessions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Registered Sessions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Session Title</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Keynote: Future of AI</strong></td>
                                    <td>Sep 26, 2024</td>
                                    <td>09:00 AM - 10:30 AM</td>
                                    <td>Main Hall</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Panel Discussion: Sustainability</strong></td>
                                    <td>Sep 26, 2024</td>
                                    <td>11:00 AM - 12:30 PM</td>
                                    <td>Room A</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Workshop: Data Science</strong></td>
                                    <td>Sep 27, 2024</td>
                                    <td>02:00 PM - 04:00 PM</td>
                                    <td>Lab B</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
