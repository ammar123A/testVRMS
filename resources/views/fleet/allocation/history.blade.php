@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Fleet Management &raquo; Allocation History</h4>

    <form method="GET" action="{{ route('allocation.history') }}" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Year</label>
            <select name="year" class="form-select">
                <option value="">All</option>
                @foreach ($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Assign To (PHB)</label>
            <select name="phb" class="form-select">
                <option value="">All</option>
                @foreach ($phbs as $phb)
                    <option value="{{ $phb }}" {{ request('phb') == $phb ? 'selected' : '' }}>{{ $phb }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>PHB</th>
                <th>Category</th>
                <th class="text-end">Allocation (RM)</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allocations as $index => $alloc)
                <tr>
                    <td>{{ $allocations->firstItem() + $index }}</td>
                    <td>{{ \Carbon\Carbon::parse($alloc->altn_date)->format('d-m-Y') }}</td>
                    <td>{{ $alloc->altn_phb }}</td>
                    <td>{{ $alloc->altn_category }}</td>
                    <td class="text-end">{{ number_format($alloc->altn_allocation, 2) }}</td>
                    <td>
                        <a href="{{ route('allocation.edit', $alloc->altn_id) }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No allocation history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $allocations->withQueryString()->links() }}
    </div>
</div>
@endsection
