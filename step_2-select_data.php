<!--
then I will bring the beauty of!

AND 
Then do not forget to delete the file
-->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

$(function() {
	$("a#sheet_index").click(function() {
		var hiddenData = this.className; 
		var dataArray = hiddenData.split('?'); //[0]-> sheet index; [1] -> tmp_file name
		//alert(dataArray[1]);
		$("#step_2-form input[@type=hidden]#sheet_index").val(dataArray[0]);
		$("#step_2-form input[@type=hidden]#xls_path").val(dataArray[1]);	
		$("#step_2-form").submit();
	});
});


$(function() {
	$("a#scope_data").click(function() {
		var scopeData = this.className; 
		var scopeArray = scopeData.split('?'); //[0]-> xls file path; [1]-> sheet index; [2]-> first row; [3]-> first col
//		alert($("#row_col input[@type=hidden]#first_row").attr('value'));
		$("form#init_data_scope_form input[@type=hidden]#xls_file").val(scopeArray[0]);
		$("form#init_data_scope_form input[@type=hidden]#sheet_index").val(scopeArray[1]);
		$("form#init_data_scope_form input[@type=hidden]#xls_first_row").val($("#row_col input[@type=hidden]#first_row").attr('value'));
		$("form#init_data_scope_form input[@type=hidden]#xls_first_col").val($("#row_col input[@type=hidden]#first_col").attr('value'));		
		$("form#init_data_scope_form").submit();
	});
});

</script>
</head>
<body>

<form action="<?php $PHP_SELF; ?>" id="step_2-form" name="step_2-form" method="POST" enctype="multipart/form-data">
<input type="hidden" id="sheet_index" name="sheet_index" value="0" />
<input type="hidden" id="xls_path" name="xls_path" value="" />
</form>

<form action="step_3-parse_data.php" id="init_data_scope_form" name="init_data_scope_form" method="POST">
<input type="hidden" id="xls_file" name="xls_file" value="" />
<input type="hidden" id="sheet_index" name="sheet_index" value="0" />
<input type="hidden" id="xls_first_row" name="xls_first_row" value="" />
<input type="hidden" id="xls_first_col" name="xls_first_col" value="" />
</form>


<a href="step_1-upload.php"> <<< </a>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$have_file=0;
require_once 'Excel/excel_reader2.php';
//-------debug output
echo "<div style='border: 1px dashed grey;'><small><b>DEBUG INFO [FILES]: </b>";print_r($_FILES);echo"<br><b>DEBUG INFO [POST]: </b>"; print_r($_POST); echo "</small></div>";
//-------end of debug
if( (isset($_FILES['xls_file']['name'])) && (!is_null($_FILES['xls_file']['size'])) ){
	$have_file=1;	
	$sheet_index=0;
	$xls_file=$_FILES['xls_file'];
	$uploaddir = './files/';  
	$file = $uploaddir . basename($_FILES['xls_file']['name']);   
	if (copy($_FILES['xls_file']['tmp_name'], $file)){echo "<br>File ".$xls_fle['xls_file']['name']." upload successfully.<br>";}
		else{echo "<br>Error while uploading ".$xls_fle['xls_file']['name'];}//This else blok must be after all (view sheets etc)
} else if(isset($_POST['xls_path'])){
	$file=$_POST['xls_path'];
	$sheet_index=$_POST['sheet_index'];
	$have_file=1;
	} else {$have_flag=0;}

if($have_file){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding("UTF-8");
	$data->read($file);

	echo "<br>You must choose what excel sheet we will parse <strong>(By default Sheet #1)</strong> and in which cell the data starts.";
	echo "<br><small>If the sheet number is different, simly click on proper sheet below...</small><br>";
	echo "<form name='row_col' id='row_col'>";
	echo "<p>Row number: <input type='text' value='2' id='first_row' /></p>";
	echo "<p>Col number: <input type='text' value='1' id='first_col' /></p>";
	echo "</form>";
	for($sheets=0;$sheets<count($data->sheets);$sheets++){
	echo "| <a href='#' id='sheet_index' class='$sheets?$file'>Sheet #".$num=$sheets+'1'." (".$data->boundsheets[$sheets]['name'].")"."</a> |";
	}
	echo "<div><p>";
	echo "When you are shure that all settings are correct, click ";
	echo "<a href='#' id='scope_data' class='$file?$sheet_index'>this link.</a>";
	echo "</p></div>";
	echo $data->dump(true,true,$sheet_index);


}
else {echo "There was some error. It seems that we have no data. ";}

?>
</body>
</html>
