@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Fleet Management &raquo; Create Allocation</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('allocation.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Year</label>
            <select name="altn_year" class="form-select" required>
                <option value="">-- Select Year --</option>
                @for($year = now()->year + 1; $year >= 2010; $year--)
                    <option value="{{ $year }}" {{ old('altn_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label>Assign To</label>
            <select name="altn_phb" class="form-select" required>
                @foreach($phbs as $phb)
                    <option value="{{ $phb }}" {{ old('altn_phb') == $phb ? 'selected' : '' }}>{{ $phb }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Allocation Category</label>
            <select name="altn_category" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('altn_category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Allocation (RM)</label>
            <input type="number" name="altn_allocation" step="0.01" class="form-control" required value="{{ old('altn_allocation') }}">
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="altn_date" class="form-control" required value="{{ old('altn_date') }}">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('allocation.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
