@extends('layouts.vertical')

@section('title', 'Assign Paper Review')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Assign Paper Review', 'subtitle' => 'Create a new review assignment'])

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.paper-reviews.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Conference <span class="text-danger">*</span></label>
                        <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror" required>
                            <option value="">Select Conference</option>
                            @foreach($conferences as $conference)
                                <option value="{{ $conference->id }}" {{ old('conference_id') == $conference->id ? 'selected' : '' }}>{{ $conference->name }}</option>
                            @endforeach
                        </select>
                        @error('conference_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Submission <span class="text-danger">*</span></label>
                        <select name="submission_id" class="form-select @error('submission_id') is-invalid @enderror" required>
                            <option value="">Select Submission</option>
                            @foreach($submissions as $submission)
                                <option value="{{ $submission->id }}" {{ old('submission_id') == $submission->id ? 'selected' : '' }}>{{ $submission->title }}</option>
                            @endforeach
                        </select>
                        @error('submission_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reviewer <span class="text-danger">*</span></label>
                        <select name="reviewer_id" class="form-select @error('reviewer_id') is-invalid @enderror" required>
                            <option value="">Select Reviewer</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ old('reviewer_id') == $reviewer->id ? 'selected' : '' }}>{{ $reviewer->name }} ({{ $reviewer->email }})</option>
                            @endforeach
                        </select>
                        @error('reviewer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Assign Review</button>
                    <a href="{{ route('admin.paper-reviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
