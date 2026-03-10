@extends('layouts.vertical', ['title' => 'Reviewer Dashboard'])

@section('content')
<div class="container-xxl">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-sm-0">Reviewer Dashboard</h1>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active">Reviewer</li>
            </ol>
        </div>
    </div>

    <!-- Review Statistics -->
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        <!-- Assigned Papers -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Assigned Papers</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-primary mb-2">8</h3>
                    <p class="text-muted mb-0">Papers to review</p>
                </div>
            </div>
        </div>

        <!-- Completed Reviews -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Completed Reviews</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-success mb-2">5</h3>
                    <p class="text-muted mb-0">Submitted reviews</p>
                </div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Pending Reviews</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-warning mb-2">3</h3>
                    <p class="text-muted mb-0">In progress</p>
                </div>
            </div>
        </div>

        <!-- Review Deadline -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Deadline</h4>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-semibold text-danger mb-2">15 days</h3>
                    <p class="text-muted mb-0">May 15, 2026</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Papers for Review -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Papers for Review</h4>
                    <div>
                        <span class="badge bg-primary">8 Total</span>
                        <span class="badge bg-warning">3 Pending</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paper Title</th>
                                    <th>Authors</th>
                                    <th>Topic</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>AI in Healthcare Systems</strong></td>
                                    <td>Dr. Smith, Dr. Johnson</td>
                                    <td>Artificial Intelligence</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><a href="#" class="btn btn-sm btn-primary">Review</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Climate Change Impact Analysis</strong></td>
                                    <td>Prof. Brown</td>
                                    <td>Environmental Science</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><a href="#" class="btn btn-sm btn-primary">Review</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Renewable Energy Solutions</strong></td>
                                    <td>Dr. Green, Dr. Lee</td>
                                    <td>Energy Technology</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><a href="#" class="btn btn-sm btn-primary">Review</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Biomedical Applications</strong></td>
                                    <td>Dr. Martinez</td>
                                    <td>Biomedical Science</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Materials Science Advances</strong></td>
                                    <td>Prof. Wilson</td>
                                    <td>Materials Science</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Digital Transformation</strong></td>
                                    <td>Dr. Taylor</td>
                                    <td>Technology</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Guidelines & Resources -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Review Guidelines</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                            <strong>Originality:</strong> Check if the work is original and not previously published
                        </li>
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                            <strong>Quality:</strong> Evaluate the technical quality and methodology
                        </li>
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                            <strong>Relevance:</strong> Assess relevance to conference topics
                        </li>
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                            <strong>Clarity:</strong> Check presentation and writing quality
                        </li>
                        <li>
                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                            <strong>Significance:</strong> Evaluate contribution to the field
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Expert Topics</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Your Assigned Topics:</h5>
                        <div>
                            <span class="badge bg-primary me-2 mb-2">Artificial Intelligence</span>
                            <span class="badge bg-info me-2 mb-2">Technology</span>
                            <span class="badge bg-success mb-2">Sustainable Development</span>
                        </div>
                    </div>
                    <hr/>
                    <p class="text-muted mb-2"><strong>Note:</strong> You will be assigned papers primarily in these topic areas.</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Edit Topics</a>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Resources</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-secondary btn-sm">Reviewer Handbook</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Evaluation Rubric</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Summary -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Your Review History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paper</th>
                                    <th>Decision</th>
                                    <th>Confidence</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Quantum Computing Basics</td>
                                    <td><span class="badge bg-success">Accept</span></td>
                                    <td><span class="badge bg-light text-dark">High</span></td>
                                    <td>2026-03-05</td>
                                </tr>
                                <tr>
                                    <td>Blockchain Applications</td>
                                    <td><span class="badge bg-warning">Minor Revision</span></td>
                                    <td><span class="badge bg-light text-dark">High</span></td>
                                    <td>2026-03-04</td>
                                </tr>
                                <tr>
                                    <td>IoT Security Issues</td>
                                    <td><span class="badge bg-danger">Reject</span></td>
                                    <td><span class="badge bg-light text-dark">Medium</span></td>
                                    <td>2026-03-01</td>
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
