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
	case 'excelPass' :
		excelPass();
		break;	
}
function excelPass()
{
  
  
    require('export-excel/lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	//$objPHPExcel->getProperties()
								//	->setCreator("SoftWorks Ltd.")
									//->setLastModifiedBy("SoftWorks Ltd.")
									//->setTitle("Contraceptive Summary Report");			
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Suppot Status List')	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');	
													
    $objPHPExcel->getActiveSheet()
	                            		
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', 'project/tool')
									->SetCellValue('C6', 'Facility type')							
									->SetCellValue('D6', 'Facility')						
			  						->SetCellValue('E6', 'Support person')
									->SetCellValue('F6', 'date of support');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(18);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('F6'  . ':F6') -> applyFromArray($styleThinBlackBorderOutline);
   
        	
		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
   
    // $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);        
      //  $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);

		//$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//$objPHPExcel->getActiveSheet()->mergeCells('A3:A3');
			
		//$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   
   
   
   
    $YearMonth=$_GET['YearMonth'];
	 
	$EndYearMonth=explode(' ',$YearMonth);
	$EndYearMonth=explode('-',$EndYearMonth[0]);
	$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	
	
	
	$sql=" SELECT ProjectToolName, FacilityTypeName,FacilityName,ModeName,MembersName,SupportDate   FROM  supportstatus a, projecttools b, facility_type c , facility d, supportmodes e,teammembers f
	WHERE a.ProjectToolId=b.ProjectToolId and d.FacilityTypeId=c.FacilityTypeId and a.FacilityId = d.FacilityId and a.ModeId = e.ModeId and a.MembersId = f.MembersId
	".$SupportDate."";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['ProjectToolName'])								
									->SetCellValue('C'.$j, $rec['FacilityTypeName'])									
									->SetCellValue('D'.$j, $rec['FacilityName'])									
									->SetCellValue('E'.$j, $rec['MembersName'])									
									->SetCellValue('F'.$j, $rec['SupportDate'])											
									
									;  			
				
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
					
			 $i++; $j++;
				 
				 
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'export-excel/media/' . $file)); 
	header('Location: export-excel/media/' . $file);
	
	
}
?>