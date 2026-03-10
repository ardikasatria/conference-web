@extends('layouts.vertical')

@section('title', 'New Submission')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'New Submission', 'subtitle' => 'Submit a new paper'])

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('participant.submissions.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Paper Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Abstract <span class="text-danger">*</span></label>
                            <textarea name="abstract" rows="6" class="form-control @error('abstract') is-invalid @enderror" required>{{ old('abstract') }}</textarea>
                            <small class="text-muted">Minimum 100 characters</small>
                            @error('abstract')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keywords</label>
                            <input type="text" name="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords') }}" placeholder="Separate with commas">
                            @error('keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Co-Authors</label>
                            <input type="text" name="co_authors" class="form-control @error('co_authors') is-invalid @enderror" value="{{ old('co_authors') }}" placeholder="Separate with commas">
                            @error('co_authors')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paper File (PDF, DOC, DOCX)</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Max 10MB</small>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Conference <span class="text-danger">*</span></label>
                            <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror" required>
                                <option value="">Select Conference</option>
                                @foreach($conferences as $conf)
                                    <option value="{{ $conf->id }}" {{ old('conference_id') == $conf->id ? 'selected' : '' }}>{{ $conf->name }}</option>
                                @endforeach
                            </select>
                            @error('conference_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if($registrations->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label">Registration</label>
                            <select name="registration_id" class="form-select @error('registration_id') is-invalid @enderror">
                                <option value="">None</option>
                                @foreach($registrations as $reg)
                                    <option value="{{ $reg->id }}" {{ old('registration_id') == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->conference->name ?? '' }} — {{ $reg->ticket_number ?? 'No Ticket' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('registration_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Topic</label>
                            <input type="text" name="topic" class="form-control @error('topic') is-invalid @enderror" value="{{ old('topic') }}">
                            @error('topic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Presenter Name <span class="text-danger">*</span></label>
                            <input type="text" name="presenter_name" class="form-control @error('presenter_name') is-invalid @enderror" value="{{ old('presenter_name', auth()->user()->name) }}" required>
                            @error('presenter_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Presenter Email <span class="text-danger">*</span></label>
                            <input type="email" name="presenter_email" class="form-control @error('presenter_email') is-invalid @enderror" value="{{ old('presenter_email', auth()->user()->email) }}" required>
                            @error('presenter_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Submit Paper</button>
                    <a href="{{ route('participant.submissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
