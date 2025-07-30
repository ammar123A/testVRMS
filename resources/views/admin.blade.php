@extends('layouts.app')

@section('content')

    <div id="Menu">
        <div class="clock"></div>
    </div>

    <style type="text/css">
        .name1 { border-bottom:1px dotted #ccc; padding:1%; font-weight:bold; margin-bottom:20px; }
        .content label.text { color:#0000ff; margin-left:5px; }
        .content li { padding:3px; }
        .content li a { color:#0000ff; text-decoration:none; }

        #indicator { margin-bottom:10px; }
        #indicator .td-indicator { background-color:#f4f4f4; }
        #indicator label { font-weight:bold; text-transform:uppercase; font-style:italic; }
    </style>

    <script type="text/javascript">
        $(function(){
            $('#pwd')
                .css('margin-bottom', '5px')
                .button({ icons: { primary: 'ui-icon-locked' }})
                .click(function(){
                    window.location.href = "{{ url('/change-password') }}";
                });
        });
    </script>

    <h4>Profile Admin</h4>
    <a href="{{ url('/change-password') }}">
        <button type="button">Change Password</button>
    </a>



    <div class="info">
        <div class="content">
            @if(auth()->user()->role === 'staff')
            <ul>
                <li><label class="tag">Username:</label> <label class="text">{{ auth()->user()->username }}</label></li>
                <li><label class="tag">Name:</label> <label class="text">{{ auth()->user()->name }}</label></li>
                <li><label class="tag">Staff No:</label> <label class="text">{{ auth()->user()->em_id ?? '-' }}</label></li>
                <li><label class="tag">Faculty:</label> <label class="text">{{ auth()->user()->faculty ?? '-' }}</label></li>
                <li><label class="tag">Department:</label> <label class="text">{{ auth()->user()->department ?? '-' }}</label></li>
                <li><label class="tag">Unit:</label> <label class="text">{{ auth()->user()->unit ?? '-' }}</label></li>
                <li><label class="tag">Designation:</label> <label class="text">{{ auth()->user()->designation ?? '-' }}</label></li>
                <li><label class="tag">Campus:</label> <label class="text">{{ strtoupper(strtolower(auth()->user()->campus ?? '-')) }}</label></li>
            </ul>
            @else
            <ul>
                <li><label class="tag">Username:</label> <label class="text">{{ auth()->user()->username }}</label></li>
                <li><label class="tag">Name:</label> <label class="text">{{ auth()->user()->name }}</label></li>
                <li><label class="tag">Matric No:</label> <label class="text">{{ auth()->user()->em_id ?? '-' }}</label></li>
                <li><label class="tag">Department / Faculty:</label> 
                    <label class="text">{{ auth()->user()->department ?? '-' }} / {{ auth()->user()->faculty ?? '-' }}</label></li>
                <li><label class="tag">Campus:</label> 
                    <label class="text">{{ strtoupper(strtolower(auth()->user()->campus ?? '-')) }}</label></li>
            </ul>
            @endif
        </div>
    </div>
@endsection