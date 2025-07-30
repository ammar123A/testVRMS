<div id="myslidemenu" class="jqueryslidemenu">
    <ul>
        <li><a href="{{ url('/main') }}">Home</a></li>

        <li><a href="#">Reservation <i class="fa fa-caret-down"></i></a>
            <ul>
                <li><a href="{{ url('/history') }}">History</a></li>
                <li><a href="{{ url('/create-request') }}">Create Reservation</a></li>
            </ul>
        </li>
<!-- 
        <li><a>Fleet Management <i class="fa fa-caret-down"></i></a>
            <ul>
                <li><a href="{{ url(path: '/allocation') }}">Create Allocation</a></li>
                <li><a href="{{ url('/allocation/history') }}">History</a></li>
                <li><a href="{{ url('/allocation/recommend') }}">Recommend New Reservation</a></li>
                <li><a href="{{ url('/allocation/recommend/history') }}">Recommend History</a></li>
                <li><a href="{{ url('/allocation/report/reservations-by-ptj') }}">List of Reservation by PTJ</a></li>
            </ul>
        </li> -->

        <li><a href="#">Reservation Management <i class="fa fa-caret-down"></i></a>
            <ul>
                <li><a href="{{ url('/reservation-management/reservation/create_request') }}">Create Request</a></li>
                <li><a href="{{ url('/reservation-management/history') }}">History</a></li>
                <li><a href="{{ url('/workorder/history') }}">WorkOrder History</a></li>
                <li><a href="{{ url('/workorder/calendar') }}">WorkOrder Calendar</a></li>
                <li><a href="{{ url('/reports/reservation-by-vehicle-type') }}">Reservation by Vehicle Type</a></li>
                <li><a href="{{ url('/reports/reservation-cost-bus') }}">Reservation Cost Bus</a></li>
                <li><a href="{{ url('/reports/list-by-ptj-all-site') }}">List by PTJ (All Sites)</a></li>
                <li><a href="{{ url('/reports/list-by-ptj-bus') }}">List by PTJ (Bus Only)</a></li>
                <li><a href="{{ url('/reports/driver-monthly-trip') }}">Driver Monthly Trip</a></li>
                <li><a href="{{ url('/reports/vehicle-monthly-usage') }}">Vehicle Monthly Usage</a></li>
                <li><a href="{{ url('/reports/monthly-chart') }}">Monthly Chart by PTJ</a></li>
                <li><a href="{{ url('/reports/work-order-details') }}">WorkOrder Details</a></li>
                <li><a href="{{ url('/reports/work-order-charted') }}">WorkOrder Charted</a></li>
                <li><a href="{{ url('/reports/charted-trip') }}">Charted Trip</a></li>
            </ul>
        </li>

        <li><a href="#">Supervisor <i class="fa fa-caret-down"></i></a>
            <ul>
                <li><a href="{{ url('/supervisor/vehicle') }}">Vehicle</a></li>
                <li><a href="{{ url('/supervisor/driver') }}">Driver</a></li>
                <li><a href="{{ url('/supervisor/leave') }}">Leave</a></li>
                <li><a href="{{ url('/supervisor/permission') }}">Permission</a></li>
                <li><a href="{{ url('/supervisor/company') }}">Company</a></li>
                <li><a href="{{ url('/supervisor/technician') }}">Technician</a></li>
                <li><a href="{{ url('/supervisor/workshops') }}">Workshops</a></li>
                <li><a href="{{ url('/supervisor/parts') }}">Parts & Items</a></li>
            </ul>
        </li>

        <li><a href="#">Maintenance <i class="fa fa-caret-down"></i></a>
            <ul>
                <li><a href="{{ url('/complaint/create') }}">Create Complaint</a></li>
                <li><a href="{{ url('/complaint/history') }}">Complaint History</a></li>
                <li><a href="{{ url('/maintenance/verify-r/history') }}">History (Verification)</a></li>
                <li><a href="{{ url('/maintenance/verify-wr/history') }}">History 2</a></li>
                <li><a href="{{ url('/maintenance/vehicle/preventive') }}">Preventive</a></li>
                <li><a href="{{ url('/reports/maintenance/monthly-vehicle-cost') }}">Monthly Vehicle Cost</a></li>
                <li><a href="{{ url('/reports/maintenance/monthly-complaints') }}">Monthly Complaints</a></li>
            </ul>
        </li>

        <li><a href="#">System Administration <i class="fa fa-caret-down"></i></a>
            <ul>
                <li><a href="{{ url('/system-admin/user') }}">User</a></li>
            </ul>
        </li>

        <li><a href="#">Statistic</a></li>

        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </li>
    </ul>
    <br style="clear: left" />
</div>

{{-- Hidden logout form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
