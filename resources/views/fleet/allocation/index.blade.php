@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Fleet Management &raquo; Allocation List</h4>
    <a href="{{ route('allocation.create') }}" class="btn btn-success mb-3">Create New Allocation</a>

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>PHB</th>
                <th>Category</th>
                <th>Allocation (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $index => $al)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($al->altn_date)->format('d-m-Y') }}</td>
                <td>{{ $al->altn_phb }}</td>
                <td>{{ $al->altn_category }}</td>
                <td>{{ number_format($al->altn_allocation, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
