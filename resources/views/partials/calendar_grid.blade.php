@php
    use Carbon\Carbon;
    $startDate = Carbon::create($year, $month, 1);
    $endDate = $startDate->copy()->endOfMonth();
    $weeks = [];
    $current = $startDate->copy()->startOfWeek(Carbon::SUNDAY);
    while ($current <= $endDate->copy()->endOfWeek(Carbon::SATURDAY)) {
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $current->copy();
            $current->addDay();
        }
        $weeks[] = $week;
    }
@endphp

<table class="table table-bordered text-center">
    <thead>
        <tr class="table-secondary">
            <th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th>
            <th>Thursday</th><th>Friday</th><th>Saturday</th>
        </tr>
    </thead>
    <tbody>
        @foreach($weeks as $week)
            <tr>
                @foreach($week as $day)
                    <td class="{{ $day->month != $month ? 'bg-light' : 'bg-warning' }}">
                        {{ $day->day }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
