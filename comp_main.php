<div id="ajax-loader"><img src="images/ajax-loader.gif" /></div>
<div id="button-collections">
	<br />
	<input type="button" onClick="onAddEditClick()" class="buttonname" value="Add">
	<input type="button" onClick="onListClick()" class="buttonname" value="Suppot Status List">
	<a href="addsupportperson.php" class="buttonname">Add Support Person</a>
</div>
<div id="entryForm" style="padding-bottom:20px;">
	<form id="formID" class="formular" method="post" action="serverProcessing.php" style="width:950px">
		<fieldset>
			<legend>
				Support Status Add/Edit
			</legend>
			<label class="left"> <label><span class="label">Project/Tool : </span>
					<select name="ProjectToolId" id="ProjectToolId" class="validate[required] text-input2">
						<option value="">Choose a Project/Tool</option>
					</select> </label> </label>
			<label> <span class="label">Mode Of Support : </span>
				<select name="ModeId" id="ModeId" class="validate[required] text-input2">
					<option value="">Choose a Support Mode</option>
				</select> </label>
			<label class="left"> <span class="label">Types Of Facility : </span>
				<select name="FacilityTypeId" id="FacilityTypeId" onChange="onComboFacility()" class="validate[required] text-input2">
					<option value="">Choose a Facility Type</option>
				</select> </label>
			<label> <span class="label">Facility Name : </span>
				<select name="FacilityId" id="FacilityId" class="validate[required] text-input2">
					<option value="">Choose a Facility</option>
				</select> </label>

			<label class="left"> <span class="label">Requested By : </span>
				<input value="" class="validate[required] text-input" type="text" name="RequestedBy" id="RequestedBy" />
			</label>
			<label> <span class="label">Requested Date : </span>
				<input style="float:left;" value="" class="validate[required] text-input datepicker" type="text" name="ReqSupportDate" id="ReqSupportDate" />
			</label>
			<label class="left"> <span class="label">Support Person : </span>
				<select name="MembersId" id="MembersId" class="validate[required] text-input2">
					<option value="">Choose a Team Member</option>
				</select> </label>
			<label> <span class="label">Date Of Support : </span>
				<input style="float:left;" value="" class="validate[required] text-input datepicker" type="text" name="SupportDate" id="SupportDate" />
			</label>
			<label  class="left"> <span class="label">Support Duration(DD:HH:MM) : </span>
				<input value="" class="validate[required] text-input" type="text" name="SupportDuration" maxlength="8" id="SupportDuration" />
			</label>
			<label  class="left"> <span class="label2">Task Performed : </span> 				<textarea class="label2 validate[required] text-input" rows="4" cols="20" name="TaskPerformes" id="TaskPerformes" ></textarea> </label>
			<label> <span class="label3" >Remarks : </span> 				<textarea class="label3 validate[] text-input" rows="4" cols="20" name="Remarks" id="Remarks" ></textarea> </label>
		</fieldset>
		<input name="SupportId" id="SupportId" value="" style="display:none;"/>
		<input name="action" value="insertUpdateSupportRecord" style="display:none;"/>
		<input class="submit buttonname" type="submit" id="btnSave" value="Save!"/>
		<hr/>
	</form>
</div>
<div id="grid">
	<div><select style="float:right;margin:20px 0;" onChange="ExportReport(this.value)" id="ExpMembersId" name="ExpMembersId">
    <option value="">Choose a Team Member</option><option>Mahmudul Islam</option><option>Md. Elias Miah</option><option>Md. Khurshed Alam Nayeem</option></select>
	<span style="margin:20px 0;font-family:Calibri !important;font-size:15px;margin-right:20px;float:right;">Export As CSV</span>
	<select style="float:right;margin:20px 0;margin-right:20px;" onChange="ExportReport(this.value)" id="YearMonth" name="YearMonth">
		<?php 			
			$MonthName=array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");			
			$time = time () ; 
			$year= date("Y",$time);
			$month= date("m",$time);
			for($k=2012;$k<2015;$k++){							
				for($j=0;$j<count($MonthName);$j++){
					$str='';	
					$digit=$j<9? '0'.($j+1) : ($j+1);
					if($year==$k&&$month==$digit)
						$str='selected';
					echo '<option '.$str.' value="'.$k.'-'.$digit.'-01 00:00:00'.'">'.$MonthName[$j].'-'.$k.'</option>';
				}
			}
		?>
	</select>
	<span style="margin:20px 0;font-family:Calibri !important;font-size:15px;margin-right:20px;float:right;">Choose Year & Month</span>
	</div>
	<a href="javascript:void(0);" id="mydestiny" style="display:none;">Link</a>
 
   <button style="text-align; background-color:#cecece; color:#000000"; id='printBtnId' onclick="copyText()">print</button> 
   <button style="text-align; background-color:#cecece; color:#000000"; id='pdfBtnId' onclick="pdffunction()">pdf</button> 
   <button style="text-align; background-color:#cecece; color:#000000"; id='excelBtnId' onclick="excelfunction()">excel</button> 
	 <script>
		function copyText()
		{
			 var yearval=document.getElementById("YearMonth").value;
			 window.location="printProcessing.php?action=printPass&YearMonth="+yearval;
			 
		}
		
		function pdffunction()
		{
			 var yearval=document.getElementById("YearMonth").value;
			 window.location="preparepdfdocument.php?action=pdfPass&YearMonth="+yearval;
			
		}
		
		function excelfunction()
		{
			 var yearval=document.getElementById("YearMonth").value;
			 window.location="prepareexcelsheet.php?action=excelPass&YearMonth="+yearval;
			
		}
		
     </script>
     
 
	
     
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="supportStatusGrid">
		<thead>
			<tr>
				<th>#SL</th>
				<th>#SL</th>
				<th style="text-align:left;">Project/Tool</th>
				<th style="text-align:left;">Facility Type</th>
				<th style="text-align:left;">Facility</th>
				<th style="text-align:left;">Support Person </th>
				<th>Date Of Support</th>
				<th>Created Date</th>
				<th>More</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<style text="text/css">
	.ui-datepicker {
		font-size: 12px;
	}
	#grid {
		width: 990px;
		margin: 0 auto;
	}
</style>
<script type="text/javascript" src="js/supportstatus.js"></script>