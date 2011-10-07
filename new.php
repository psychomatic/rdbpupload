<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'Excel/excel_reader2.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding("UTF-8");
$data->read('./files/PMA_Format-Water.xls');
echo "<p>Sheets count in file: ".count($data->sheets).'</p>'; 	
echo $data->dump(false,false);
?>
