<?php
/*
Uploadify v2.1.2
Release Date: November 4, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$oldfilename = $_FILES['Filedata']['name'];
	$newfilenamearray = explode(".", $_FILES['Filedata']['name']);
	$newfilename = $newfilenamearray[0]."_".time().".".$newfilenamearray[1];
	$targetFile =  str_replace('//','/',$targetPath) . $newfilename;
	
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);
	
	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);
		
		move_uploaded_file($tempFile,$targetFile);
		require_once('../Connections/commreq.php');
		mysql_select_db($database_commreq, $commreq);
		$query_Recordset1 = "INSERT INTO uploads (projectid, originalfilename, newfilename) VALUES (0,'".$oldfilename."','".$newfilename."')";
		$Recordset1 = mysql_query($query_Recordset1, $commreq) or die(mysql_error());
		$uploadid=mysql_insert_id();
		echo $uploadid;
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>