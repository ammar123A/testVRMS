function Reports(Index) {
	var el = document.forms['searchform'].elements;
	var file;
	var params = 'flag=search';
	
	if (Index > 0) {
		if (Index == 1) {
			//	Vehicle Reservation by Month-x
			file = '../reservation-management/reports/index_vehicle_reservation_by_month.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&s_requestor_type=' + el['search[requestor_type]'].value;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if (Index == 2) {
			//	Reservation by Vehicle Type (COMPLETED)
			file = 'reports/reservation-management/index_reservation_by_vehicle_type.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if(Index == 3) {
			//	List of Vehicle-x
			file = '../supervisor/reports/index_list_vehicle.php';
			params += '&s_site_id=' + el['search[site_id]'].value;
		}
		else if(Index == 4) {
			//	List of Driver-x
			file = '../supervisor/reports/index_list_driver.php';
			params += '&s_site_id=' + el['search[site_id]'].value;
		}
		else if (Index == 5) {
			//	Monthly Vehicle Usage by PTJ (Bus Only) (COMPLETED)
			file = 'reports/reservation-management/index_monthly_vehicle_usage_by_ptj_bus.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if (Index == 6) {
			//	Monthly Vehicle Usage by PTJ (Except Bus) (COMPLETED)
			file = 'reports/reservation-management/index_monthly_vehicle_usage_by_ptj_xbus.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if (Index == 7) {
			//	Vehicle Monthly Usage (COMPLETED)
			file = 'reports/reservation-management/index_vehicle_monthly_usage.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&s_vehicle_type=' + el['s_vehicle_type'].value;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if (Index == 8) {
			//	Driver Monthly Trip (COMPLETED)
			file = 'reports/reservation-management/index_driver_monthly_trip.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if (Index == 9) {
			//	List of Reservation by PTJ (Bus Only) (COMPLETED)
			file = 'reports/reservation-management/index_list_of_reservation_by_ptj_bus.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&s_dv_id=' + el['s_dv_id'].value;
			params += '&s_dv_name=' + el['s_dv_id'].options[el['s_dv_id'].selectedIndex].text;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else if (Index == 10) {
			//	List of Reservation by PTJ (Except Bus)
			file = 'reports/reservation-management/index_list_of_reservation_by_ptj_xbus.php';
			params += '&s_year=' + el['search[year]'].value;
			params += '&s_dv_id=' + el['s_dv_id'].value;
			params += '&s_dv_name=' + el['s_dv_id'].options[el['s_dv_id'].selectedIndex].text;
			params += '&report_type=' + el['search[report_type]'].value;
		}
		else
			return;
	}
	else
		return;
	
	$('#IdReports').css('font-style', 'italic');
	$('#IdReports').css('text-align', 'center');
	$('#IdReports').empty().html('Loading...');
	$('#IdReports').show();
	$.ajax({
		type: 'POST',
		url: file,
		data: params,
		success: function(html) {
			$('#IdReports').css('font-style', 'normal'),
			$('#IdReports').css('text-align', 'left'),
			$('#IdReports').html(html)
		}
	});
}