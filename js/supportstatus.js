var oTable;
var FacilityType1;
var FacilityType2;
var FacilityType3;
var TempLoadId=0;

function resetForm(id) {
	$('#' + id).each(function() {
		this.reset();
	});
}

function onAddEditClick() {
	$('#ajax-loader').hide();
	resetForm("formID");
	$('#btnSave').removeAttr('disabled');
	onComboFacility();
	$('#SupportId').val('');
	$('#btnSave').val('Save!');
	$('#SupportDuration').val('00:00:00');
	$('#entryForm').show();
	$('#grid').hide();
}

function onListClick() {
	$('#entryForm').hide();
	$('#grid').show();
}

function onComboProjectTools() {
	$.getJSON('serverProcessing.php', {
		action : 'getComboProjectTools'
	}, function(response) {
		str = '<option value="">Choose a Project/Tool</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].ProjectToolId + '">' + response[i].ProjectToolName + '</option>';
		}
		$('#ProjectToolId').html(str);
	});
}

function onComboModeSupport() {
	$.getJSON('serverProcessing.php', {
		action : 'getComboModeSupport'
	}, function(response) {
		str = '<option value="">Choose a Support Mode</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].ModeId + '">' + response[i].ModeName + '</option>';
		}
		$('#ModeId').html(str);
	});
}

function onComboFacilityType() {
	$.getJSON('serverProcessing.php', {
		action : 'getComboFacilityType'
	}, function(response) {
		str = '<option value="">Choose a Facility Type</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FacilityTypeId + '">' + response[i].FacilityTypeName + '</option>';
		}
		$('#FacilityTypeId').html(str);
	});
}

function allFacility() {
	$('#ajax-loader').show();
	$.getJSON('serverProcessing.php', {
		action : 'getComboFacility',
		FacilityTypeId : 1
	}, function(response) {
		str = '<option value="">Choose a Facility</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FacilityId + '">' + response[i].FacilityName + '</option>';
		}
		FacilityType1 = str;
		$('#ajax-loader').hide();
		$('#btnSave').removeAttr('disabled');
	});
	$.getJSON('serverProcessing.php', {
		action : 'getComboFacility',
		FacilityTypeId : 2
	}, function(response) {
		str = '<option value="">Choose a Facility</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FacilityId + '">' + response[i].FacilityName + '</option>';
		}
		FacilityType2 = str;
	});
	$.getJSON('serverProcessing.php', {
		action : 'getComboFacility',
		FacilityTypeId : 3
	}, function(response) {
		str = '<option value="">Choose a Facility</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FacilityId + '">' + response[i].FacilityName + '</option>';
		}
		FacilityType3 = str;
	});
}

function onComboFacility() {
	FacilityType = $('#FacilityTypeId').val();
	if (FacilityType == 1)
		$('#FacilityId').html(FacilityType1);
	else if (FacilityType == 2)
		$('#FacilityId').html(FacilityType2);
	else if (FacilityType == 3)
		$('#FacilityId').html(FacilityType3);
	else {
		str = '<option value="">Choose a Facility</option>';
		$('#FacilityId').html(str);
	}
}

function onComboSupportPerson() {
	$.getJSON('serverProcessing.php', {
		action : 'getComboSupportPerson'
	}, function(response) {
		str = '<option value="">Choose a Team Member</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].MembersId + '">' + response[i].MembersName + '</option>';
		}
		$('#MembersId').html(str);
	});
}

function beforeCall(form, options) {
	if (window.console)
		//console.log("Right before the AJAX form validation call");
		return true;
}

function ajaxValidationCallback(status, form, json, options) {
	//if (window.console)
	//console.log(status);

	if (status === true) {
		//alert("the form is valid!");
		$('#btnSave').attr('disabled', 'disabled');
		$('#ajax-loader').show();
		if (json == 0) {
			$('#ajax-loader').hide();
			alert('Server Processing Error')
			$('#btnSave').removeAttr('disabled');
		} else if (json == 2) {
			$('#ajax-loader').hide();
			alert('Sorry, Given Support Duration formate is Invalid.');
			$('#btnSave').removeAttr('disabled');
		} else {
			resetForm("formID");
			$('#SupportDuration').val('00:00:00');
			$('#btnSave').removeAttr('disabled');
			oTable.fnDraw();
			onListClick();
			$('#ajax-loader').hide();
			alert('Successfully Saved.')
		}
		//console.log(json);
		// uncomment these lines to submit the form to form.action
		// form.validationEngine('detach');
		// form.submit();
		// or you may use AJAX again to submit the data
	}
}

function fnFormatDetails(nTr) {
	var aData = oTable.fnGetData(nTr);
	var sOut = "<ul class='detailsView' >";
	sOut += "<li class='left'><label>Request By</label><p style='width:3px;padding:0;'>:</p><p>" + aData[14] + "</p></li>";
	sOut += "<li><label>Requested Date</label><p style='width:3px;padding:0;'>:</p><p>" + unescape(aData[16]) + "</p></li>";
	sOut += "<li class='left'><label>Support Duration</label><p style='width:3px;padding:0;'>:</p><p>" + unescape(aData[17]) + " (DD:HH:MM)</p></li>";
	sOut += "<li class='left'><label>Task Performed</label><p style='width:3px;padding:0;'>:</p><p id='left-desc'>" + unescape(aData[18]) + "</p></li>";
	sOut += "<li><label>Remarks</label><p style='width:3px;padding:0;'>:</p><p id='right-desc'>" + unescape(aData[19]) + "</p></li>";
	sOut += "</ul>";
	return sOut;
}

