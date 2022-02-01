<?php
//header('content-type: text/html; charset=utf-8');
$res = [];
if (($file=fopen($_FILES['f']['tmp_name'], 'r')) !== false)
{

	while(( $data = fgetcsv($file, 100, ";") )!==false)
	{
		$res[]=$data;
	}

	fclose($file);
}



include 'config.php';





$connectdb = mysqli_connect($servername, $username, $password, $database);
mysqli_set_charset($connectdb, 'utf8');
if (!$connectdb) {
      die("Connection failed: " . mysqli_connect_error());
}

$file1 = fopen('./csv1.csv','c');


foreach($res as $line)
 {
 	$sql = "wINSERT INTO `spravochnik` (`id`, `Name`) VALUES ('";
    $sql = (mb_substr($sql,1));
    $sql .= (string)$line[0] ."', '" .(string)$line[1] ."');";

    if (!preg_match("#[^а-яА-ЯёЁa-zA-Z0-9-.]+#iu",(string)$line[1], $matches, PREG_OFFSET_CAPTURE)) {

    	if (!mysqli_query($connectdb, $sql)) {

         $sql = "wUPDATE `spravochnik` SET Name='" .(string)$line[1] ."' WHERE id='" .(string)$line[0] ."';";
     	 $sql = (mb_substr($sql,1));
     	 if (!mysqli_query($connectdb, $sql)) {
         }
    	}
	}else {

		 foreach($matches as $matche)
		 {
			$line[2] = 'Error=Недопустимый символ \'' . $matche[0] . '\' в поле Название';
		 }
	}

 	fputcsv($file1, $line, ";");


 }

mysqli_close($connectdb);
fclose($file1);

?>

<body onLoad="javascript:window.location.href = '/csv1.csv';">

