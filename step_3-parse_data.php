
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
</script>
</head>
<body>
<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'Excel/excel_reader2.php';
//-------debug output
echo "<div style='border: 1px dashed grey;'><small><b>DEBUG INFO [FILES]: </b>";print_r($_FILES);echo"<br><b>DEBUG INFO [POST]: </b>"; print_r($_POST); echo "</small></div>";
//-------end of debug

$host="localhost";
	$user="samtest";
	$pass="samtest";
	$db=mysql_connect($host,$user,$pass) or die("Could not connect!");
	$tab=mysql_select_db("samtest") or die ("no db!!!!");
	@mysql_select_db("samtest") or die ("no db!!!!");
	$names=mysql_query("SET NAMES 'utf-8'",$db);
	@mysql_query("SET CHARACTER SET 'utf8'");
	@mysql_query('set character_set_client="utf8"');
	@mysql_query('set character_set_results="utf8"');
	@mysql_query('set collation_connection="utf8_general_ci"');



if((isset($_POST['xls_file'])) && (!is_null($_POST['xls_file']))){
	//main parser block
	$file=$_POST['xls_file'];
	$sheet_index=$_POST['sheet_index'];
	$first_row=$_POST['xls_first_row'];
	$first_col=$_POST['xls_first_col'];
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding("UTF-8");
	$data->read($file);
	//prepare SQL query
for($row=$first_row; $row<=$data->sheets[$sheet_index]['numRows']; $row++)
{
	for($col=$first_col; $col<=$data->sheets[$sheet_index]['numCols']; $col++)
	{
		$data->raw($row,$col,$sheet_index);
		$sampleKeyValue= array($data->sheets[$sheet_index]['cells'][1][$col] => $data->sheets[$sheet_index]['cells'][$row][$col]);
		$sampleData=array_merge((array)$sampleData, (array)$sampleKeyValue);
	}
		foreach($sampleData as $key=>$val)
		{
			$keys[]=$key;
			preg_match('/\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}/',$val)?strtotime($val): $val;
			$vals[]= (is_null($val)) ? 0 : $val;
			$ki=implode("`,`",$keys);
			$vi=implode("','",$vals);
		}
//	print_r($keys);
	unset($keys);
	unset($vals);
	$sql_query="INSERT INTO SampleDataTest (`$ki`) VALUES ('$vi')";
	echo $sql_query."<hr>";
	$sql_answer=mysql_query($sql_query,$db) or die ("Got errror on line $row. <b>MySQL say: </b>".mysql_error());
}
mysql_close($db);

//echo $data->dump(true,true,$sheet_index);
}//end of main parser block


?>

</body>
</html>
