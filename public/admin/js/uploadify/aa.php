<?php 
	$arrInfo = $_FILES['Filedata'];
	// var_dump($arrInfo);
	$tmpName = $arrInfo['tmp_name'];
	// var_dump($tmpName);
	$realName = $arrInfo['name'];
	// var_dump($realName);
	$ext = explode(".", $realName)[1];
	$baseName = md5(uniqid()).".$ext";

	$baseDir = Date("Y/m/d/",time());
	if (!is_dir($baseDir)) {
		mkdir($baseDir,0,777);
	}
	$filePath = $baseDir.$baseName;

	move_uploaded_file($tmpName, $filePath);
	echo $filePath;
 ?>