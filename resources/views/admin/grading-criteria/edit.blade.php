@extends('layouts.vertical')

@section('title', 'Edit Grading Criterion')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Edit Grading Criterion', 'subtitle' => $grading_criterion->name])

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.grading-criteria.update', $grading_criterion) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Conference <span class="text-danger">*</span></label>
                    <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror" required>
                        <option value="">Select Conference</option>
                        @foreach($conferences as $conference)
                            <option value="{{ $conference->id }}" {{ old('conference_id', $grading_criterion->conference_id) == $conference->id ? 'selected' : '' }}>{{ $conference->name }}</option>
                        @endforeach
                    </select>
                    @error('conference_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $grading_criterion->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $grading_criterion->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Score <span class="text-danger">*</span></label>
                        <input type="number" name="max_score" class="form-control @error('max_score') is-invalid @enderror" value="{{ old('max_score', $grading_criterion->max_score) }}" min="1" required>
                        @error('max_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $grading_criterion->order) }}" min="0">
                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Update Grading Criterion</button>
                    <a href="{{ route('admin.grading-criteria.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
