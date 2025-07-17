<?php

$request_array ['user_type'] = array("STAFF", "STUDENT");
$request_array ['phone'] = array("010", "011", "012", "013", "014", "016", "017", "018", "019");
$request_array ['purpose'] = array("O" => "OFFICIAL", "N" => "NON-OFFICIAL");
$request_array ['status'] = array("REQUESTED", "REJECTED", "CONFIRM", "APPROVED", "COMPLETED");
$request_array ['type'] = array( "SEND", "FETCH", "SEND AND FETCH", "UNTIL FINISH");
$request_array ['PHB'] = array(
		"UTM" => "UTM JOHOR BAHRU", 
		"UTMIC" => "UTM KUALA LUMPUR");
$request_array ['nov'] = array(
		"1" => "1", 
		"2" => "2", 
		"3" => "3", 
		"4" => "4", 
		"5" => "5", 
		"6" => "6", 
		"7" => "7");

$request_array ['VOTE'] = array(
		"U" => "BUDGET OF UNIVERSITY",
		"T" => "TRUST FUND", 
		"R" => "RESEARCH GRANT", 
		"Z" => "OTHERS"); 
//		"Z" => "Trust Fund and Research Grant");
		
$system_array ['day'] = array("SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY");
//$system_array ['role'] = array("VERIFIER", "APPROVER", "SUPERVISOR", "SYSTEM-ADMIN");
$system_array ['role'] = array(
		"ADMINISTRATOR" => array(
				"2" => "Users System Maintenance",
			),
		"FLEETMANAGEMENT" => array(
				"2" => "Allocation",
			//	"3" => "Allocation 3",
				"4" => "Recommend New Reservation",
				"6" => "Reports",
			),	
		"MANAGEMENT" => array(
				"6" => "Reservation by Vehicle",
				"2" => "Reservation by PTJ",
				"3" => "Monthly Vehicle by PTJ",
				"5" => "Statistic 4",
				"7" => "Statistic 5",
				"4" => "Statistic 6",
			),
		"RESERVATION" => array(
				"2" => "Ad-Hoc",
				"8" => "Recommend New Reservation",
				"3" => "Verify Reservation",
				"4" => "Create Work Order",
				"5" => "Verify Work Order",
				"7" => "Re-Assignment Work Order",
				"6" => "Reports",
			),
		"SUPERVISOR" => array(
				"2" => "Work Order",
				"3" => "Vehicle",
				"4" => "Driver",
				"5" => "Leave",
				"10" => "User Permission",
				"9" => "Charted Company",
				"6" => "Technician (Maintenance)",
				"7" => "Workshop (Maintenance)",
				"8" => "Parts & Items Inventory (Maintenance)",
			),
		"MAINTENANCE" => array( 
				"6" => "Create Complaint by Faculty/Department",
				"2" => "Verify Complaint (Verifier Officer)",
				"3" => "Create Work Order",
				"5" => "Preventive",
				"7" => "Technician",
				"4" => "Reports",
			),
		
	);

$vehicle_array ['type'] = array("CAR", "VAN", "4WD", "BUS", "LORRY", "MOTORCYCLE", "PICK-UP", "BACK HOE", "TRACTOR");
$vehicle_array ['fuel'] = array("PETROL", "DIESEL");
$vehicle_array ['transmission'] = array("AUTO", "MANUAL");
$vehicle_array ['image'] = array("jpg", "jpeg", "png", "gif", "bmp");

$driver_array ['image'] = array("jpg", "jpeg", "png", "gif", "bmp");

$request_array ['hour'] = array(
			"01" => "1 AM",
			"02" => "2 AM",
			"03" => "3 AM",
			"04" => "4 AM",
			"05" => "5 AM",
			"06" => "6 AM",
			"07" => "7 AM",
			"08" => "8 AM",
			"09" => "9 AM",
			"10" => "10 AM",
			"11" => "11 AM",
			"12" => "12 PM",
			"13" => "1 PM",
			"14" => "2 PM",
			"15" => "3 PM",
			"16" => "4 PM",
			"17" => "5 PM",
			"18" => "6 PM",
			"19" => "7 PM",
			"20" => "8 PM",
			"21" => "9 PM",
			"22" => "10 PM",
			"23" => "11 PM",
			"00" => "12 AM",
		);
		//subang added 6NOV12
		//KLIA2 added 11MAY14

