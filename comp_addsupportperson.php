<div id="ajax-loader"><img src="images/ajax-loader.gif" /></div>
<div id="button-collections">
	<br />
	<input type="button" onClick="onAddEditClick()" class="buttonname" value="Add">
	<input type="button" onClick="onListClick()" class="buttonname" value="Suppot Person List">
	<a href="index.php" class="buttonname">Support Status</a>
</div>
<div id="entryForm" style="padding-bottom:20px;">
	<form id="formID" class="formular" method="post" action="serverProcessing.php" style="width:950px">
		<fieldset>
			<legend>
				Support Person Add/Edit
			</legend>
			<label class="left"> <label><span class="label">Team Members Name : </span>
					<input value="" class="validate[required] text-input" type="text" name="MembersName" id="MembersName" /> </label> </label>
			<label> <span class="label">Depertment : </span>
				<input value="" class="validate[] text-input" type="text" name="Depertment" id="Depertment" /> </label>
			<label class="left"> <span class="label">Designation : </span>
				<input value="" class="validate[] text-input" type="text" name="Designation" id="Designation" /></label>			
		</fieldset>
		<input name="MembersId" id="MembersId" value="" style="display:none;"/>
		<input name="action" value="insertUpdateSupportPerson" style="display:none;"/>
		<input class="submit buttonname" type="submit" id="btnSave" value="Save!"/>
		<hr/>
	</form>
</div>
<div id="grid">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="supportPersonGrid">
		<thead>
			<tr>
				<th>#SL</th>
				<th>#SL</th>
				<th style="text-align:left;">Members Name</th>
				<th style="text-align:left;">Depertment</th>
				<th style="text-align:left;">Designation</th>								
				<th>Action</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<style text="text/css">	
	#grid {
		width: 990px;
		margin: 0 auto;
	}
</style>
<script type="text/javascript" src="js/supportperson.js"></script>