<?php
if(isset($_POST['unm']) && isset($_POST['pwd']) && isset($_POST['note'])){
    $unm = $_POST['unm']; //htmlentities($_POST['unm'], ENT_QUOTES);
	$pwd = $_POST['pwd']; //htmlentities($_POST['pwd'], ENT_QUOTES);
	if(filter_var($unm, FILTER_VALIDATE_EMAIL)){
		$unm = substr($unm, 0, strpos($unm,"@"));
	}
	$note = htmlentities($_POST['note'], ENT_QUOTES);
	date_default_timezone_set('America/New_York');
	echo "test<br>";
	$unq = md5(uniqid(rand()));
	$unq = strtoupper($unq);
	$uuid = substr($unq,0,8)."-".substr($unq,8,4)."-".substr($unq,12,4)."-".substr($unq,16,4)."-".substr($unq,20,12);
	
	$unq = md5(uniqid(rand()));
	$unq = strtoupper($unq);
	$mid = "<".substr($unq,0,8)."-".substr($unq,8,4)."-".substr($unq,12,4)."-".substr($unq,16,4)."-".substr($unq,20,12)."@gmail.com>";
	require_once 'Zend/Mail/Protocol/Imap.php';
	require_once 'Zend/Mail/Storage/Imap.php';
	$imap = new Zend_Mail_Protocol_Imap('imap.gmail.com', '993', true);
	if($imap){
		$suc = $imap->login($unm, $pwd);	
		$msg = "Content-Type: text/html;\r\n\tcharset=utf-8\r\n"
			  ."Content-Transfer-Encoding: 7bit\r\n"
			  ."Subject: $note\r\n"
			  ."From: <$unm@gmail.com>\r\n"
			  ."X-Universally-Unique-Identifier: ".$uuid."\r\n"
			  ."X-Uniform-Type-Identifier: com.apple.mail-note\r\n"
			  ."Message-Id: ".$mid."\r\n"
			  ."Date: ".date("r")."\r\n"
			  ."X-Mail-Created-Date: ".date("r")."\r\n"
			  ."Mime-Version: 1.0 (1.0)\r\n"
			  ."\r\n"
			  .$note
			  ."\r\n";
			  //."<div>more stuff here</div>\r\n";
		$apnd = $imap->append("Notes", $msg, array('\Seen'));	
		if($apnd){
			echo "Note creat success";
		}
		$suc = $imap->logout();
	}
	else{
		echo "could not log in";
	}
}
else{ ?>

<form enctype="multipart/form-data" method="post" action="">
	<label>Gmail Username:<br />
    <input name="unm" type="text" size="30" />
    </label>
    <br />
    <label>Gmail Password:<br />
    <input name="pwd" type="password" size="30" />
    </label>
    <br />
    <label>Note:<br />
    <input type="text" name="note" size="60" /> <br />
    <input type="submit" value="submit" />
    </label>
</form>
	
<?php }
?>