$request_array ['state'] = array(
			"OPT-1" => "AIRPORT",
			"A-M-J-S" => "SENAI",
			"A-M-K-K" => "KLIA",
			"A-M-K-K2" => "KLIA2",
			//"A-M-K-L" => "LCCT",
			"A-S-S-C" => "CHANGI",
			"A-M-S-S" => "SUBANG",
			"OPT-2" => "CAMPUS",
			"UTM" => "UTM JB",
			"UTMIC" => "UTM KL",
			"OPT-3" => "STATE",
			"01" => "JOHOR",
			"02" => "KEDAH",
			"03" => "KELANTAN",
			"04" => "MELAKA",
			"05" => "NEGERI SEMBILAN",
			"06" => "PAHANG",
			"07" => "PULAU PINANG",
			"08" => "PERAK",
			"09" => "PERLIS",
			"10" => "SELANGOR",
			"11" => "TERENGGANU",
		//	"12" => "SABAH",
		//	"13" => "SARAWAK",
			"14" => "WP KUALA LUMPUR",
		//	"15" => "WP LABUAN",
			"16" => "WP PUTRAJAYA",
			"OPT-4" => "OTHERS",
			"S" => "SINGAPURA",
		//	"T" => "THAILAND",
		);
$request_array ['pickupfrom'] = array(
			
			"OPT-1" => "CAMPUS",
			"UTM" => "UTM JB",
			"UTMIC" => "UTM KL",
			"OPT-2" => "PICK UP POINT",
			"01" => "TMN SRI PULAI PERDANA",
			"02" => "TMN SRI PULAI",
			"03" => "TMN PULAI JAYA",
			"04" => "TMN PULAI EMAS",
			"05" => "PERLING",
			"06" => "TAMPOI",
			"07" => "BANDARAYA JOHOR BAHRU",
			"08" => "STULANG",
		);		
		
		
//subang added 6NOV12
//KLIA2 added 11MAY14
$request_array ['short-state'] = array(
			"A-M-J-S" => "A. SENAI",
			"A-M-K-K" => "A. KLIA",
			"A-M-K-K2" => "A. KLIA2",
			//"A-M-K-L" => "A. LCCT",
			"A-S-S-C" => "A. CHANGI",
			"A-M-S-S" => "SUBANG",
			"UTM" => "UTM JB",
			"UTMIC" => "UTM KL",
			"01" => "JHR",
			"02" => "KED",
			"03" => "KEL",
			"04" => "MEL",
			"05" => "N9",
			"06" => "PHG",
			"07" => "PP",
			"08" => "PRK",
			"09" => "PER",
			"10" => "SEL",
			"11" => "TER",
			"12" => "SBH",
			"13" => "SRK",
			"14" => "KL",
			"15" => "LBN",
			"16" => "PJAYA",
			"S" => "SIN",
		);

$vehicle_array ['manufacture'] = array(
			"BMW", "Case", "Chevrolet", "Citroen", "Daihatsu", "Fiat", "Ford", "Hicom", "Hino", "Honda", "Hyundai", "Inokom", "Isuzu", "Jeep", "Kia", "Mahindra", "Mazda", "Mercedes-Benz", "Modenas", "Mitsubishi", "Naza", "Nissan", "Perodua", "Peugeot", "Proton", "Renault", "Rolls-Royce", "Rover", "Ssangyong", "Suzuki", "TATA", "Toyota", "Yamaha", "Volvo", "Others",
		);

$driver_array ['license_class'] = array("a", "b", "b1", "b2", "c", "d", "e", "e1", "e2", "f", "g", "h", "i", "m");

$maintenance_array ['repaired'] = array(
		"Accesorries",
		"Air Compressor System",
		"Body",
		"Brake System",
		"Chain Saw",
		"Chasis Electrical",
		"Cooling System",
		"Door",
		"Engine",
		"Engine Electrical",
		"Exhaust System",
		"Exterior",
		"Front Axle",
		"Fuel System",
		"Hydraulic Backhoe Attachment",
		"Hydraulic Shovel Bucket Attachment",
		"Hydraulic System",
		"Interior",
		"Lubricant System",
		"Rear Axle",
		"Seat",
		"Steering System",
		"Suspension System",
		"Transmission System",
		"Tyre",
		"Wheel",
	);
	
$maintenance_array ['category'] = array(
		"A001" => "Accesorries",
		"A002" => "Air Compressor System",
		"A003" => "Aircond System",
		"B001" => "Body",
		"B002" => "Brake System",
		"C001" => "Chain Saw",
		"C002" => "Chasis Electrical",
		"C003" => "Cooling System",
		"D001" => "Door",
		"E001" => "Engine",
		"E002" => "Engine Electrical",
		"E003" => "Exhaust System",
		"E004" => "Exterior",
		"F001" => "Front Axle",
		"F002" => "Fuel System",
		"G001" => "Grass Cutter",
		"H001" => "Hydraulic Backhoe Attachment",
		"H002" => "Hydraulic Shovel Bucket Attachment",
		"H003" => "Hydraulic System",
		"I001" => "Interior",
		"L001" => "Lubricant System",
		"R001" => "Rear Axle",
		"S001" => "Seat",
		"S002" => "Steering System",
		"S003" => "Suspension System",
		"T001" => "Transmission System",
		"T002" => "Tyre",
		"W001" => "Wheel",
	);
	
