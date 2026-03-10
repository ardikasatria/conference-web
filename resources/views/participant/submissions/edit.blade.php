@extends('layouts.vertical')

@section('title', 'Edit Submission')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Edit Submission', 'subtitle' => \Illuminate\Support\Str::limit($submission->title, 50)])

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('participant.submissions.update', $submission) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Paper Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $submission->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Abstract <span class="text-danger">*</span></label>
                            <textarea name="abstract" rows="6" class="form-control @error('abstract') is-invalid @enderror" required>{{ old('abstract', $submission->abstract) }}</textarea>
                            @error('abstract')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keywords</label>
                            <input type="text" name="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords', is_array($submission->keywords) ? implode(', ', $submission->keywords) : $submission->keywords) }}">
                            @error('keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Co-Authors</label>
                            <input type="text" name="co_authors" class="form-control @error('co_authors') is-invalid @enderror" value="{{ old('co_authors', is_array($submission->co_authors) ? implode(', ', $submission->co_authors) : $submission->co_authors) }}">
                            @error('co_authors')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paper File (PDF, DOC, DOCX)</label>
                            @if($submission->file_path)
                                <p class="text-muted mb-1"><i class="ti ti-file me-1"></i> Current: {{ basename($submission->file_path) }}</p>
                            @endif
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Leave blank to keep current file. Max 10MB.</small>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Conference</label>
                            <input type="text" class="form-control" value="{{ $submission->conference->name ?? '-' }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Topic</label>
                            <input type="text" name="topic" class="form-control @error('topic') is-invalid @enderror" value="{{ old('topic', $submission->topic) }}">
                            @error('topic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Presenter Name <span class="text-danger">*</span></label>
                            <input type="text" name="presenter_name" class="form-control @error('presenter_name') is-invalid @enderror" value="{{ old('presenter_name', $submission->presenter_name) }}" required>
                            @error('presenter_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Presenter Email <span class="text-danger">*</span></label>
                            <input type="email" name="presenter_email" class="form-control @error('presenter_email') is-invalid @enderror" value="{{ old('presenter_email', $submission->presenter_email) }}" required>
                            @error('presenter_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status', $submission->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ old('status', $submission->status) === 'submitted' ? 'selected' : '' }}>Submit for Review</option>
                            </select>
                        </div>

                        <div class="alert alert-info py-2">
                            <small><i class="ri-information-line me-1"></i> Current status: <strong>{{ str_replace('_', ' ', ucfirst($submission->status)) }}</strong></small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Update Submission</button>
                    <a href="{{ route('participant.submissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
