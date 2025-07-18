@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Supervisor » Parts & Item Inventory</h4>

    @if(session('success'))
        <div class="alert alert-success" id="flash-message">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('parts.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label>Category:</label>
                <select name="category" class="form-control">
                    <option value="">ALL</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Part/Item:</label>
                <input type="text" name="name" class="form-control" value="{{ request('name') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Search</button>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('parts.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Add Category:</label>
                <select name="category" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Add Part/Item:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success">Add Part/Item</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>#</th>
                <th>Last Updated</th>
                <th>Category</th>
                <th>Part/Item</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parts as $index => $part)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $part->updated_at->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $part->category }}</td>
                    <td>{{ $part->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No parts/items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    setTimeout(() => {
        document.getElementById('flash-message')?.remove();
    }, 2000);
</script>
@endsection
