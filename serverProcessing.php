<?php
require ("define.inc");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case 'insertUpdateSupportRecord' :
		insertUpdateSupportRecord($conn);
		break;	
	case 'deleteSupportRecord' :
		deleteSupportRecord($conn);
		break;
	case 'getSupportStatusData' :
		getSupportStatusData($conn);
		break;
	case 'getComboProjectTools' :
		getComboProjectTools($conn);
		break;
	case 'getComboModeSupport' :
		getComboModeSupport($conn);
		break;
	case 'getComboFacilityType' :
		getComboFacilityType($conn);
		break;
	case 'getComboFacility' :
		getComboFacility($conn);
		break;
	case 'getComboSupportPerson' :
		getComboSupportPerson($conn);
		break;
	
	case 'insertUpdateSupportPerson' :
		insertUpdateSupportPerson($conn);
		break;	
	case 'getSupportPersonData' :
		getSupportPersonData($conn);
		break;	
	case 'deletePersonRecord' :
		deletePersonRecord($conn);
		break;
	case 'exportRecordData' :
		exportRecordData($conn);
		break;
	default :
		echo "{failure:true}";
		break;
}

function insertUpdateSupportRecord($conn) {
	$SupportId = $_POST['SupportId'];
	$FacilityId = $_POST['FacilityId'];
	$FacilityTypeId = $_POST['FacilityTypeId'];
	$MembersId = $_POST['MembersId'];
	$ModeId = $_POST['ModeId'];
	$SupportDuration = $_POST['SupportDuration'];
	$ProjectToolId = $_POST['ProjectToolId'];
	$RequestedBy = mysql_real_escape_string($_POST['RequestedBy']);
	$ReqSupportDate = $_POST['ReqSupportDate'];
	$SupportDate = $_POST['SupportDate'];
	$TaskPerformes = mysql_real_escape_string($_POST['TaskPerformes']);
	$Remarks = mysql_real_escape_string($_POST['Remarks']);
	$error = 0;

	$sql="";
	if($SupportId==''){	
		$sql = '	 INSERT INTO `supportstatus` (`SupportId` ,`ProjectToolId` ,`ModeId` ,`MembersId` ,`FacilityId` ,`RequestedBy` ,`ReqSupportDate` ,`SupportDate` ,`SupportDuration` ,`TaskPerformes` ,`Remarks` ,`CreatedDate` ,`LastUpdateTime`)VALUES (
NULL , "' . $ProjectToolId . '", "' . $ModeId . '", "' . $MembersId . '", "' . $FacilityId . '", "' . $RequestedBy . '", "' . $ReqSupportDate . '", "' . $SupportDate . '", "' . $SupportDuration . '", "' . $TaskPerformes . '", "' . $Remarks . '", NOW(), NOW()); ';
	}else{
		$sql = '	UPDATE `supportstatus` SET 
					ProjectToolId=' . $ProjectToolId . ', ModeId=' . $ModeId . ', FacilityId=' . $FacilityId . ', MembersId=' . $MembersId . ', 
					RequestedBy="' . $RequestedBy . '", ReqSupportDate="' . $ReqSupportDate . '", SupportDate="' . $SupportDate . '", TaskPerformes="' . $TaskPerformes . '", 
					SupportDuration="' . $SupportDuration . '", Remarks="' . $Remarks . '",
					LastUpdateTime=NOW()
					WHERE `SupportId` ="'.$SupportId.'";	';
	}
	$sDLength=strlen($SupportDuration);
	$l = explode(':', $SupportDuration);
	$lLength=count($l);
	
	if($SupportDuration=="00:00:00"||$sDLength!=8||$lLength!=3){
		$error = 2;
	}else{		
		if (mysql_query($sql, $conn))
			$error = 1;
		else
			$error = 0;			
	}
	echo $error;
}

function deleteSupportRecord($conn) {
	$SupportId = $_POST['SupportId'];	
	$error = 0;
	
	$sql = "	DELETE FROM supportstatus WHERE SupportId ='".$SupportId."';  ";
	
	if (mysql_query($sql, $conn))
		$error = 1;
	else
		$error = 0;				
	echo $error;
}

