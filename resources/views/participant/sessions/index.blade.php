@extends('layouts.vertical')

@section('title', 'My Sessions')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'My Sessions', 'subtitle' => 'Sessions from your registered conferences'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">Registered Sessions</h4>
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search session..." value="{{ request('search') }}">
                <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Session Title</th>
                            <th>Conference</th>
                            <th>Date & Time</th>
                            <th>Room</th>
                            <th>Speakers</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr>
                            <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                            <td><strong>{{ $session->title }}</strong></td>
                            <td>{{ $session->conference->name ?? '-' }}</td>
                            <td>
                                {{ $session->start_time?->format('d M Y, H:i') }}
                                @if($session->end_time)
                                    <br><small class="text-muted">to {{ $session->end_time->format('H:i') }}</small>
                                @endif
                            </td>
                            <td>{{ $session->room ?? '-' }}</td>
                            <td>
                                @foreach($session->speakers as $speaker)
                                    <span class="badge bg-soft-info text-info">{{ $speaker->name }}</span>
                                @endforeach
                                @if($session->speakers->isEmpty()) <span class="text-muted">TBA</span> @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = ['scheduled' => 'primary', 'ongoing' => 'info', 'completed' => 'success', 'cancelled' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$session->status] ?? 'secondary' }}">
                                    {{ ucfirst($session->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No sessions found. Register for a conference to see your sessions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $sessions->links() }}</div>
        </div>
    </div>
</div>
@endsection
