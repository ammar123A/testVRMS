<?php
	define("FPDF_FONTPATH","../../font/");
	
	class PDF_MC_Table extends FPDF
	{
		function SetWidths($w) {
			//Set the array of column widths
			$this->widths = $w;
		}

		function SetAligns($a) 	{
			//Set the array of column alignments
			$this->aligns = $a;
		}

		function Row($data) {
			//Calculate the height of the row
			$nb = 0;
			for($i = 0; $i < count($data); $i++)
				$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
			$h = 5 * $nb;
			
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			
			//Draw the cells of the row
			
			for($i = 0; $i < count($data); $i++) {
				$w = $this->widths [$i];
				$a = isset($this->aligns [$i]) ? $this->aligns [$i] : 'L';
				
				//Save the current position
				$x = $this->GetX();
				$y = $this->GetY();
				
				//Draw the border
				$this->Rect($x, $y, $w, $h);
				
				//Print the text
				$this->MultiCell($w, 5, $data [$i], 0, $a);
				
				//Put the position to the right of the cell
				$this->SetXY($x + $w, $y);
			}
			//Go to the next line
			$this->Ln($h);
		}

		function CheckPageBreak($h) {
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY() + $h > $this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}

		function NbLines($w, $txt) {
			//Computes the number of lines a MultiCell of width w will take
			$cw = &$this->CurrentFont['cw'];
			
			if($w == 0)
				$w = $this->w - $this->rMargin - $this->x;
			$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
			$s = str_replace("\r", '', $txt);
			$nb = strlen($s);
			if($nb > 0 and $s [$nb-1] == "\n")
				$nb--;
			$sep = -1;
			$i = 0;
			$j = 0;
			$l = 0;
			$nl = 1;
			while($i < $nb) {
				$c = $s [$i];
				if($c == "\n") {
					$i++;
					$sep = -1;
					$j = $i;
					$l = 0;
					$nl++;
					continue;
				}
				
				if($c == ' ')
					$sep = $i;
				$l += $cw [$c];
				if($l > $wmax) {
					if($sep == -1) {
						if($i == $j)
							$i++;
					}
					else
						$i = $sep + 1;
					$sep = -1;
					$j = $i;
					$l = 0;
					$nl++;
				}
				else
					$i++;
			}
			
			return $nl;
		}
	}

	class myEmail
	{
		var $db;
		var $req_id;
		var $wr_id;
		var $wo_id;
		var $mail;
		var $array;
		var $fpdf;
		var $session;
		var $is_utm;
		
		function suratChartedApprovedPDF(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
						"wr" => array(
								"req_id" => "fl_wr.request_id",
								"wr_id" => "fl_wr.wr_id",
								"wo_id" => "fl_wr.wo_id",
								"status" => "fl_wr.status",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi')",
							),
						"booking" => array(
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi')",
								"act_cost" => "fl_wr.actual_cost",
							),
						"em" => array(
								"em_number" => "CASE fl_wr.requestor_type
												WHEN 'STAFF' THEN 
													(SELECT UPPER(em.em_number) FROM em WHERE em.em_id = fl_wr.requestor_no)
												WHEN 'STUDENT' THEN 
													(SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
													WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
												END CASE
											",
								"em_id" => "UPPER(fl_wr.requestor_no)",
										
							),
					);
			
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE request_id = " . $this->req_id . " AND wr_id = " . $this->wr_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t) {
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
				
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking_assembly_state = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking_assembly_state = $this->request_array ['state'][$booking ['assembly_state']];
				$booking_assembly = $booking ['assembly'] . " / " . $booking_assembly_state;
				
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking_destination_state = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking_destination_state = $this->request_array ['state'][$booking ['destination_state']];
				$booking_destination = $booking ['destination'] . " / " . $booking_destination_state;
			}
			
			$this->fpdf->AddPage();
			$this->fpdf->SetFont("Arial", "B", 12);
			$this->fpdf->Cell(190, 12, "VEHICLE RESERVATION MANAGEMENT SYSTEM", 0, 1);
			
			$this->fpdf->SetY($this->fpdf->GetY() + 5);
			
			$this->fpdf->SetFont("Arial", "B", 7);
			$this->fpdf->Cell(190, 5, "UNIT KENDERAAN", 0, 1);
			$this->fpdf->Cell(190, 5, "BAHAGIAN PERKHIDMATAN, PEJABAT HARTA BINA", 0, 1);
			if(strcmp($this->session ['FLEET']['site_id'], "UTM") == 0){
				$this->fpdf->Cell(190, 5, "UTM SKUDAI, JOHOR", 0, 1);
				$this->fpdf->Cell(190, 5, "TEL: 07-5530042, FAX: 07-5530218, HOTLINE: 019-7293154", 0, 1);
			}
			else {
				$this->fpdf->Cell(190, 5, "UTM IC, JOHOR", 0, 1);
				$this->fpdf->Cell(190, 5, "TEL: 03-26154218, FAX: 03-26910737, HOTLINE: 019-2813533", 0, 1);
			}
			
			//info
			$this->fpdf->SetXY(10, $this->fpdf->GetY() + 3);
			$this->fpdf->SetFont("Arial", "B", 8);
			$this->fpdf->SetFillColor(204, 204, 204);
			$this->fpdf->Cell(190, 5, "INFO", 1, 1, "L", true);
			
			$this->fpdf->SetFont("Arial", "", 7);
			
			$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
			$this->fpdf->Cell(50, 6, "REQ ID", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['req_id'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "WR ID", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['wr_id'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "STATUS", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['status'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DATE TIME REQUESTED", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['datetime_requested'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "NAME", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $em ['em_number'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "STAFF NO / NEW IC / PASSPORT NO", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $em ['em_id'], 0, 1);
			
			// booking
			$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
			$this->fpdf->SetFont("Arial", "B", 8);
			$this->fpdf->Cell(190, 5, "RESERVATION", 1, 1, "L", true);
			
			$this->fpdf->SetFont("Arial", "", 7);
			$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
			$this->fpdf->Cell(50, 6, "BOOKING TYPE", 0, 0);
			$this->fpdf->Cell(50, 6, ": " . $booking ['type'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "ASSEMBLY / PICK UP POINT", 0, 0);
			$this->fpdf->MultiCell(140, 6, ": " . $booking_assembly, 0);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DESTINATION", 0, 0);
			$this->fpdf->MultiCell(140, 6, ": " . $booking_destination, 0);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DATE TIME SEND/FETCH", 0, 0);
			$this->fpdf->Cell(50, 6, ": " .$booking ['datetime_pickup'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DATE TIME FETCH/SEND", 0, 0);
			$this->fpdf->Cell(50, 6, ": " . $booking ['datetime_fetch'], 0, 1);
			
			//passenger
			$_sql = "SELECT UPPER(name), UPPER(position), phone, UPPER(registration_no) FROM fl_passenger " .
					"WHERE fl_passenger.wo_id = " . $wr ['wo_id'] . " " .
					"AND fl_passenger.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			if($rs->RecordCount() > 0){
				while(!$rs->EOF){
					$array_passenger [] = array(
							trim($rs->fields [0]),
							trim($rs->fields [1]),
							trim($rs->fields [2]),
							trim($rs->fields [3]),
						);
					$rs->MoveNext();
				}
				
				// passenger
				$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
				$this->fpdf->SetFont("Arial", "B", 8);
				$this->fpdf->Cell(190, 5, "PASSENGER", 1, 1, "L", true);
				
				$this->fpdf->SetFont("Arial", "B", 6);
				$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
				$this->fpdf->Cell(60, 5, "NAME", 1, 0, "C", true);
				$this->fpdf->Cell(55, 5, "POSITION", 1, 0, "C", true);
				$this->fpdf->Cell(30, 5, "HANDPHONE", 1, 0, "C", true);
				$this->fpdf->Cell(25, 5, "REGISTRATION NO", 1, 1, "C", true);
				
				$this->fpdf->SetFont("Arial", "", 6);
				$row_width = array(60, 55, 30, 25);
				$row_align = array("L", "C", "C", "C");
				$this->fpdf->SetAligns($row_align);
				$this->fpdf->SetWidths($row_width);
				for($i = 0; $i < count($array_passenger); $i++){
					$this->fpdf->SetX(20);
					$this->fpdf->Row($array_passenger [$i]);
				}
			}
			
			// charted
			$_sql = "SELECT UPPER(fl_company.name), UPPER(fl_charted.contact_person), fl_charted.tel, fl_charted.mobile " .
					"FROM fl_charted " .
					"LEFT JOIN fl_company ON fl_company.com_id = fl_charted.com_id " .
					"WHERE wo_id = " . $wr ['wo_id'];
			$rs = $this->db->Execute($_sql);
			if($rs->RecordCount() > 0){
				$array_charted [] = array(
						trim($rs->fields [0]),
						trim($rs->fields [1]),
						trim($rs->fields [2]) . "/" . trim($rs->fields [3]),
					);
				
				$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
				$this->fpdf->SetFont("Arial", "B", 8);
				$this->fpdf->Cell(190, 5, "CHARTED", 1, 1, "L", true);
				
				$this->fpdf->SetFont("Arial", "B", 6);
				$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
				$this->fpdf->Cell(65, 5, "COMPANY NAME", 1, 0, "C", true);
				$this->fpdf->Cell(65, 5, "CONTACT PERSON", 1, 0, "C", true);
				$this->fpdf->Cell(40, 5, "TELEPHONE/HANDPHONE", 1, 1, "C", true);
				
				$this->fpdf->SetFont("Arial", "", 6);
				$row_width = array(65, 65, 40);
				$row_align = array("L", "C", "C", "C");
				$this->fpdf->SetAligns($row_align);
				$this->fpdf->SetWidths($row_width);
				for($i = 0; $i < count($array_charted); $i++){
					$this->fpdf->SetX(20);
					$this->fpdf->Row($array_charted [$i]);
				}
			}
			
			$pdfcontent = $this->fpdf->Output("surat.pdf", "S");
			return $pdfcontent;
		}
		
		function suratReservationApprovedPDF(){
			
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
						"wr" => array(
								"req_id" => "fl_wr.request_id",
								"wr_id" => "fl_wr.wr_id",
								"wo_id" => "fl_wr.wo_id",
								"status" => "fl_wr.status",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi')",
							),
						"booking" => array(
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi')",
							),
						"em" => array(
								"em_number" => "CASE fl_wr.requestor_type
												WHEN 'STAFF' THEN 
													(SELECT UPPER(em.em_number) FROM em WHERE em.em_id = fl_wr.requestor_no)
												WHEN 'STUDENT' THEN 
													(SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
													WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
												END CASE
											",
								"em_id" => "UPPER(fl_wr.requestor_no)",
										
							),
					);
			
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE request_id = " . $this->req_id . " AND wr_id = " . $this->wr_id;
					
			$rs = $this->db->Execute($_sql);
			$i = 0;
			
			
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t) {
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
				
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking_assembly_state = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking_assembly_state = $this->request_array ['state'][$booking ['assembly_state']];
				$booking_assembly = $booking ['assembly'] . " / " . $booking_assembly_state;
				
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking_destination_state = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking_destination_state = $this->request_array ['state'][$booking ['destination_state']];
				$booking_destination = $booking ['destination'] . " / " . $booking_destination_state;
			}
			
			$this->fpdf->AddPage();
			$this->fpdf->SetFont("Arial", "B", 12);
			$this->fpdf->Cell(190, 12, "VEHICLE RESERVATION MANAGEMENT SYSTEM", 0, 1);
			
			$this->fpdf->SetY($this->fpdf->GetY() + 5);
			
			$this->fpdf->SetFont("Arial", "B", 7);
			$this->fpdf->Cell(190, 5, "UNIT KENDERAAN", 0, 1);
			$this->fpdf->Cell(190, 5, "BAHAGIAN PERKHIDMATAN, PEJABAT HARTA BINA", 0, 1);
			if(strcmp($this->session ['FLEET']['site_id'], "UTM") == 0){
				$this->fpdf->Cell(190, 5, "UTM SKUDAI, JOHOR", 0, 1);
				$this->fpdf->Cell(190, 5, "TEL: 07-5530042, FAX: 07-5530218, HOTLINE: 019-7293154", 0, 1);
			}
			else {
				$this->fpdf->Cell(190, 5, "UTM IC, JOHOR", 0, 1);
				$this->fpdf->Cell(190, 5, "TEL: 03-26154218, FAX: 03-26910737, HOTLINE: 019-2813533", 0, 1);
			}
			
			//info
			$this->fpdf->SetXY(10, $this->fpdf->GetY() + 3);
			$this->fpdf->SetFont("Arial", "B", 8);
			$this->fpdf->SetFillColor(204, 204, 204);
			$this->fpdf->Cell(190, 5, "INFO", 1, 1, "L", true);
			
			$this->fpdf->SetFont("Arial", "", 7);
			
			$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
			$this->fpdf->Cell(50, 6, "REQ ID", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['req_id'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "WR ID", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['wr_id'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "STATUS", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['status'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DATE TIME REQUESTED", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $wr ['datetime_requested'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "NAME", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $em ['em_number'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "STAFF NO / NEW IC / PASSPORT NO", 0, 0);
			$this->fpdf->Cell(140, 6, ": " . $em ['em_id'], 0, 1);
			
			// booking
			$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
			$this->fpdf->SetFont("Arial", "B", 8);
			$this->fpdf->Cell(190, 5, "RESERVATION", 1, 1, "L", true);
			
			$this->fpdf->SetFont("Arial", "", 7);
			$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
			$this->fpdf->Cell(50, 6, "BOOKING TYPE", 0, 0);
			$this->fpdf->Cell(50, 6, ": " . $booking ['type'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "ASSEMBLY / PICK UP POINT", 0, 0);
			$this->fpdf->MultiCell(140, 6, ": " . $booking_assembly, 0);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DESTINATION", 0, 0);
			$this->fpdf->MultiCell(140, 6, ": " . $booking_destination, 0);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DATE TIME SEND/FETCH", 0, 0);
			$this->fpdf->Cell(50, 6, ": " .$booking ['datetime_pickup'], 0, 1);
			
			$this->fpdf->SetX(20);
			$this->fpdf->Cell(50, 6, "DATE TIME FETCH/SEND", 0, 0);
			$this->fpdf->Cell(50, 6, ": " . $booking ['datetime_fetch'], 0, 1);
			
			
			// vehicle
			$_sql = "SELECT fl_vehicle.vehicle_type, UPPER(fl_vehicle.registration_no), fl_vehicle.manufacture_type, " .
					"UPPER(fl_vehicle.model), UPPER(fl_vehicle.colour) FROM fl_wrv ".
					"INNER JOIN fl_vehicle ON fl_vehicle.registration_no = fl_wrv.registration_no " .
					"WHERE fl_wrv.wo_id = " . $wr ['wo_id'];
					//echo "HERE GOOD UNTIL PDF vehicle array"; 
				
			$rs = $this->db->Execute($_sql);
				
			if($rs->RecordCount() > 0){
				while(!$rs->EOF){
					$array_data [] = array(
							trim($rs->fields [0]),
							trim($rs->fields [1]),
							trim($rs->fields [2]) . " / " . trim($rs->fields [3]),
							trim($rs->fields [4]),
						);
					$rs->MoveNext();
				}
			
				//vehicle
				$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
				$this->fpdf->SetFont("Arial", "B", 8);
				$this->fpdf->Cell(190, 5, "VEHICLE ASSIGNED", 1, 1, "L", true);
				
				$this->fpdf->SetFont("Arial", "B", 6);
				$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
				$this->fpdf->Cell(20, 5, "TYPE", 1, 0, "C", true);
				$this->fpdf->Cell(30, 5, "REGISTRATION NO", 1, 0, "C", true);
				$this->fpdf->Cell(65, 5, "MODEL", 1, 0, "C", true);
				$this->fpdf->Cell(55, 5, "COLOR", 1, 1, "C", true);
				
				$this->fpdf->SetFont("Arial", "", 6);
				$row_width = array(20, 30, 65, 55);
				$row_align = array("C", "C", "C", "C");
				$this->fpdf->SetAligns($row_align);
				$this->fpdf->SetWidths($row_width);
				for($i = 0; $i < count($array_data); $i++){
					$this->fpdf->SetX(20);
					$this->fpdf->Row($array_data [$i]);
				}
			}
			
			// driver
			$_sql = "SELECT UPPER(em.em_number), fl_driver.phone, fl_wrcf.registration_no " .
					"FROM fl_wrcf " .
					"LEFT JOIN em ON em.em_id = fl_wrcf.em_id " .
					"LEFT JOIN fl_driver ON fl_driver.em_id = fl_wrcf.em_id " .
					"WHERE fl_wrcf.wo_id = " . $wr ['wo_id'];
			$rs = $this->db->Execute($_sql);
			if($rs->RecordCount() > 0){
				while(!$rs->EOF){
					$array_driver [] = array(
							trim($rs->fields [0]),
							trim($rs->fields [1]),
							trim($rs->fields [2]),
						);
					$rs->MoveNext();
				}
				
				// passenger
				$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
				$this->fpdf->SetFont("Arial", "B", 8);
				$this->fpdf->Cell(190, 5, "DRIVER ASSIGNED", 1, 1, "L", true);
				
				$this->fpdf->SetFont("Arial", "B", 6);
				$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
				$this->fpdf->Cell(95, 5, "NAME", 1, 0, "C", true);
				$this->fpdf->Cell(40, 5, "HANDPHONE", 1, 0, "C", true);
				$this->fpdf->Cell(35, 5, "REGISTRATION NO", 1, 1, "C", true);
				
				$this->fpdf->SetFont("Arial", "", 6);
				$row_width = array(95, 40, 35);
				$row_align = array("L", "C", "C", "C");
				$this->fpdf->SetAligns($row_align);
				$this->fpdf->SetWidths($row_width);
				for($i = 0; $i < count($array_driver); $i++){
					$this->fpdf->SetX(20);
					$this->fpdf->Row($array_driver [$i]);
				}
			}
			
			//passenger
			$_sql = "SELECT UPPER(name), UPPER(position), phone, UPPER(registration_no) FROM fl_passenger " .
					"WHERE fl_passenger.wo_id = " . $wr ['wo_id'] . " " .
					"AND fl_passenger.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			if($rs->RecordCount() > 0){
				while(!$rs->EOF){
					$array_passenger [] = array(
							trim($rs->fields [0]),
							trim($rs->fields [1]),
							trim($rs->fields [2]),
							trim($rs->fields [3]),
						);
					$rs->MoveNext();
				}
				
				// passenger
				$this->fpdf->SetXY(10, $this->fpdf->GetY() + 5);
				$this->fpdf->SetFont("Arial", "B", 8);
				$this->fpdf->Cell(190, 5, "PASSENGER", 1, 1, "L", true);
				
				$this->fpdf->SetFont("Arial", "B", 6);
				$this->fpdf->SetXY(20, $this->fpdf->GetY() + 2);
				$this->fpdf->Cell(60, 5, "NAME", 1, 0, "C", true);
				$this->fpdf->Cell(55, 5, "POSITION", 1, 0, "C", true);
				$this->fpdf->Cell(30, 5, "HANDPHONE", 1, 0, "C", true);
				$this->fpdf->Cell(25, 5, "REGISTRATION NO", 1, 1, "C", true);
				
				$this->fpdf->SetFont("Arial", "", 6);
				$row_width = array(60, 55, 30, 25);
				$row_align = array("L", "C", "C", "C");
				$this->fpdf->SetAligns($row_align);
				$this->fpdf->SetWidths($row_width);
				for($i = 0; $i < count($array_passenger); $i++){
					$this->fpdf->SetX(20);
					$this->fpdf->Row($array_passenger [$i]);
				}
			}
			
			$pdfcontent = $this->fpdf->Output("surat.pdf", "S");

			return $pdfcontent;
		}
		
		function suratMaklumanPDF(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_wr.status",
								"req_id" => "fl_wr.request_id",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_wr.requestor_id",
								"em_id" => "fl_wr.requestor_no",
								"em_number" => "
										CASE fl_wr.requestor_type
											WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
												WHERE em.em_id = fl_wr.requestor_no) 
											WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
												WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
										END CASE
									",
								"dv_name" => "
										CASE fl_wr.requestor_type
											WHEN 'STAFF' THEN (
													SELECT UPPER(dv.name) FROM dv, em 
													WHERE em.dv_id = dv.dv_id 
													AND em.em_id = fl_wr.requestor_no
												) 
											WHEN 'STUDENT' THEN (
													SELECT UPPER(dv.name) FROM dv, $vw_ifamms_pelajar_aktif 
													WHERE vw_ifamms_pelajar_aktif.kod_fakulti = dv.dv_id 
													AND vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no
												)
										END CASE
									",
								"phone" => "fl_wr.requestor_phone",
								"email" => "fl_wr.requestor_email",
								"datetime_rejected" => "TO_CHAR(fl_wr.datetime_rejected, 'dd-mm-yyyy hh24:mi:ss')",
								"rejected_by" => "(
										SELECT UPPER(em.em_number) FROM afm_users, em WHERE afm_users.email = em.email 
										AND afm_users.user_name = fl_wr.rejected_by
									)",
								"remark" => "UPPER(fl_wr.remark)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_wr.booking_site_id
									)",
								"program" => "UPPER(fl_wr.booking_program)",
								"purpose" => "fl_wr.booking_purpose",
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"date_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy')",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_wr.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_wr.supported_ref_no)",
							),
				);
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$i = 0;
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE fl_wr.request_id = " . $this->req_id . " AND fl_wr.wr_id = " . $this->wr_id;
			$rs = $this->db->Execute($_sql);
			foreach($table_array as $mf => $array){
				foreach($array as $f => $v){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$_sql = "SELECT UPPER(name) FROM fl_passenger WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$arr [] = array(trim($rs->fields [0]));
				$rs->MoveNext();
			}
			
			$this->fpdf->AddPage();
			$this->fpdf->SetFont("Arial", "B", 12);
			$this->fpdf->Cell(190, 12, "VEHICLE RESERVATION MANAGEMENT SYSTEM", 0, 1);
			
			$this->fpdf->SetY($this->fpdf->GetY() + 5);
			
			$this->fpdf->SetFont("Arial", "B", 7);
			$this->fpdf->Cell(190, 5, "UNIT KENDERAAN", 0, 1);
			$this->fpdf->Cell(190, 5, "BAHAGIAN PERKHIDMATAN, PEJABAT HARTA BINA", 0, 1);
			if(strcmp($this->session ['FLEET']['site_id'], "UTM") == 0){
				$this->fpdf->Cell(190, 5, "UTM SKUDAI, JOHOR", 0, 1);
				$this->fpdf->Cell(190, 5, "TEL: 07-5530042, FAX: 07-5530218, HOTLINE: 019-7293154", 0, 1);
			}
			else {
				$this->fpdf->Cell(190, 5, "UTM IC, JOHOR", 0, 1);
				$this->fpdf->Cell(190, 5, "TEL: 03-26154218, FAX: 03-26910737, HOTLINE: 019-2813533", 0, 1);
			}
			
			$this->fpdf->SetY($this->fpdf->GetY() + 5);
			
			$this->fpdf->SetFont("Arial", "UB", 7);
			$this->fpdf->Cell(190, 5, "TO:", 0, 1);
			
			$this->fpdf->SetFont("Arial", "B", 7);
			$this->fpdf->Cell(190, 5, $request ['em_number'], 0, 1);
			$this->fpdf->Cell(190, 5, $request ['em_id'], 0, 1);
			$this->fpdf->Cell(190, 5, $request ['dv_name'], 0, 1);
			
			$this->fpdf->SetY($this->fpdf->GetY() + 5);
			
			$this->fpdf->SetFont("Arial", "UB", 7);
			$this->fpdf->Cell(190, 5, "RESERVATION:", 0, 1);
			
			$this->fpdf->SetFont("Arial", "B", 7);
			$this->fpdf->Cell(190, 5, "STATUS: " . $request ['status'], 0, 1);
			$this->fpdf->Cell(190, 5, "REQ ID: " . $request ['req_id'], 0, 1);
			$this->fpdf->MultiCell(190, 5, "DATE TIME REQUESTED: " . $request ['datetime_requested']);
			$this->fpdf->MultiCell(190, 5, "PROGRAM: " . $booking ['program']);
			$this->fpdf->MultiCell(190, 5, "DATE: " . $booking ['date_pickup']);
			$this->fpdf->MultiCell(190, 5, "DESTINATION: " . $booking ['destination'] . "/" . $booking ['destination_state']);
			$this->fpdf->MultiCell(190, 5, "REMARK: " . $request ['remark']);
			
			if(count($arr) > 0){
				$this->fpdf->SetY($this->fpdf->GetY() + 5);
				
				$this->fpdf->SetFont("Arial", "UB", 7);
				$this->fpdf->Cell(190, 5, "PASSENGER:", 0, 1);
			
				$this->fpdf->SetFont("Arial", "B", 7);
				for($i = 0; $i < count($arr); $i++){
					$this->fpdf->Cell(10, 5, ($i + 1), 0, 0);
					$this->fpdf->Cell(180, 5, $arr [$i][0], 0, 1);
				}
			}
			
			$this->fpdf->SetY($this->fpdf->GetY() + 10);
			$this->fpdf->SetFont("Arial", "I", 7);
			$this->fpdf->Cell(190, 5, "THIS IS A COMPUTER GENERATED LETTER AND NO SIGNATURE IS REQUIRED", 0, 1, "C");
			
			$pdfcontent = $this->fpdf->Output("surat.pdf", "S");
			return $pdfcontent;
		}
		
		function HTML_Reservation_Requested(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_request.status",
								"req_id" => "fl_request.request_id",
								"datetime_requested" => "TO_CHAR(fl_request.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_request.requestor_id",
								"em_id" => "fl_request.requestor_no",
								"em_number" => "CASE fl_request.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_request.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_request.requestor_no)
												END CASE
											",
								"phone" => "fl_request.requestor_phone",
								"email" => "fl_request.requestor_email",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_request.booking_site_id
									)",
								"program" => "UPPER(fl_request.booking_program)",
								"purpose" => "fl_request.booking_purpose",
								"type" => "fl_request.booking_type",
								"assembly" => "UPPER(fl_request.booking_assembly)",
								"assembly_state" => "fl_request.booking_assembly_state",
								"destination" => "UPPER(fl_request.booking_destination)",
								"destination_state" => "fl_request.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_request.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_request.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_request.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_request.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_request.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_request.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_request.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_purpose) END
									",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_request " .
					"WHERE fl_request.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		// 16-Jan Start
		function HTML_Reservation_Requestedtoptj(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_request.status",
								"req_id" => "fl_request.request_id",
								"datetime_requested" => "TO_CHAR(fl_request.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_request.requestor_id",
								"em_id" => "fl_request.requestor_no",
								"em_number" => "CASE fl_request.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_request.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_request.requestor_no)
												END CASE
											",
								"phone" => "fl_request.requestor_phone",
								"email" => "fl_request.requestor_email",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_request.booking_site_id
									)",
								"program" => "UPPER(fl_request.booking_program)",
								"purpose" => "fl_request.booking_purpose",
								"type" => "fl_request.booking_type",
								"assembly" => "UPPER(fl_request.booking_assembly)",
								"assembly_state" => "fl_request.booking_assembly_state",
								"destination" => "UPPER(fl_request.booking_destination)",
								"destination_state" => "fl_request.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_request.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_request.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_request.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_request.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_request.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_request.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_request.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_purpose) END
									",
									"ptj_email" => "fl_request.ptj_email",
									"ptj_cost" => "fl_request.ptj_cost",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_request " .
					"WHERE fl_request.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info Requestor</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Estimated Cost Trip:</td>
							<td style=\"$tdRight\">" . $booking ['ptj_cost'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			$html .= "<br>";
			$html .= "<br>";
			$html .= "Please login to http://vrms.utm.my to view detail of reservation.";
			
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($booking ['ptj_email']);
			$m->Subject("VRMS :: FROM REQUESTOR[STATUS - " . $request ['status'] . "]");
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		// 18-Jan End
function HTML_Reservation_Recommend(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_request.status",
								"req_id" => "fl_request.request_id",
								"datetime_requested" => "TO_CHAR(fl_request.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"datetime_recommend" => "TO_CHAR(fl_request.datetime_recommend, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_request.requestor_id",
								"em_id" => "fl_request.requestor_no",
								"em_number" => "CASE fl_request.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_request.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_request.requestor_no)
												END CASE
											",
								"phone" => "fl_request.requestor_phone",
								"email" => "fl_request.requestor_email",
								"ptj_off" => "(SELECT UPPER(em.em_number) FROM em WHERE em.email = fl_request.ptj_email)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_request.booking_site_id
									)",
								"program" => "UPPER(fl_request.booking_program)",
								"purpose" => "fl_request.booking_purpose",
								"type" => "fl_request.booking_type",
								"assembly" => "UPPER(fl_request.booking_assembly)",
								"assembly_state" => "fl_request.booking_assembly_state",
								"destination" => "UPPER(fl_request.booking_destination)",
								"destination_state" => "fl_request.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_request.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_request.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_request.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_request.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_request.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_request.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_request.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_purpose) END
									",
								"ptj_cost" => "fl_request.ptj_cost",
								
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_request " .
					"WHERE fl_request.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Recommend:</td>
							<td style=\"$tdRight\">" . $request ['datetime_recommend'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Recommended By:</td>
							<td style=\"$tdRight\">" . $request ['ptj_off'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Estimated Cost Trip:</td>
							<td style=\"$tdRight\">" . $booking ['ptj_cost'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		
		
		
	function HTML_Reservation_Notrecommend(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_request.status",
								"req_id" => "fl_request.request_id",
								"datetime_requested" => "TO_CHAR(fl_request.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"datetime_recommend" => "TO_CHAR(fl_request.datetime_recommend, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_request.requestor_id",
								"em_id" => "fl_request.requestor_no",
								"remark" => "UPPER(fl_request.remark)",
								"em_number" => "CASE fl_request.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_request.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_request.requestor_no)
												END CASE
											",
								"phone" => "fl_request.requestor_phone",
								"email" => "fl_request.requestor_email",
								"ptj_off" => "(SELECT UPPER(em.em_number) FROM em WHERE em.email = fl_request.ptj_email)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_request.booking_site_id
									)",
								"program" => "UPPER(fl_request.booking_program)",
								"purpose" => "fl_request.booking_purpose",
								"type" => "fl_request.booking_type",
								"assembly" => "UPPER(fl_request.booking_assembly)",
								"assembly_state" => "fl_request.booking_assembly_state",
								"destination" => "UPPER(fl_request.booking_destination)",
								"destination_state" => "fl_request.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_request.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_request.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_request.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_request.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_request.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_request.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_request.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_purpose) END
									",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_request " .
					"WHERE fl_request.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time  Not Recommend:</td>
							<td style=\"$tdRight\">" . $request ['datetime_recommend'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Not Recommended  By:</td>
							<td style=\"$tdRight\">" . $request ['ptj_off'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Remark:</td>
							<td style=\"$tdRight\">" . $request ['remark'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		function HTML_Reservation_Reject_Recommended(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_request.status",
								"req_id" => "fl_request.request_id",
								"datetime_requested" => "TO_CHAR(fl_request.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"datetime_recommend" => "TO_CHAR(fl_request.datetime_recommend, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_request.requestor_id",
								"em_id" => "fl_request.requestor_no",
								"remark" => "UPPER(fl_request.remark)",
								"em_number" => "CASE fl_request.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_request.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_request.requestor_no)
												END CASE
											",
								"phone" => "fl_request.requestor_phone",
								"email" => "fl_request.requestor_email",
								"ptj_off" => "(SELECT UPPER(em.em_number) FROM em WHERE em.email = fl_request.ptj_email)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_request.booking_site_id
									)",
								"program" => "UPPER(fl_request.booking_program)",
								"purpose" => "fl_request.booking_purpose",
								"type" => "fl_request.booking_type",
								"assembly" => "UPPER(fl_request.booking_assembly)",
								"assembly_state" => "fl_request.booking_assembly_state",
								"destination" => "UPPER(fl_request.booking_destination)",
								"destination_state" => "fl_request.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_request.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_rejected" => "TO_CHAR(fl_request.datetime_rejected, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_request.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_request.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_request.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_request.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_request.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_request.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_request.booking_vehicle_purpose) END
									",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_request " .
					"WHERE fl_request.request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time  Rejected:</td>
							<td style=\"$tdRight\">" . $request ['datetime_recommend'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Remark:</td>
							<td style=\"$tdRight\">" . $request ['remark'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		function HTML_Reservation_Rejected(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_wr.status",
								"req_id" => "fl_wr.request_id",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_wr.requestor_id",
								"em_id" => "fl_wr.requestor_no",
								"em_number" => "CASE fl_wr.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_wr.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
												END CASE
											",
								"phone" => "fl_wr.requestor_phone",
								"email" => "fl_wr.requestor_email",
								"datetime_rejected" => "TO_CHAR(fl_wr.datetime_rejected, 'dd-mm-yyyy hh24:mi:ss')",
								"rejected_by" => "(
										SELECT UPPER(em.em_number) FROM afm_users, em WHERE afm_users.email = em.email 
										AND afm_users.user_name = fl_wr.rejected_by
									)",
								"remark" => "UPPER(fl_wr.remark)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_wr.booking_site_id
									)",
								"program" => "UPPER(fl_wr.booking_program)",
								"purpose" => "fl_wr.booking_purpose",
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_wr.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_wr.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_wr.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_wr.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_wr.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_purpose) END
									",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE fl_wr.request_id = " . $this->req_id . "AND fl_wr.wr_id = " . $this->wr_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Status</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">
								<font style=\"color:#ff0000; font-weight:bold;\">" . $request ['status'] . "</font>
							</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Rejected:</td>
							<td style=\"$tdRight\">" . $request ['datetime_rejected'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Rejected By:</td>
							<td style=\"$tdRight\">" . $request ['rejected_by'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Remark:</td>
							<td style=\"$tdRight\">" . $request ['remark'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			
			$pdfcontent = $this->suratMaklumanPDF();
			$m->Attach [] = array(
					"content" => $pdfcontent,
					"type" => "application/pdf",
					"name" => "surat.pdf",
					"encoding" => "base64",
					"id" => MIME::unique(),
				);
			
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		
		function HTML_Reservation_InProgress(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_wr.status",
								"req_id" => "fl_wr.request_id",
								"wr_id" => "fl_wr.wr_id",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"datetime_responded" => "TO_CHAR(fl_wr.datetime_responded, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_wr.requestor_id",
								"em_id" => "fl_wr.requestor_no",
								"em_number" => "CASE fl_wr.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_wr.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
												END CASE
											",
								"phone" => "fl_wr.requestor_phone",
								"email" => "fl_wr.requestor_email",
								"datetime_rejected" => "TO_CHAR(fl_wr.datetime_rejected, 'dd-mm-yyyy hh24:mi:ss')",
								"rejected_by" => "(
										SELECT UPPER(em.em_number) FROM afm_users, em WHERE afm_users.email = em.email 
										AND afm_users.user_name = fl_wr.rejected_by
									)",
								"remark" => "UPPER(fl_wr.remark)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_wr.booking_site_id
									)",
								"program" => "UPPER(fl_wr.booking_program)",
								"purpose" => "fl_wr.booking_purpose",
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_wr.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_wr.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_wr.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_wr.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_wr.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_purpose) END
									",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE fl_wr.request_id = " . $this->req_id . "AND fl_wr.wr_id = " . $this->wr_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(trim($rs->fields [0]), trim($rs->fields [1]), trim($rs->fields [2]));
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Status</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">WR ID:</td>
							<td style=\"$tdRight\">" . $request ['wr_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Responded:</td>
							<td style=\"$tdRight\">" . $request ['datetime_responded'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
		
		function HTML_Reservation_Approved(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_wr.status",
								"req_id" => "fl_wr.request_id",
								"wr_id" => "fl_wr.wr_id",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"datetime_responded" => "TO_CHAR(fl_wr.datetime_responded, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_wr.requestor_id",
								"em_id" => "fl_wr.requestor_no",
								"em_number" => "CASE fl_wr.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_wr.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
												END CASE
											",
								"phone" => "fl_wr.requestor_phone",
								"email" => "fl_wr.requestor_email",
								"datetime_rejected" => "TO_CHAR(fl_wr.datetime_rejected, 'dd-mm-yyyy hh24:mi:ss')",
								"rejected_by" => "(
										SELECT UPPER(em.em_number) FROM afm_users, em WHERE afm_users.email = em.email 
										AND afm_users.user_name = fl_wr.rejected_by
									)",
								"remark" => "UPPER(fl_wr.remark)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_wr.booking_site_id
									)",
								"program" => "UPPER(fl_wr.booking_program)",
								"purpose" => "fl_wr.booking_purpose",
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"act_cost" => "fl_wr.actual_cost",
								"datetime_fetch" => "
										CASE WHEN fl_wr.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_wr.supported_ref_no)",
								"vehicle" => "
										CASE WHEN fl_wr.booking_vehicle_type IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_type) END
									",
								"model" => "
										CASE WHEN fl_wr.booking_vehicle_model IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_model) END
									",
								"model_purpose" => "
										CASE WHEN fl_wr.booking_vehicle_purpose IS NULL THEN 'N/A' 
										ELSE UPPER(fl_wr.booking_vehicle_purpose) END
									",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE fl_wr.request_id = " . $this->req_id . " AND fl_wr.wr_id = " . $this->wr_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT fl_vehicle.vehicle_type, fl_wrv.registration_no, UPPER(fl_vehicle.model), " .
					"UPPER(fl_vehicle.colour) FROM fl_wrv " .
					"INNER JOIN fl_vehicle ON fl_vehicle.registration_no = fl_wrv.registration_no " .
					"WHERE fl_wrv.wo_id = " . $this->wo_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$vehicle_array [] = array(
						trim($rs->fields [0]),
						trim($rs->fields [1]),
						trim($rs->fields [2]),
						trim($rs->fields [3]),
					);
				$rs->MoveNext();
			}
			
			$_sql = "SELECT UPPER(em.em_number), fl_driver.phone, fl_wrcf.registration_no " .
					"FROM fl_wrcf " .
					"LEFT JOIN em ON em.em_id = fl_wrcf.em_id " .
					"LEFT JOIN fl_driver ON fl_driver.em_id = fl_wrcf.em_id " .
					"WHERE fl_wrcf.wo_id = " . $this->wo_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$driver_array [] = array(
						trim($rs->fields [0]),
						trim($rs->fields [1]),
						trim($rs->fields [2]),
					);
				$rs->MoveNext();
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(
						trim($rs->fields [0]),
						trim($rs->fields [1]),
						trim($rs->fields [2]),
					);
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			$html .= "<div style=\"border:1px solid #000000;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Vehicle & Driver Assigned</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($vehicle_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Type</td>
								<td style=\"$tdHead\" align=\"center\">REGISTRATION NO</td>
								<td style=\"$tdHead\" align=\"center\">MODEL</td>
								<td style=\"$tdHead\" align=\"center\">COLOR</td>
							</tr>
						";
				for($i = 0; $i < count($vehicle_array); $i++){
					$type = $vehicle_array [$i][0];
					$reg_no = $vehicle_array [$i][1];
					$model = $vehicle_array [$i][2];
					$color = $vehicle_array [$i][3];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $type . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $reg_no . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $model . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $color . "</td>
								</tr>
							";
				}
			}
			$html .= "</table>";
			$html .= "<br/>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($driver_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">NAME</td>
								<td style=\"$tdHead\" align=\"center\">HANDPHONE</td>
								<td style=\"$tdHead\" align=\"center\">REGISTRATION</td>
							</tr>
						";
				for($i = 0; $i < count($driver_array); $i++){
					$name = $driver_array [$i][0];
					$phone = $driver_array [$i][1];
					$reg_no = $driver_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $reg_no . "</td>
								</tr>
							";
				}
			}
			$html .= "</table>";
			$html .= "</div>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Status</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">WR ID:</td>
							<td style=\"$tdRight\">" . $request ['wr_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Responded:</td>
							<td style=\"$tdRight\">" . $request ['datetime_responded'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Vehicle Request:</td>
							<td style=\"$tdRight\">" . $booking ['vehicle'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Model Request:</td>
							<td style=\"$tdRight\">" . $booking ['model'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Special Request (Remark):</td>
							<td style=\"$tdRight\">" . $booking ['model_purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Actual Cost:</td>
							<td style=\"$tdRight\">" . $booking ['act_cost'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . "]");
			
			$pdfcontent = $this->suratReservationApprovedPDF();
			
			$m->Attach [] = array(
					"content" => $pdfcontent,
					"type" => "application/pdf",
					"name" => "surat.pdf",
					"encoding" => "base64",
					"id" => MIME::unique(),
				);
				
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
			
			
		}
		
		function HTML_Reservation_Charted(){
			$vw_ifamms_pelajar_aktif = ($this->is_utm ? "viewtable." : "") . "vw_ifamms_pelajar_aktif";
			
			$table_array = array(
					"request" => array(
								"status" => "fl_wr.status",
								"req_id" => "fl_wr.request_id",
								"datetime_requested" => "TO_CHAR(fl_wr.datetime_requested, 'dd-mm-yyyy hh24:mi:ss')",
								"user_name" => "fl_wr.requestor_id",
								"em_id" => "fl_wr.requestor_no",
								"em_number" => "CASE fl_wr.requestor_type
													WHEN 'STAFF' THEN (SELECT UPPER(em.em_number) FROM em 
														WHERE em.em_id = fl_wr.requestor_no) 
													WHEN 'STUDENT' THEN (SELECT UPPER(vw_ifamms_pelajar_aktif.nama_pelajar) FROM $vw_ifamms_pelajar_aktif 
														WHERE vw_ifamms_pelajar_aktif.no_kp_pelajar = fl_wr.requestor_no)
												END CASE
											",
								"phone" => "fl_wr.requestor_phone",
								"email" => "fl_wr.requestor_email",
								"datetime_rejected" => "TO_CHAR(fl_wr.datetime_rejected, 'dd-mm-yyyy hh24:mi:ss')",
								"rejected_by" => "(
										SELECT UPPER(em.em_number) FROM afm_users, em WHERE afm_users.email = em.email 
										AND afm_users.user_name = fl_wr.rejected_by
									)",
								"remark" => "UPPER(fl_wr.remark)",
							),
						"booking" => array(
								"site_id" => "(
										SELECT UPPER(site.name) FROM site WHERE site.site_id = fl_wr.booking_site_id
									)",
								"program" => "UPPER(fl_wr.booking_program)",
								"purpose" => "fl_wr.booking_purpose",
								"type" => "fl_wr.booking_type",
								"assembly" => "UPPER(fl_wr.booking_assembly)",
								"assembly_state" => "fl_wr.booking_assembly_state",
								"destination" => "UPPER(fl_wr.booking_destination)",
								"destination_state" => "fl_wr.booking_destination_state",
								"datetime_pickup" => "TO_CHAR(fl_wr.datetime_pickup, 'dd-mm-yyyy hh24:mi')",
								"datetime_fetch" => "
										CASE WHEN fl_wr.datetime_fetch IS NULL THEN 'N/A' 
										ELSE TO_CHAR(fl_wr.datetime_fetch, 'dd-mm-yyyy hh24:mi') END",
								"ref_no" => "UPPER(fl_wr.supported_ref_no)",
							),
				);
				
			$tables = array();
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t)
					$tables [] = $t;
			}
			
			$_sql = "SELECT " . implode(", ", $tables) . " FROM fl_wr " .
					"WHERE fl_wr.request_id = " . $this->req_id . "AND fl_wr.wr_id = " . $this->wr_id;
			$rs = $this->db->Execute($_sql);
			$i = 0;
			foreach($table_array as $mf => $array){
				foreach($array as $f => $t){
					${$mf} [$f] = trim($rs->fields [$i]);
					$i++;
				}
			}
			
			$_sql = "SELECT UPPER(fl_company.name), UPPER(fl_charted.contact_person), fl_charted.tel, fl_charted.mobile " .
					"FROM fl_charted " .
					"LEFT JOIN fl_company ON fl_company.com_id = fl_charted.com_id " .
					"WHERE wo_id = " . $this->wo_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$charted ['company'] = trim($rs->fields [0]);
				$charted ['contact'] = trim($rs->fields [1]);
				$charted ['tel'] = trim($rs->fields [2]);
				$charted ['mobile'] = trim($rs->fields [3]);
				$rs->MoveNext();
			}
			
			$_sql = "SELECT UPPER(name), UPPER(position), phone FROM fl_passenger " .
					"WHERE request_id = " . $this->req_id;
			$rs = $this->db->Execute($_sql);
			while(!$rs->EOF){
				$passenger_array [] = array(
						trim($rs->fields [0]),
						trim($rs->fields [1]),
						trim($rs->fields [2]),
					);
				$rs->MoveNext();
			}
			
			if($booking ['purpose'])
				$booking ['purpose'] = $this->request_array ['purpose'][$booking ['purpose']];
			
			if($booking ['assembly_state']) {
				if(substr($booking ['assembly_state'], 0, 1) == "A")
					$booking ['assembly_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['assembly_state']];
				else
					$booking ['assembly_state'] = $this->request_array ['state'][$booking ['assembly_state']];
			}
			
			if($booking ['destination_state']) {
				if(substr($booking ['destination_state'], 0, 1) == "A")
					$booking ['destination_state'] = "AIRPORT " . $this->request_array ['state'][$booking ['destination_state']];
				else
					$booking ['destination_state'] = $this->request_array ['state'][$booking ['destination_state']];
			}
			
			$tdLeft = "text-align: right; width: 25%; font-style: italic; font-weight: bold;";
			$tdRight = "";
			
			$html = "<h1>Vehicle Reservation Management System :: VRMS</h1>";
			
			// Charted Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Charted</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Company:</td>
							<td style=\"$tdRight\">" . $charted ['company'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Contact Person:</td>
							<td style=\"$tdRight\">" . $charted ['contact'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Tel/Handphone:</td>
							<td style=\"$tdRight\">" . $charted ['tel'] . "/" . $charted ['mobile'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Requestor Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Status</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Status:</td>
							<td style=\"$tdRight\">" . $request ['status'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">REQ ID:</td>
							<td style=\"$tdRight\">" . $request ['req_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Requested:</td>
							<td style=\"$tdRight\">" . $request ['datetime_requested'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Info</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Requestor ID:</td>
							<td style=\"$tdRight\">" . $request ['user_name'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Name:</td>
							<td style=\"$tdRight\">" . $request ['em_number'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Staff No/New IC/Passport No:</td>
							<td style=\"$tdRight\">" . $request ['em_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Handphone:</td>
							<td style=\"$tdRight\">" . $request ['phone'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Booking Details
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Booking Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			$html .= "
						<tr>
							<td style=\"$tdLeft\">Attention To:</td>
							<td style=\"$tdRight\">PHB - " . $booking ['site_id'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Program:</td>
							<td style=\"$tdRight\">" . $booking ['program'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Purpose:</td>
							<td style=\"$tdRight\">" . $booking ['purpose'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Booking Type:</td>
							<td style=\"$tdRight\">" . $booking ['type'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Assembly/Pick up Point:</td>
							<td style=\"$tdRight\">" . $booking ['assembly'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['assembly_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Destination:</td>
							<td style=\"$tdRight\">" . $booking ['destination'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">State/Campus:</td>
							<td style=\"$tdRight\">" . $booking ['destination_state'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Send/Fetch:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_pickup'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Date Time Fetch/Send:</td>
							<td style=\"$tdRight\">" . $booking ['datetime_fetch'] . "</td>
						</tr>
						<tr>
							<td style=\"$tdLeft\">Supported Document (Ref. No):</td>
							<td style=\"$tdRight\">" . $booking ['ref_no'] . "</td>
						</tr>
					";
			$html .= "</table>";
			$html .= "</div>";
			
			// Passenger Details
			$tdHead = "
						font-weight: bold;
						background-color:#f4f4f4;
						padding: 3px;
						border-right: 1px solid #999999;
						border-bottom: 1px solid #999999;
					";
			$tdRow = "padding: 3px;";
			$html .= "<div style=\"border:1px solid #000000; margin-top:10px;\">";
			$html .= "<div style=\"
							background-color: #cccccc;
							border-bottom: 1px solid #000000;
							font-weight: bold;
							padding: 5px;
						\">Passenger Details</div>";
			$html .= "<table cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">";
			if(count($passenger_array) > 0){
				$html .= "
							<tr>
								<td style=\"$tdHead\" width=\"5%\">#</td>
								<td style=\"$tdHead\" align=\"center\">Name</td>
								<td style=\"$tdHead\" align=\"center\">Position</td>
								<td style=\"$tdHead\" align=\"center\">Handphone</td>
							</tr>
						";
				for($i = 0; $i < count($passenger_array); $i++){
					$name = $passenger_array [$i][0];
					$post = $passenger_array [$i][1];
					$phone = $passenger_array [$i][2];
					
					$html .= "
								<tr>
									<td style=\"$tdRow\">" . ($i + 1) . "</td>
									<td style=\"$tdRow\">" . $name . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $post . "</td>
									<td style=\"$tdRow\" align=\"center\">" . $phone . "</td>
								</tr>
							";
				}
			}
			else
				$html .= "
							<tr>
								<td>Passenger: No record founds.</td>
							</tr>
						";
			$html .= "</table>";
			$html .= "</div>";
			
			$m = new MAIL;
			$m->From($this->mail ['user_name'], "VRMS Helpdesk");
			$m->AddTo($request ['email'], $request ['em_number']);
			$m->Subject("VRMS :: [STATUS - " . $request ['status'] . " - CHARTED]");
			
			$pdfcontent = $this->suratChartedApprovedPDF();
			$m->Attach [] = array(
					"content" => $pdfcontent,
					"type" => "application/pdf",
					"name" => "surat.pdf",
					"encoding" => "base64",
					"id" => MIME::unique(),
				);
				
			$m->Html($html);
			
			if($c = $m->Connect(
					$this->mail ['smtp'],
					$this->mail ['port'])
				){
			if($m->Send($c) === false)
				return false;
			}
			else
				return false;
			return true;
		}
	}
	
	$myemail = new myEmail;
	$myemail->fpdf = new PDF_MC_Table;
	$myemail->session = $_SESSION;
	$myemail->mail = $email_param;
	$myemail->request_array = $request_array;
	$myemail->is_utm = $is_utm;
?>