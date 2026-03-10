@extends('layouts.vertical')

@section('title', 'Topics')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Topics', 'subtitle' => 'Manage all topics'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Topics</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
                <a href="{{ route('admin.topics.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Slug</th>
                            <th>Conferences</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $topic)
                        <tr>
                            <td>{{ $loop->iteration + ($topics->currentPage() - 1) * $topics->perPage() }}</td>
                            <td><strong>{{ $topic->name }}</strong></td>
                            <td>{{ Str::limit($topic->description, 60) ?? '-' }}</td>
                            <td><code>{{ $topic->slug }}</code></td>
                            <td><span class="badge bg-soft-info text-info">{{ $topic->conferences_count ?? $topic->conferences->count() }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this topic?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No topics found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $topics->links() }}</div>
        </div>
    </div>
</div>
@endsection
