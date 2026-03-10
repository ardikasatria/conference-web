@extends('layouts.vertical', ['title' => 'Admin Dashboard'])

@section('content')
<div class="container-xxl">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-sm-0">Admin Dashboard</h1>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active">Admin</li>
            </ol>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        <!-- Total Registrations -->
        <div class="col">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <div>
                        <h4 class="header-title">Total Registrations</h4>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-end gap-2 justify-content-between">
                        <div>
                            <h3 class="fw-semibold">1,245</h3>
                            <p class="text-muted mb-0">Active participants</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Submissions -->
        <div class="col">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <div>
                        <h4 class="header-title">Total Submissions</h4>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-end gap-2 justify-content-between">
                        <div>
                            <h3 class="fw-semibold">487</h3>
                            <p class="text-muted mb-0">Papers submitted</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="col">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <div>
                        <h4 class="header-title">Pending Reviews</h4>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-end gap-2 justify-content-between">
                        <div>
                            <h3 class="fw-semibold">124</h3>
                            <p class="text-muted mb-0">Awaiting review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <div>
                        <h4 class="header-title">Total Revenue</h4>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-end gap-2 justify-content-between">
                        <div>
                            <h3 class="fw-semibold">$62,450</h3>
                            <p class="text-muted mb-0">From registrations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Recent Registrations</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@example.com</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                    <td>2026-03-08</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>jane@example.com</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>2026-03-07</td>
                                </tr>
                                <tr>
                                    <td>Bob Johnson</td>
                                    <td>bob@example.com</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                    <td>2026-03-06</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Recent Submissions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>AI in Healthcare</td>
                                    <td>Dr. Smith</td>
                                    <td><span class="badge bg-info">Under Review</span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td>Climate Science</td>
                                    <td>Prof. Johnson</td>
                                    <td><span class="badge bg-success">Accepted</span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td>Renewable Energy</td>
                                    <td>Dr. Brown</td>
                                    <td><span class="badge bg-danger">Rejected</span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Tools -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Admin Tools</h4>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-primary me-2">Manage Conferences</a>
                    <a href="#" class="btn btn-info me-2">Manage Users</a>
                    <a href="#" class="btn btn-warning me-2">Manage Reviewers</a>
                    <a href="#" class="btn btn-secondary">Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