function getSupportStatusData($conn) {
	$MembersId = $_POST['MembersId'];	
	$YearMonth = $_POST['YearMonth'];
	$EndYearMonth=explode(' ',$YearMonth);
	$EndYearMonth=explode('-',$EndYearMonth[0]);
	$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($MembersId){
		$MembersId=" and d.MembersName='".$MembersId."' ";
	}
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."' and SupportDate <= '".$EndYearMonth."' ";
	}
	
	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	/* Ordering */
	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") { $sWhere = " and ( ProjectToolName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' or " . " FacilityName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' or " . " MembersName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' or " . " FacilityTypeName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
	}

	//$cStatus = ($_POST['currentstatus'] == '0') ? "" : " and CurrentStatus='" . $_POST['currentstatus'] . "' ";

	$sql = "select SQL_CALC_FOUND_ROWS a.SupportId, b.ProjectToolId, b.ProjectToolName, a.ModeId, c.ModeName, d.MembersId, d.MembersName, e.FacilityId, e.FacilityTypeId, f.FacilityTypeName, e.FacilityName, a.RequestedBy, a.ReqSupportDate, a.SupportDate, a.CreatedDate, a.SupportDuration, a.TaskPerformes, a.Remarks
FROM `supportstatus` a, projecttools b, supportmodes c, teammembers d, facility e,  facility_type f 
Where a.ProjectToolId=b.ProjectToolId and a.ModeId=c.ModeId and a.MembersId=d.MembersId and a.FacilityId=e.FacilityId and f.FacilityTypeId=e.FacilityTypeId ".$MembersId." ".$SupportDate." " . $cStatus . " 
$sWhere $sOrder  $sLimit;";
	//and YearId='" . $_POST['procyear'] . "' " . $cStatus . " $sWhere $sOrder  $sLimit;";
	//order by PackageDesc

	//echo $sql;
	$pacrs = mysql_query($sql, $conn);
	$sql = "SELECT FOUND_ROWS()";
	$rs = mysql_query($sql, $conn);
	$r = mysql_fetch_array($rs);
	$total = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
	$f = 0;
	$serial = $_POST['iDisplayStart'] + 1;		
	while ($row = @mysql_fetch_object($pacrs)) {
		$SupportId = $row -> SupportId;
		$ProjectToolId = $row -> ProjectToolId;
		$ProjectToolName = $row -> ProjectToolName;
		$ModeId = $row -> ModeId;
		$ModeName = $row -> ModeName;
		$MembersId = $row -> MembersId;
		$MembersName = $row -> MembersName;
		$FacilityId = $row -> FacilityId;
		$FacilityTypeId = $row -> FacilityTypeId;
		$FacilityTypeName = $row -> FacilityTypeName;
		$FacilityName = $row -> FacilityName;
		$RequestedBy = $row -> RequestedBy;
		$ReqSupportDate = $row -> ReqSupportDate;
		$SupportDate = $row -> SupportDate;
		$CreatedDate = $row -> CreatedDate;
		$SupportDuration = $row -> SupportDuration;
		$TaskPerformes = rawurlencode($row -> TaskPerformes);
		$Remarks = rawurlencode($row -> Remarks);

		$ClassName = explode('^', $temp);
		$Tooltips = explode('^', $tooltips);
		$Milestone = explode('^', $milestone);
		$st = '';
		$i = 0;
		while ($i < count($ClassName)) { $st .= "<div class='" . $ClassName[$i] . "' title='" . $Tooltips[$i] . "' >" . $Milestone[$i] . "</div>";
			$i++;
		}
		
		if(strlen($serial) < 3)
			$s = (strlen($serial) < 2) ? '00' . $serial : '0'.$serial;
		else
			$s =$serial;
		//$s = (strlen($serial) < 2) ? '00' . $serial : $serial;
		if ($f++)
			echo ",";
				
		$x="<img class='pMore' src='images/details_open.png' />"; 
		$y="<img class='pEdit' src='images/i_edit.png' />";
		$z="<img class='pDrop' src='images/i_drop.png' />"; 
				  
		echo '["' . $SupportId . '","'.$s.'","' . $ProjectToolName . '","' . $FacilityTypeName . '","' . $FacilityName . '","' . $MembersName . '","' . date('Y-m-d',strtotime($SupportDate)) . '","'  . $CreatedDate . '","'.$x.'","'  . $y.$z . '","'.$ProjectToolId.'","'.$ModeId.'","'.$FacilityTypeId.'","'.$FacilityId.'","'.$RequestedBy .'","'.$MembersId .'","'.date('Y-m-d',strtotime($ReqSupportDate)) .'","'.$SupportDuration .'","'.$TaskPerformes .'","'.$Remarks .'"]';

		$serial++;

	}/// end of while()

	echo ']}';
}

