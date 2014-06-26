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
	case 'printPass' :
		printPass();
		break;	
}
function printPass()
{
	
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
	$i=1;	
	if ($r)
		
	{
		echo '<!DOCTYPE html>
			<html>
			<head>
			<style>
			table,th,td
			{
			border:1px solid black;
			border-collapse:collapse;
			}
			</style>
			</head>
			
			<body>';	
	         echo' <tr style="width=100px;">
	               <h1 style="text-align:center;">Suppot Status List</h1> 
	              </tr>';	
			echo '<table align="center" style="align:center;">';	
			 
		    echo'<tr>';
		    echo'<th>#SL</th>';
		    echo'<th>project/tool</th>';
		    echo'<th>Facility type</th>';
		    echo'<th>Facility</th>';
		    echo'<th>Support person</th>';
		    echo'<th>date of support</th>';   
		    echo'</tr>';
		
		
		
	 	
		while($rec=mysql_fetch_array($r))
		{
			
			echo '<tr>
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
				 				 
			     </tr>
			     ';
				 
				 $i++; 
		}
		
		
		
		
	
	 echo '</table>';
     echo '</body>
      </html>';	
    }		
	else
	{
		
	 
		$error = 0;	
		echo $error;
	}		
	
 
	
	
}
  
  
//printPass();

?>