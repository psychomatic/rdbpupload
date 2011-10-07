<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'Excel/excel_reader2.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
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

<form action="<?php $PHP_SELF; ?>" id="xls_upload" name="xls_upload" method="POST" enctype="multipart/form-data">
Excel file:<input type="file" name="xls_file" />
<input type="hidden" id="sheet_index" name="sheet_index" value="0" />
<input type="hidden" id="xls_path" name="xls_path" value="" />
<input type="submit" id="add_xls" value="Submit"></input>
</form>

<?
print_r($_POST);
print_r($_FILES);
if((isset($_FILES['xls_file'])) && ($_FILES['xls_file']['size']<>'')){
	$xls_file=$_FILES['xls_file'];
	echo "<p>Parsing 'xls' file: <b>".$xls_file['name']."</b></p>";
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding("UTF-8");
	$uploaddir = './files/';  
	$file = $uploaddir . basename($_FILES['xls_file']['name']);   
	if (copy($_FILES['xls_file']['tmp_name'], $file)){echo "done";}
	echo '<br>uploaded filename: '.$_FILES['xls_file']['name'];     
//	$fp=fopen($xls_file['tmp_name'],"r");
//echo $fp;
	$data->read($file);//$_FILES['xls_file']['tmp_name']);
echo "<p>Sheets count in file: ".count($data->sheets).'</p>'; 	
/*for($sheets=0;$sheets<count($data->sheets);$sheets++)
	{
	echo "Sheet name: ".$data->boundsheets[$sheets]['name']."<br />";
	echo "<p>Rows count on ".($sheets+1)." sheet: ".$data->rowcount($sheet_index=$sheets)."</p>";
	echo "<p>Cols count on ".($sheets+1)." sheet: ".$data->colcount($sheet_index=$sheets)."</p><hr>";
	}*/
echo "<hr>";
$data->dump(true,true,$sheet=0);
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
	}
	echo "\n";

}

//echo "Which sheet we will try to parse? <select name='sheet_index' id='sheet_index'>";
//for($sheets=0;$sheets<count($data->sheets);$sheets++){
//	echo "<option value='".$sheets."'>".$data->boundsheets[$sheets]['name']."</option>";
//}
//echo "</select>";
echo "You can view sheet data:<br>";
for($sheets=0;$sheets<count($data->sheets);$sheets++){
	echo "| <a href='#' id='sheet_index' class='$sheets?$file'>".$data->boundsheets[$sheets]['name']."</a> |";
}
echo $data->dump(true,true);
}

//if (isset($_POST['sheet_index'])){$sheet_index=$_POST['sheet_index'];} else {$sheet_index=0;}
echo "<hr>";print_r($_POST);
if(isset($_POST['xls_path'])){$xls=$_POST['xls_path'];
	echo $xls;
	$sheet_index=$_POST['sheet_index'];
	$data1 = new Spreadsheet_Excel_Reader();
	$data1->setOutputEncoding("UTF-8");
	$data1->read("$xls");
	echo $data1->dump(true,true,$sheet_index);
echo "You can view sheet data:<br>";
for($sheets=0;$sheets<count($data1->sheets);$sheets++){
	echo "| <a href='#' id='sheet_index' class='$sheets?$xls'>".$data1->boundsheets[$sheets]['name']."</a> |";
}
}
 ?>
</body>
</html>
