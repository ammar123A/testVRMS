@extends('layouts.app')

@section('content')
<h4>Work Order &raquo; Calendar</h4>

<div class="mb-4">
    <select id="month">
        @for ($m = 1; $m <= 12; $m++)
            <option value="{{ sprintf('%02d', $m) }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
            </option>
        @endfor
    </select>
    /
    <select id="year">
        @for ($y = now()->year + 1; $y > 2009; $y--)
            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>
    <button id="go" class="btn btn-sm btn-primary">Go</button>
</div>

<div id="calendar" class="border p-3 bg-light"></div>
@endsection

@push('scripts')
<script>
    $(function () {
        loadCalendar();

        $('#go').click(function () {
            loadCalendar();
        });

        function loadCalendar() {
            const month = $('#month').val();
            const year = $('#year').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('workorder.calendar.load') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    month: month,
                    year: year
                },
                success: function (html) {
                    $('#calendar').html(html);
                }
            });
        }
    });
</script>
@endpush