$maintenance_array ['faulty'] = array(
			"Aras Minyak Pelincir" => "L",
			"Aras Air Radiator" => "L",
			"Aras Bendalir Brek" => "L",
			"Aras Bendalir Stereng Kuasa" => "L",
			"Aras Bendalir Cekam" => "L",
			"Aras Air Bateri" => "L",
			"Aras Bahan Api" => "L",
			"Aras Bendalir Transmisi Auto" => "L",
			"Aras Gris Auto" => "L",
			"Penghawa Dingin" => "I",
			"Radio/CD" => "I",
			"Tahun Bateri" => "I",
			"Tahun Tayar Hadapan" => "D",
			"Tahun Tayar Belakang",
			"Peratus Bunga Tayar",
			"Tayar Simpanan",
			"Enjin",
			"Sistem Brek",
			"Sistem Penyejukan Enjin",
			"Sistem Stereng/Kawalan",
			"Sistem Gantungan",
			"Sistem Penghidup",
			"Sistem Mengecas",
			"Sistem Lampu & Hon",
			"Sistem Penggera",
			"Sistem Pengelap Cermin",
			"Tingkap Kuasa",
			"Tolok Lampu Penunjuk",
			"Perkakas (Tool Box)",
			"Bicu (Jack)",
			"Pemadam Api",
			"Ujian Jalan",
	);

$maintenance_array ['details_service'] = array(
			"oil_engine_flag" => "Engine Oil",//
			"oil_gear_flag" => "Gear Oil", //
			"engine_belt_flag" => "Power Stereng Belt", //
			"radiator_flag" => "Service Radiator",
			"oil_filter_flag" => "Oil Filter", //
			"oil_axle_flag" => "Oil Axle",//
			"strap_radiator_fan_flag" => "Radiator Fan Belt", //
			"lubrication_flag" => "Lubrication/Grease",//
			"spark_plug_flag" => "Spark Plug", //
			"oil_automatic_gear_flag" => "Auto Gear Oil",//
			"water_pump_flag" => "Water Pump", //
			"additional_grease_flag" => "Additional Grease",//
			"air_engine_flag" => "Engine Coolant",//
			"auto_air_filter_flag" => "Automatic Transmission Filter",//
			"timing_belt_flag" => "Timing Belt", //
			"wheel_rotation_flag" => "Wheel Rotation", //
			"air_filter_flag" => "Air Filter",//
			"engine_tuning_flag" => "Engine Tuning",//
			"air_cond_flag" => "Service Air Conditioning",//
			"wheel_alignment_flag" => "Wheel Alignment",//
			"fuel_filter_flag" => "Fuel Filter",//
			"ac_belt_flag" => "AC Belt",//
			"brake_service_flag" => "Service Brake",//
			"wheel_balancing_flag" => "Wheel Balancing",
	);

$maintenance_array ['mileage_service'] = array(
			"1" => "1 K",
			"5" => "5 K",
			//"10" => "10 K",
			"20" => "20 K",
			"30" => "30 K",
			"40" => "40 K",
			//"50" => "50 K",
			"60" => "60 K",
			"80" => "80 K",
			"90" => "90 K",
			"100" => "100 K",
			"120" => "120 K only",
	);

$maintenance_array ['status'] = array("REQUESTED", "REJECTED", "IN PROGRESS", "COMPLETED");
$maintenance_array ['quality'] = array("Good", "Satisfied", "Not Satisfied");
$maintenance_array ['quantity'] = array("Comply Specification", "Below Specification");
$maintenance_array ['cleanliness'] = array("Good", "Not Satisfied");
$maintenance_array ['action'] = array("No Action", "Redo Repair", "Immediate Repair", "Require Discussion");

$month_array = array(
		"01" => "JANUARI",
		"02" => "FEBRUARI",
		"03" => "MAC",
		"04" => "APRIL",
		"05" => "MEI",
		"06" => "JUN",
		"07" => "JULAI",
		"08" => "OGOS",
		"09" => "SEPTEMBER",
		"10" => "OKTOBER",
		"11" => "NOVEMBER",
		"12" => "DESEMBER",
	);
?>