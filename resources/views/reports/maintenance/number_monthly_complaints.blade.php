@extends('layouts.app') {{-- Or your actual layout --}}

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

<div class="title">Report &raquo; Number of Monthly Complaint</div>

<form id="search-form">
    <div class="search-box">
        <div class="content">
            <div class="row mb-3">
                <label for="year" class="col-md-2 td-left-search">Year:</label>
                <div class="col-md-4 td-right-search">
                    <select id="year" name="year" class="form-control">
                        <option value=""></option>
                        @for ($i = now()->year; $i > 2009; $i--)
                            <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <label for="report_type" class="col-md-2 td-left-search">Report Type:</label>
                <div class="col-md-4 td-right-search">
                    <select id="report_type" name="report_type" class="form-control">
                        <option value="GRAPH">GRAPH</option>
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
    {{-- AJAX-loaded content goes here --}}
</div>

<script>
    $(function () {
        $('#search').click(function () {
            let params = {
                flag: 'search',
                s_year: $('#year').val(),
                report_type: $('#report_type').val()
            };

            $('#result').css({ 'font-style': 'italic', 'text-align': 'center' }).html('Loading...');

            $.ajax({
                type: 'POST',
                url: "{{ route('reports.maintenance.monthlyComplaintGraphAjax') }}", // Define this route
                data: params,
                success: function (html) {
                    $('#result').css({ 'font-style': 'normal', 'text-align': 'left' }).html(html);
                },
                error: function () {
                    $('#result').html('<div class="alert alert-danger">Error loading graph/report.</div>');
                }
            });
        });
    });
</script>
@endsection
