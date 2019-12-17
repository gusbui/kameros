<?php
// index.php
// jei vartotojas prisijungęs rodomas demonstracinis meniu pagal jo rolę
// jei neprisijungęs - prisijungimo forma per include("login.php");
// toje formoje daugiau galimybių...

session_start();
include("include/functions.php");
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Demo projektas</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
		<style>
#zinutes {
    font-family: "Trebuchet MS", Arial; border-collapse: collapse; width: 70%;
}
#zinutes td {
    border: 1px solid #ddd; padding: 8px;
}
#zinutes tr:nth-child(even){background-color: #f2f2f2;}
#zinutes tr:hover {background-color: #ddd;}
</style>
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
        </td></tr><tr><td> 
<?php
           
    if (!empty($_SESSION['user']))     //Jei vartotojas prisijungęs, valom logino kintamuosius ir rodom meniu
    {                                  // Sesijoje nustatyti kintamieji su reiksmemis is DB
                                       // $_SESSION['user'],$_SESSION['ulevel'],$_SESSION['userid'],$_SESSION['umail']
		
		inisession("part");   //   pavalom prisijungimo etapo kintamuosius
		$_SESSION['prev']="index"; 
        
        include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę
?>
                <div style="text-align: center;color:green">
                    <br><br>
                    <h1>Aktyvios paieškos</h1>
                </div><br>

			<table style="margin: 0px auto;" id="zinutes">

<?php
		//if (($userlevel == $user_roles["Darbuotojas"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
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
	
	$vardas = $_SESSION['user']; 
	$epastas =$_SESSION['umail'];
	//$kam = $_POST['kam'];
	$zinute = $_POST['zinute'];
			$ip=$_SERVER['REMOTE_ADDR'];
	//$sql = "INSERT INTO gustasbuividavicius (vardas, epastas, kam, zinute, data,ip) VALUES ('$vardas', '$epastas','$kam', '$zinute',now(),'$ip')";
    //if (!mysqli_query($dbc, $sql))  die ("Klaida įrašant:" .mysqli_error($dbc));
	}
			//  nuskaityti viska bei spausdinti 
	echo "<tr><td>Nr</td><td>Vardas</td><td>Pavardė</td><td>Data</td><td>Amžius</td><td>Lytis</td></tr>";
	$sql = "SELECT * FROM searches WHERE status = 1";
    $result = mysqli_query($dbc, $sql);
	{while($row = mysqli_fetch_assoc($result))
		{
		if ($row['gender'] == "V") 
        	$gender = "vyras";
		else 
			$gender = "moteris";
		echo "<tr><td>".$row['id']."</td><td>".$row['firstname']."</td><td>".$row['lastname']."</td><td>".$row['date']."</td><td>".$row['age']."</td><td>".$gender."</td></tr>";
		} 
    };
	echo "</table>";
		//}
?>
	
      <?php
          }                
          else {   			 
              
              if (!isset($_SESSION['prev'])) inisession("full");             // nustatom sesijos kintamuju pradines reiksmes 
              else {if ($_SESSION['prev'] != "proclogin") inisession("part"); // nustatom pradines reiksmes formoms
                   }  
   			  // jei ankstesnis puslapis perdavė $_SESSION['message']
				echo "<div align=\"center\">";echo "<font size=\"4\" color=\"#ff0000\">".$_SESSION['message'] . "<br></font>";          
		
                echo "<table class=\"center\"><tr><td>";
          include("include/login.php");                    // prisijungimo forma
                echo "</td></tr></table></div><br>";
           
		  }
?>
            </body>
</html>