function fnColumnToField($i) {
	if ($i == 2)
		return "ProjectToolName";
	else if ($i == 3)
		return "FacilityName";
	else if ($i == 4)
		return "MembersName";
	else if ($i == 5)
		return "SupportDate";
	else if ($i == 6)
		return "FacilityTypeName";
}

function getComboProjectTools($conn) {
	$sql = " 	SELECT * FROM `projecttools` order by ProjectToolName ";

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		echo json_encode($arr);
}

function getComboModeSupport($conn) {
	$sql = " 	SELECT * FROM `supportmodes` order by ModeName ";

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		echo json_encode($arr);
}

function getComboFacilityType($conn) {
	$sql = " 	SELECT * FROM `facility_type` order by FacilityTypeName ";

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		echo json_encode($arr);
}

function getComboFacility($conn) {
	$FacilityTypeId = $_GET['FacilityTypeId'];

	$sql = " 	SELECT * FROM `facility`
			where FacilityTypeId='" . $FacilityTypeId . "' and IsActive=1 
			order by FacilityName ";

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		echo json_encode($arr);
}

function getComboSupportPerson($conn) {
	$sql = " 	SELECT * FROM `teammembers` order by MembersName  ";

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		echo json_encode($arr);
}

function insertUpdateSupportPerson($conn) {
	$MembersId = $_POST['MembersId'];
	$MembersName = mysql_real_escape_string($_POST['MembersName']);
	$Depertment = mysql_real_escape_string($_POST['Depertment']);
	$Designation = mysql_real_escape_string($_POST['Designation']);	
	
	$error = 0;

	$sql="";
	if($MembersId==''){	
		$sql = '	 INSERT INTO `teammembers` (`MembersId` ,`MembersName` ,`Depertment` ,`Designation` )VALUES (
NULL , "' . $MembersName . '", "' . $Depertment . '", "' . $Designation . '")';
	}else{
		$sql = '	UPDATE `teammembers` SET 
					MembersName="' . $MembersName . '", Depertment="' . $Depertment . '", Designation="' . $Designation . '"
					WHERE `MembersId` ="'.$MembersId.'";	';
	}
	//echo $sql;		
	if (mysql_query($sql, $conn))
		$error = 1;
	else
		$error = 0;			
	
	echo $error;
}

function fnColumnToField2($i) {
	if ($i == 2)
		return "MembersName";
	else if ($i == 3)
		return "Depertment";
	else if ($i == 4)
		return "Designation";	
}

