@extends('layouts.vertical')

@section('title', 'Review Paper')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Review Paper', 'subtitle' => \Illuminate\Support\Str::limit($paper_review->submission->title ?? '', 60)])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row">
        {{-- Paper Details --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Paper Details</h4>
                </div>
                <div class="card-body">
                    <h5>{{ $paper_review->submission->title ?? 'N/A' }}</h5>
                    <p class="text-muted mb-2"><strong>Author:</strong> {{ $paper_review->submission->user->name ?? 'N/A' }}</p>
                    <p class="text-muted mb-2"><strong>Conference:</strong> {{ $paper_review->conference->name ?? '-' }}</p>
                    <p class="text-muted mb-2"><strong>Topic:</strong> {{ $paper_review->submission->topic ?? '-' }}</p>

                    @if($paper_review->submission->keywords)
                        <p class="mb-2">
                            <strong>Keywords:</strong>
                            @foreach((array) $paper_review->submission->keywords as $kw)
                                <span class="badge bg-soft-primary text-primary">{{ $kw }}</span>
                            @endforeach
                        </p>
                    @endif

                    @if($paper_review->submission->abstract)
                        <div class="mt-3">
                            <strong>Abstract:</strong>
                            <p class="text-muted mt-1">{{ $paper_review->submission->abstract }}</p>
                        </div>
                    @endif

                    @if($paper_review->submission->file_path)
                        <a href="{{ asset('storage/' . $paper_review->submission->file_path) }}" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
                            <i class="ti ti-download me-1"></i> Download Paper
                        </a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Review Status</h4>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $paper_review->status === 'completed' ? 'success' : ($paper_review->status === 'in_progress' ? 'info' : 'warning') }}">
                            {{ str_replace('_', ' ', ucfirst($paper_review->status)) }}
                        </span>
                    </p>
                    @if($paper_review->total_score)
                        <p class="mb-2"><strong>Total Score:</strong> {{ number_format($paper_review->total_score, 1) }}</p>
                    @endif
                    @if($paper_review->recommendation)
                        <p class="mb-0"><strong>Recommendation:</strong>
                            <span class="badge bg-{{ $paper_review->recommendation === 'accept' ? 'success' : ($paper_review->recommendation === 'reject' ? 'danger' : 'warning') }}">
                                {{ str_replace('_', ' ', ucfirst($paper_review->recommendation)) }}
                            </span>
                        </p>
                    @endif

                    @if($paper_review->status === 'pending')
                        <form method="POST" action="{{ route('reviewer.assigned-papers.start-review', $paper_review) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary"><i class="ti ti-player-play me-1"></i> Start Review</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Review Form --}}
        <div class="col-lg-7">
            @if($paper_review->status === 'in_progress')
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Submit Review</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reviewer.assigned-papers.submit-review', $paper_review) }}">
                        @csrf

                        @if($gradingCriteria->isNotEmpty())
                        <h5 class="mb-3">Grading Criteria</h5>
                        @foreach($gradingCriteria as $i => $criteria)
                            <div class="card bg-light border mb-3">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $criteria->name }}</strong>
                                            @if($criteria->description)
                                                <br><small class="text-muted">{{ $criteria->description }}</small>
                                            @endif
                                        </div>
                                        <span class="badge bg-primary">Max: {{ $criteria->max_score }}</span>
                                    </div>
                                    <input type="hidden" name="grades[{{ $i }}][criteria_id]" value="{{ $criteria->id }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">Score <span class="text-danger">*</span></label>
                                            <input type="number" name="grades[{{ $i }}][score]" class="form-control @error("grades.{$i}.score") is-invalid @enderror" min="0" max="{{ $criteria->max_score }}" step="0.5" required
                                                value="{{ old("grades.{$i}.score", $paper_review->grades->where('grading_criteria_id', $criteria->id)->first()?->score) }}">
                                            @error("grades.{$i}.score")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Notes</label>
                                            <input type="text" name="grades[{{ $i }}][notes]" class="form-control" placeholder="Optional notes..."
                                                value="{{ old("grades.{$i}.notes", $paper_review->grades->where('grading_criteria_id', $criteria->id)->first()?->notes) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                            <div class="alert alert-warning">No grading criteria defined for this conference.</div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Recommendation <span class="text-danger">*</span></label>
                            <select name="recommendation" class="form-select @error('recommendation') is-invalid @enderror" required>
                                <option value="">Select recommendation</option>
                                @foreach(['accept' => 'Accept', 'minor_revision' => 'Minor Revision', 'major_revision' => 'Major Revision', 'reject' => 'Reject'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('recommendation') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('recommendation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comments <span class="text-danger">*</span></label>
                            <textarea name="comments" rows="5" class="form-control @error('comments') is-invalid @enderror" required placeholder="Provide detailed feedback...">{{ old('comments') }}</textarea>
                            @error('comments')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Submit this review? This action cannot be undone.')">
                                <i class="ti ti-send me-1"></i> Submit Review
                            </button>
                            <a href="{{ route('reviewer.assigned-papers.index') }}" class="btn btn-outline-secondary">Back to List</a>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($paper_review->status === 'completed')
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Review Summary</h4>
                </div>
                <div class="card-body">
                    @if($paper_review->grades->isNotEmpty())
                    <h5 class="mb-3">Grades</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr><th>Criteria</th><th>Score</th><th>Notes</th></tr>
                            </thead>
                            <tbody>
                                @foreach($paper_review->grades as $grade)
                                <tr>
                                    <td>{{ $grade->criteria->name ?? 'N/A' }}</td>
                                    <td><strong>{{ number_format($grade->score, 1) }}</strong> / {{ $grade->criteria->max_score ?? '-' }}</td>
                                    <td>{{ $grade->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($paper_review->comments)
                    <h5 class="mb-2">Comments</h5>
                    <p class="text-muted">{{ $paper_review->comments }}</p>
                    @endif

                    <a href="{{ route('reviewer.assigned-papers.index') }}" class="btn btn-outline-secondary mt-2">
                        <i class="ti ti-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ti ti-clipboard-check fs-48 text-muted mb-3 d-block"></i>
                    <h5>Start reviewing this paper</h5>
                    <p class="text-muted">Click "Start Review" on the left to begin your evaluation.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
