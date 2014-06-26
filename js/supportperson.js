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
	$('#MembersId').val('');
	$('#btnSave').val('Save!');	
	$('#entryForm').show();
	$('#grid').hide();
}

function onListClick() {
	$('#entryForm').hide();
	$('#grid').show();
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

function fnCallback(){
}

$(function() {
	TempLoadId++;	
	$('body').ajaxStart(function() {
		$('#ajax-loader').show();
		$('#btnSave').attr('disabled', 'disabled');
	});
	$("#formID").validationEngine({
		ajaxFormValidation : true,
		ajaxFormValidationMethod : 'post',
		onAjaxFormComplete : ajaxValidationCallback
	});	
	$('#btnSave').removeAttr('disabled');
	oTable = $('#supportPersonGrid').dataTable({
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
		"aaSorting" : [[2, 'desc']],
		"oLanguage" : {
			"sLengthMenu" : "Display _MENU_ Records",
			//"sZeroRecords": "Nothing found - sorry",
			"sInfo" : "Showing _START_ to _END_ of _TOTAL_ Records",
			"sInfoEmpty" : "Showing 0 to 0 of 0 Records",
			"sInfoFiltered" : "(filtered from _MAX_ total Records)"
		},
		"fnDrawCallback" : function() {			
			$('td img.pEdit', oTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = oTable.fnGetData(nTr);
					if (confirm("Do you really want to change in this Support Person Record?")) {
						onAddEditClick();
						resetForm("formID");						
						$('#btnSave').removeAttr('disabled');
						$('#btnSave').val('Update');

						//console.log(aData);

						MembersId = aData[0];
						$('#MembersId').val('' + MembersId);
						$('#MembersName').val(aData[2]);
						$('#Depertment').val(aData[3]);
						$('#Designation').val(aData[4]);				

					}

				});
			});
			$('td img.pDrop', oTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = oTable.fnGetData(nTr);
					if (confirm("Do you really want to delete this Support Person Record? \nAll Support Status Report for this person will remove.")) {
						MembersId = aData[0];
						$.ajax({
							"type" : "POST",
							"url" : 'serverProcessing.php',
							"data" : 'action=deletePersonRecord&MembersId=' + MembersId,
							"success" : function(response) {
								if (response == 1) {
									oTable.fnDraw();
									alert("Support Person Record has been deleted completely.");
								} else
									alert("Support Person Record has not been deleted. Problem occured.");
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
				"value" : "getSupportPersonData"
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
			"sClass" : "MembersName"
		}, {
			"sClass" : "Depertment"
		}, {
			"sClass" : "Designation"
		}, {
			"sClass" : "Action center"
		}]
	});

	$('#grid').hide();	
	onAddEditClick();	
	
	$('select[name=supportStatusGrid_length]').change(function() {
		$('#ajax-loader').hide();
	});
	//$('body').ajaxComplete(function() {
	//  $('#ajax-loader').hide();
	//});
});
