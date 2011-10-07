<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

$(function() {
	$("a#sheet_index").click(function() {
		var hiddenData = this.className; 
		var dataArray = hiddenData.split('?'); //[0]-> sheet index; [1] -> tmp_file name
		alert(dataArray[1]);
		$("#xls_upload input[@type=hidden]#sheet_index").val(dataArray[0]);
		$("#xls_upload input[@type=hidden]#xls_path").val(dataArray[1]);	
		$("#xls_upload").submit();
	});
});
</script>
</head>

<body>
<div style="position:absolute; margin-top:100px;width:100%;text-align:center;"><center>
<form action="step_2-select_data.php" id="xls_upload" name="xls_upload" method="POST" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="7340032" />
Excel file:<input type="file" name="xls_file" />
<input type="submit" id="add_xls" value="Submit"></input>
</form>
</center>
</div>
</body>
</html>