function fnCallback(){
}

$(function() {
	TempLoadId++;
	$(".datepicker").datepicker({
		"dateFormat" : "yy-mm-dd"
	});
	$('body').ajaxStart(function() {
		$('#ajax-loader').show();
		$('#btnSave').attr('disabled', 'disabled');
	});
	$("#formID").validationEngine({
		ajaxFormValidation : true,
		ajaxFormValidationMethod : 'post',
		onAjaxFormComplete : ajaxValidationCallback
	});
	$('#SupportDuration').val('00:00:00');
	$('#btnSave').removeAttr('disabled');
	oTable = $('#supportStatusGrid').dataTable({
		"bFilter" : true,
		"bJQueryUI" : true,
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"sDom" : '<"H"fir>t<"F"lp> ',
		"sPaginationType" : "full_numbers",
		"sScrollX" : "100%",
		"sAjaxSource" : "serverProcessing.php",
		"aaSorting" : [[5, 'desc']],
		"oLanguage" : {
			"sLengthMenu" : "Display _MENU_ Records",
			//"sZeroRecords": "Nothing found - sorry",
			"sInfo" : "Showing _START_ to _END_ of _TOTAL_ Records",
			"sInfoEmpty" : "Showing 0 to 0 of 0 Records",
			"sInfoFiltered" : "(filtered from _MAX_ total Records)"
		},
		"fnDrawCallback" : function() {
			$('td img.pMore', oTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if (this.src.match('details_close')) {
						this.src = "images/details_open.png";
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						this.src = "images/details_close.png";
						oTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
			$('td img.pEdit', oTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = oTable.fnGetData(nTr);
					if (confirm("Do you really want to change in this Support Status Record?")) {
						onAddEditClick();
						resetForm("formID");
						$('#SupportDuration').val('00:00:00');
						$('#btnSave').removeAttr('disabled');
						$('#btnSave').val('Update');

						//console.log(aData);

						SupportId = aData[0];
						$('#SupportId').val('' + SupportId);
						$('#ProjectToolId').val(aData[10]);
						$('#ModeId').val(aData[11]);
						$('#FacilityTypeId').val(aData[12]);
						onComboFacility();
						$('#FacilityId').val(aData[13]);
						$('#RequestedBy').val(aData[14]);
						$('#ReqSupportDate').val(aData[16]);
						$('#MembersId').val(aData[15]);
						$('#SupportDate').val(aData[6]);
						$('#SupportDuration').val(aData[17]);
						$('#TaskPerformes').val(unescape(aData[18]));
						$('#Remarks').val(unescape(aData[19]));

					}

				});
			});
			$('td img.pDrop', oTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = oTable.fnGetData(nTr);
					if (confirm("Do you really want to delete this Support Status Record?")) {
						SupportId = aData[0];
						$.ajax({
							"type" : "POST",
							"url" : 'serverProcessing.php',
							"data" : 'action=deleteSupportRecord&SupportId=' + SupportId,
							"success" : function(response) {
								if (response == 1) {
									oTable.fnDraw();
									alert("Support Status Record has been deleted completely.");
								} else
									alert("Support Status Record has not been deleted. Problem occured.");
							}
						});
					}
					// end of if(confirm())
				});
			});
		},
		"fnRowCallback" : function(nRow, aData, iDisplayIndex) {
			return nRow;
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : "getSupportStatusData"
			});
			aoData.push({
				"name" : "YearMonth",
				"value" : $('#YearMonth').val()
			});
			aoData.push({
				"name" : "MembersId",
				"value" : $('#ExpMembersId').val()
			});

			$.ajax({
				"dataType" : 'json',
				"type" : "POST",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json){					
					TempLoadId++;
					if(TempLoadId>2){
						$('#ajax-loader').hide();
					}					
					fnCallback(json);
				}
			});			
		},
		"aoColumns" : [{
			"bVisible" : false
		}, {
			"sClass" : "sl center",
			"bSortable" : false
		}, {
			"sClass" : "ProjectToolName"
		}, {
			"sClass" : "FacilityTypeName"
		}, {
			"sClass" : "FacilityName"
		}, {
			"sClass" : "MembersName"
		}, {
			"sClass" : "center"
		}, {
			"bVisible" : false,
			"sClass" : "CreatedDate"
		}, {
			"sClass" : "center"
		}, {
			"sClass" : "Action center"
		}]
	});

	$('#grid').hide();
	onComboProjectTools();
	onComboModeSupport();
	onComboFacilityType();
	onAddEditClick();
	allFacility();
	onComboSupportPerson();
	
	$('select[name=supportStatusGrid_length]').change(function() {
		$('#ajax-loader').hide();
	});
	//$('body').ajaxComplete(function() {
	//  $('#ajax-loader').hide();
	//});
});
function ExportReport(localVal){
	oTable.fnDraw();
	localVal=$('#ExpMembersId').val();
	if(localVal==''){
		alert('Please Choose a Team Member.');
	}
	else{		
		$.ajax({				
			"type" : "POST",
			"url" : 'serverProcessing.php',
			"data" : {action:"exportRecordData", MembersId:localVal, YearMonth: $('#YearMonth').val()},
			"success" : function(json){					
				$('#ajax-loader').hide();
				if(json=='0'){
					alert('Sorry, No Support for '+localVal+' is found.');
					$('#mydestiny').attr('href','javascript:void(0);')
				}
				else{
					$('#mydestiny').attr('href',json)
				}
				$('#mydestiny').animate({'opacity':1},800,function(){
					document.getElementById('mydestiny').click(); 					
				});
			}
		});		
	}
}