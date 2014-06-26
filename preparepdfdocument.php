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
	case 'pdfPass' :
		pdfPass();
		break;	
}
function pdfPass()
{

$YearMonth=$_GET['YearMonth'];
	 
	$EndYearMonth=explode(' ',$YearMonth);
	$EndYearMonth=explode('-',$EndYearMonth[0]);
	$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	include ('html2pdf/html2pdf.class.php');
	$html =
	  '<page>'.
	  '<style> 
	table, td{word-wrap: break-word;border:1px solid black;border-collapse:collapse;
	font-size:12px;font-weight:normal;padding:4px;
	}
	   .someClassName
	{
	width: 50px;
	} 
	#title{
		text-align:center;
		font-weight:bold;
		font-size:18px;
		margin-top:20px;
	}
	#datatable{
		margin-top:300px;
		margin:0 auto;
	}
	.header td{font-weight:bold;text-align:center;}
	.number{
		text-align:right;
	}
	  </style>

	<div id="title"> '.$ListOfMonth[0+$Month].' '.$Year.'</div><br /><br />
	<tr>
	<h1 style="text-align:center;">Suppot Status List</h1> 
	</tr>
	<table id="datatable" style="width: 100%;" align="center">
	   
	 <tr class="header">	    
		<td>#SL</td>
		<td>project/tool</td>
		<td>Facility type</td>
		<td>Facility</td>
	   	<td>Support person</td>
		<td>date of support</td>    
	  </tr>';

	
	
	$content = "
	<page>
		<h1>Exemple d'utilisation</h1>
		<br>
		Ceci est un <b>exemple d'utilisation</b>
		de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
	</page>";
	
	$sql=" SELECT ProjectToolName, FacilityTypeName,FacilityName,ModeName,MembersName,SupportDate   FROM  supportstatus a, projecttools b, facility_type c , facility d, supportmodes e,teammembers f
	WHERE a.ProjectToolId=b.ProjectToolId and d.FacilityTypeId=c.FacilityTypeId and a.FacilityId = d.FacilityId and a.ModeId = e.ModeId and a.MembersId = f.MembersId
	".$SupportDate." ";

	
	$r= mysql_query($sql) ;
	$i=1;	
	if ($r)
	while($rec=mysql_fetch_array($r))
		{
				
			
			$html.= '<tr>
			     <td>
			     '.'00'.$i.'
			     </td>
			     
				 <td>
			     '.$rec['ProjectToolName'].'
			     </td>
			      <td>
			     '.$rec['FacilityTypeName'].'
			     </td>
			     
				   <td>
			     '.$rec['FacilityName'].'
			     </td>		
				   <td>
			     '.$rec['MembersName'].'
			     </td>	
			        <td>
			     '.$rec['SupportDate'].'
			     </td>			
				 				 
			     </tr>';
			     
				 
				 $i++; 
		}
		
	
	$html.='</table>
	  '.
	  '</page>';
	
	$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', 20);
	$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->WriteHTML($html);
	$html2pdf->Output("SupportStatus.pdf");
	
	
	
   
   }
    	
	

?>