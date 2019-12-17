<?php
// procuseredit.php tikrina paskyros keitimo reikšmes
// įvestas reikšmes išsaugo $_SESSION['xxxx_login']
// jei randa klaidų jas sužymi $_SESSION['xxxx_error']
// jei naujas slaptažodis ir email tinka, pataiso DB, nukreipia į index.php prisijungimui iš naujo
// po klaidų- vel i useredit.php 

session_start(); 
// cia sesijos kontrole
//if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "useredit"))
//{ header("Location: logout.php");exit;}

  include("include/nustatymai.php");
  include("include/functions.php");
  $_SESSION['prev'] = "procuseredit";
  $_SESSION['pass_error']="";
  $_SESSION['mail_error']="";
  $_SESSION['passn_error']="";
  $user=$_SESSION['user'];
  $pass=$_POST['pass'];$_SESSION['pass_login']=$pass;    //senas
  $passn=$_POST['passn'];$_SESSION['passn_login']=$passn;   //naujas
  $mail=$_POST['email']; $_SESSION['mail_login']=$mail; 

  list(,$dbpass)=checkdb($user); //paimam slaptazodzio maisa is DB
	   if (!$dbpass)
	   { echo " DB klaida nuskaitant slaptazodi vartotojui ".$user;
		               exit;}

  if (checkpass($pass,$dbpass))  // senas slaptazodis ivestas geras  
    {$ar_pasnn=checkpass($passn,substr(hash('sha256',$passn),5,32)); // ar geras naujas
	 $ar_mail=checkmail($mail);                        // ar geras epasto laukas
	 if ( $ar_pasnn && $ar_mail)  // lauku reiksmes geros
      { if ($pass != $passn || $mail != $_SESSION['umail'])   // vartotojas kazka keicia
	            {$dbpass=substr(hash('sha256',$passn),5,32);
                 
				 // CONNECTION STRING STARTS
					try {
					$conn = new PDO("sqlsrv:server = tcp:gusbuiserveris.database.windows.net,1433; Database = duombaze", "gusbui", "Passw0rd");
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					}
					catch (PDOException $e) {
						print("Error connecting to SQL Server.");
						die(print_r($e));
					}

					// SQL Server Extension Sample Code:
					$connectionInfo = array("UID" => "gusbui", "pwd" => "Passw0rd", "Database" => "duombaze", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
					$serverName = "tcp:gusbuiserveris.database.windows.net,1433";
					$conn = sqlsrv_connect($serverName, $connectionInfo);
					// CONNECTION STRING ENDS
	
                 $sql = "UPDATE ". TBL_USERS." SET password='$dbpass' ,  email='$mail'   WHERE  username='$user'";
				         if (!mysqli_query($db, $sql)) {
                   echo " DB klaida keiciant slaptazodi ir epasto adresa: " . $sql . "<br>" . mysqli_error($db);
		               exit;}
		            $_SESSION['message']="Paskyra pakeista";
				} else {$_SESSION['message']="Nieko nekeitėt, paskyra nepakeista";}
                $_SESSION['user']="";
            //    session_regenerate_id(true);
      header("Location:index.php");exit;
	  }  // yra kazkokiu klaidu, jos liecia ne galiojanti, o nauja slaptazodi, perrasom
           {$_SESSION['passn_error']=$_SESSION['pass_error'];$_SESSION['pass_error']="";} 
	// jei neteisingas galiojantis, nieko daugiau netikrinom 
    }  
   // taisyti
   $_SESSION['message']="Yra klaidų";
  // session_regenerate_id(true);
   header("Location:useredit.php");exit;
?>
  
  