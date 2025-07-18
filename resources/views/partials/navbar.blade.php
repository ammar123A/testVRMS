<div id="myslidemenu" class="jqueryslidemenu">
    <ul>
        <li><a href="{{ url('/main') }}">Home</a></li>

        <li><a href="#">Reservation</a>
            <ul>
                <li><a href="{{ url('/history') }}">History</a></li>
                <li><a href="{{ url('/create-request') }}">Create Reservation</a></li>
            </ul>
        </li>

        <li><a href="#">Fleet Management</a>
            <ul>
                <li><a href="{{ url('/allocation') }}">Create Allocation</a></li>
                <li><a href="{{ url('/allocation/history') }}">History</a></li>
                <li><a href="{{ url('/allocation/recommend') }}">Recommend New Reservation</a></li>
                <li><a href="{{ url('/allocation/recommend/history') }}">Recommend History</a></li>
                <li><a href="{{ url('/allocation/report/reservations-by-ptj') }}">List of Reservation by PTJ (All Site & Vehicle Type)</a></li>
            </ul>
        </li>

        <li><a href="#">Reservation Management</a>
            <ul>
                <li><a href="{{ url('/reservation-management') }}">Create Request</a></li>
                <li><a href="{{ url('/reservation-management/history') }}">History</a></li>
                <li><a href="{{ url('/workorder/history') }}">WorkdOrder History</a></li>
                <li><a href="{{ url('/workorder/calendar') }}">WorkdOrder Calendar</a></li>
                <li><a href="{{ url('/reports/reservation-by-vehicle-type') }}">Reservation by Vehicle Type</a></li>
                <li><a href="{{ url('/reports/reservation-cost-bus') }}">Reservation Cost Bus</a></li>
                <li><a href="{{ url('/reports/list-by-ptj-all-site') }}">List by ptj all site</a></li>
                <li><a href="{{ url('/reports/list-by-ptj-bus') }}">List by ptj Bus</a></li>
                <li><a href="{{ url('/reports/driver-monthly-trip') }}">Driver Monthly Trip</a></li>
                <li><a href="{{ url('/reports/vehicle-monthly-usage') }}">Vehicle Monthly Usage</a></li>
                <li><a href="{{ url('/reports/monthly-chart') }}">Monthly Chart by Ptj</a></li>
                <li><a href="{{ url('/reports/work-order-details') }}">WorkOrder Details</a></li>
                <li><a href="{{ url('/reports/work-order-charted') }}">WorkOrder Charted</a></li>
                <li><a href="{{ url('/reports/charted-trip') }}">Charted Trip</a></li>
                <!-- <li><a href="{{ url('/allocation/recommend/history') }}">Recommend History</a></li> -->
                <!-- <li><a href="{{ url('/allocation/report/reservations-by-ptj') }}">List of Reservation by PTJ (All Site & Vehicle Type)</a></li> -->
            </ul>
        </li>
        <li><a href="#">Supervisor</a>
            <ul>
                <li><a href="{{ url('/supervisor/vehicle') }}">Vehicle</a></li>
                <li><a href="{{ url('/supervisor/driver') }}">Driver</a></li>
                <li><a href="{{ url('/supervisor/leave') }}">leave</a></li>
                <li><a href="{{ url('/supervisor/permission') }}">permission</a></li>
                <li><a href="{{ url('/supervisor/company') }}">company</a></li>
            </ul>
        </li>
        <li><a href="#">Maintenance</a></li>
        <li><a href="#">System Administration</a></li>
        <li><a href="#">Statistic</a></li>

        <!-- LOGOUT LINK -->
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </li>
    </ul>
    <br style="clear: left" />
</div>

<!-- HIDDEN LOGOUT FORM -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
