<?php
if(isset($_POST['unm']) && isset($_POST['pwd']) && isset($_POST['note'])){
    $unm = $_POST['unm']; //htmlentities($_POST['unm'], ENT_QUOTES);
	$pwd = $_POST['pwd']; //htmlentities($_POST['pwd'], ENT_QUOTES);
	if(filter_var($unm, FILTER_VALIDATE_EMAIL)){
		$unm = substr($unm, 0, strpos($unm,"@"));
	}
	$note = explode("\n",$_POST['note']);
	$i=0;
	$contents="";
	echo count($note);
	if(count($note) > 1){
		while(trim($note[$i]) === "" && $i < count($note)){
			$i++;
			$contents.="<div><br></div>";
		}
		$subj = $note[$i];
		while($i < count($note)){
			
			if(trim($note[$i]) === "")
				$contents.="<div><br></div>";
			else
				$contents.="<div>".$note[$i]."</div>";
			$i++;
		}
	}
	else{
		$contents = $subj = $note[0];
	}
	$note = htmlentities($_POST['note'], ENT_QUOTES);
	date_default_timezone_set('America/New_York');
	$unq = md5(uniqid(rand()));
	$unq = strtoupper($unq);
	$uuid = substr($unq,0,8)."-".substr($unq,8,4)."-".substr($unq,12,4)."-".substr($unq,16,4)."-".substr($unq,20,12);
	
	$unq = md5(uniqid(rand()));
	$unq = strtoupper($unq);
	$mid = "<".substr($unq,0,8)."-".substr($unq,8,4)."-".substr($unq,12,4)."-".substr($unq,16,4)."-".substr($unq,20,12)."@me.com>";
	require_once 'Zend/Mail/Protocol/Imap.php';
	$imap = new Zend_Mail_Protocol_Imap('p01-imap.mail.me.com', '993', true);
	if($imap != NULL && $imap != false){
		$suc = $imap->login($unm, $pwd);
		if($suc != NULL && $suc != false){	
			$msg = "Content-Type: text/html;\r\n\tcharset=utf-8\r\n"
				  ."Content-Transfer-Encoding: 7bit\r\n"
				  ."Subject: $subj\r\n"
				  ."From: <$unm@me.com>\r\n"
				  ."X-Universally-Unique-Identifier: ".$uuid."\r\n"
				  ."X-Uniform-Type-Identifier: com.apple.mail-note\r\n"
				  ."Message-Id: ".$mid."\r\n"
				  ."Date: ".date("r")."\r\n"
				  ."X-Mail-Created-Date: ".date("r")."\r\n"
				  ."Mime-Version: 1.0 (1.0)\r\n"
				  ."\r\n"
				  .$contents
				  ."\r\n";
				  //."<div>more stuff here</div>\r\n";
			$apnd = $imap->append("Notes", $msg, array('\Seen'));	
			if($apnd){
				echo "Note create success<br>";
			}
			$suc = $imap->logout();
		}
		else{
			echo "Incorrect username or password<br>";
		}
	}
	else{
		echo "could not reach server<br>";
	}
	echo "<a href=\"\" >Back</a>";
}
else{ ?>

<form enctype="multipart/form-data" method="post" action="">
	<label>iCloud Username:<br />
    <input name="unm" type="text" size="30" />
    </label>
    <br />
    <label>iCloud Password:<br />
    <input name="pwd" type="password" size="30" />
    </label>
    <br />
    <label>Note:<br />
    <textarea name="note" rows="10" cols="30"></textarea><br />
    <input type="submit" value="submit" />
    </label>
</form>
	
<?php }
?>