function getSupportPersonData($conn) {
	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	/* Ordering */
	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField2(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") { $sWhere = " Where ( MembersName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' or " . " Depertment like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' or " . " Designation like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
	}

	$sql = "select SQL_CALC_FOUND_ROWS MembersId, MembersName, Depertment, Designation
FROM teammembers
$sWhere $sOrder  $sLimit;";
	//and YearId='" . $_POST['procyear'] . "' " . $cStatus . " $sWhere $sOrder  $sLimit;";
	//order by PackageDesc

	//echo $sql;
	$pacrs = mysql_query($sql, $conn);
	$sql = "SELECT FOUND_ROWS()";
	$rs = mysql_query($sql, $conn);
	$r = mysql_fetch_array($rs);
	$total = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
	$f = 0;
	$serial = $_POST['iDisplayStart'] + 1;			
	while ($row = @mysql_fetch_object($pacrs)) {
		$MembersId = $row -> MembersId;
		$MembersName = $row -> MembersName;
		$Depertment = $row -> Depertment;
		$Designation = $row -> Designation;
					
		$s = (strlen($serial) < 2) ? '0' . $serial : $serial;
		if ($f++)
			echo ",";
				
		$x="<img class='pMore' src='images/details_open.png' />"; 
		$y="<img class='pEdit' src='images/i_edit.png' />";
		$z="<img class='pDrop' src='images/i_drop.png' />"; 
				  
		echo '["' . $MembersId . '","'.$s.'","' . $MembersName . '","' . $Depertment . '","' . $Designation . '","'  . $y.$z . '"]';

		$serial++;

	}/// end of while()

	echo ']}';
}

function deletePersonRecord($conn) {
	$MembersId = $_POST['MembersId'];	
	$error = 0;
	
	$sql = "	DELETE FROM supportstatus WHERE MembersId ='".$MembersId."'; ";
	mysql_query($sql, $conn);
	$sql = "	DELETE FROM teammembers WHERE MembersId ='".$MembersId."';  ";
	
	if (mysql_query($sql, $conn))
		$error = 1;
	else
		$error = 0;				
	echo $error;
}

function exportRecordData($conn) {
	$MembersId = $_POST['MembersId'];	
	$YearMonth = $_POST['YearMonth'];
	$EndYearMonth=explode(' ',$YearMonth);
	$EndYearMonth=explode('-',$EndYearMonth[0]);
	$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	$sql = "	select SQL_CALC_FOUND_ROWS b.ProjectToolName, c.ModeName, d.MembersName, f.FacilityTypeName, e.FacilityName, a.RequestedBy, a.ReqSupportDate, a.SupportDate, a.CreatedDate, a.SupportDuration, a.TaskPerformes, a.Remarks
				FROM `supportstatus` a, projecttools b, supportmodes c, teammembers d, facility e,  facility_type f 
				Where a.ProjectToolId=b.ProjectToolId and a.ModeId=c.ModeId and a.MembersId=d.MembersId and a.FacilityId=e.FacilityId and f.FacilityTypeId=e.FacilityTypeId
				and d.MembersName='".$MembersId."'
				and SupportDate >= '".$YearMonth."' and SupportDate <= '".$EndYearMonth."'
				Order By a.SupportDate Desc
				";
				
	$retust=mysql_query($sql, $conn);
	$total=mysql_num_rows($retust);
	$arr=array();
	$str='"Support Report for '.$MembersId.'"
';
	$str.='ProjectToolName, ModeName, MembersName, FacilityTypeName, FacilityName, RequestedBy, ReqSupportDate, SupportDate, CreatedDate, SupportDuration, TaskPerformes, Remarks
';
	while ($row = @mysql_fetch_object($retust)) {
		$arr[]=$row;
		$str.='"'.$row->ProjectToolName.'","'.$row->ModeName.'","'.$row->MembersName.'","'.$row->FacilityTypeName.'","'.$row->FacilityName.'","'.$row->RequestedBy.'","'.$row->ReqSupportDate.'","'.$row->SupportDate.'","'.$row->CreatedDate.'","'.$row->SupportDuration.'","'.$row->TaskPerformes.'","'.$row->Remarks.'"
';		
	}
	//echo json_encode($arr);
	date_default_timezone_set('Asia/Almaty');
	//$file=SITEDOCUMENT.'reports/SS_'.date("Y-m-d_His",time()).'.csv';
	$file='reports/SS_'.date("Y-m-d_His",time()).'.csv';
	$fp = fopen($file, 'w');
	fwrite($fp, $str);	
	fclose($fp);
	if($total>0)
		echo $file;
	else
		echo '0';
}
?>