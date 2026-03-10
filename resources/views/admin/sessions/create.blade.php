@extends('layouts.vertical')

@section('title', 'Create Session')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Create Session', 'subtitle' => 'Add a new session'])

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sessions.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Conference <span class="text-danger">*</span></label>
                            <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror" required>
                                <option value="">Select Conference</option>
                                @foreach($conferences as $conference)
                                    <option value="{{ $conference->id }}" {{ old('conference_id') == $conference->id ? 'selected' : '' }}>{{ $conference->name }}</option>
                                @endforeach
                            </select>
                            @error('conference_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room</label>
                                <input type="text" name="room" class="form-control @error('room') is-invalid @enderror" value="{{ old('room') }}">
                                @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Capacity</label>
                                <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" min="0">
                                @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                @foreach(['scheduled', 'ongoing', 'completed', 'cancelled'] as $s)
                                    <option value="{{ $s }}" {{ old('status', 'scheduled') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Speakers</label>
                            <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                                @foreach($speakers as $speaker)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="speakers[]" value="{{ $speaker->id }}" id="speaker_{{ $speaker->id }}"
                                            {{ in_array($speaker->id, old('speakers', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="speaker_{{ $speaker->id }}">{{ $speaker->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('speakers')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Create Session</button>
                    <a href="{{ route('admin.sessions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
