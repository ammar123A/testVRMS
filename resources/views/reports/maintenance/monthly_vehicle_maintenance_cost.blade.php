@extends('layouts.app') {{-- Or your preferred layout --}}

@section('content')
<style>
    .td-left-search {
        width: 15%;
    }

    .td-right-search {
        width: 35%;
    }

    .search-box {
        border: 1px solid #ccc;
        background: #f9f9f9;
        padding: 15px;
        margin-bottom: 20px;
    }

    #result {
        margin-top: 20px;
    }
</style>

<div class="title">Report &raquo; Monthly Vehicle Maintenance Cost</div>

<form id="search-form">
    <div class="search-box">
        <div class="content">
            <div class="row mb-3">
                <label for="year" class="col-md-2 td-left-search">Year:</label>
                <div class="col-md-4 td-right-search">
                    <select id="year" name="year" class="form-control">
                        <option value=""></option>
                        @for ($i = date('Y'); $i > 2009; $i--)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <label for="report_type" class="col-md-2 td-left-search">Report Type:</label>
                <div class="col-md-4 td-right-search">
                    <select id="report_type" name="report_type" class="form-control">
                        <option value="LIST">LIST</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="vehicle_type" class="col-md-2 td-left-search">Vehicle Type:</label>
                <div class="col-md-4 td-right-search">
                    <select id="vehicle_type" name="vehicle_type" class="form-control">
                        @foreach ($vehicleTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 td-left-search">
                    <button type="button" id="search" class="btn btn-primary">Search...</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="result">
    {{-- The report data will be loaded here via AJAX --}}
</div>

<script>
    $(function () {
        $('#search').click(function () {
            let params = {
                flag: 'search',
                s_year: $('#year').val(),
                report_type: $('#report_type').val(),
                vehicle_type: $('#vehicle_type').val()
            };

            $('#result').css({
                'font-style': 'italic',
                'text-align': 'center'
            }).html('Loading...');

            $.ajax({
                type: 'POST',
                url: "{{ route('reports.maintenance.monthlyVehicleCostAjax') }}", // define this in web.php
                data: params,
                success: function (html) {
                    $('#result').css({
                        'font-style': 'normal',
                        'text-align': 'left'
                    }).html(html);
                },
                error: function () {
                    $('#result').html('<div class="alert alert-danger">Error loading report.</div>');
                }
            });
        });
    });
</script>
@endsection
