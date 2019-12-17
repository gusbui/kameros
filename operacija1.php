<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
     </script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js">
     </script>
<style>
#zinutes {
    font-family: "Trebuchet MS", Arial; border-collapse: collapse; width: 70%;
}
#zinutes td {
    border: 1px solid #ddd; padding: 8px;
}
#zinutes tr:nth-child(even){background-color: #f2f2f2;}
#zinutes tr:hover {background-color: #ddd;}
#center {
  left: 42%;
}
</style>
</head>

<body>
	Atgal į [<a href="index.php">Pradžia</a>]
    <table style="margin: 0px auto;" id="zinutes">
		
<?php
	session_start();
	
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
	
	include("include/nustatymai.php");
	include("include/functions.php");	
	$userlevel=$_SESSION['ulevel'];
	$ip=$_SERVER['REMOTE_ADDR'];
	if($_POST !=null){
		$vardas = $_POST['vardas'];
		$pavarde = $_POST['pavarde'];
		$amzius = $_POST['amzius'];
		$lytis = $_POST['lytis'];
		$dupechecksql = "SELECT * FROM searches WHERE (firstname = '$vardas' AND lastname = '$pavarde')";
		if (mysqli_query($dbc, $dupechecksql)->num_rows === 0 && $amzius > 0) {
			if (($userlevel == $user_roles["Darbuotojas"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
				$sql = "INSERT INTO searches (firstname, lastname, date, status, age, gender) VALUES ('$vardas', '$pavarde',now(),'1', '$amzius', '$lytis')";
			}
			else {
				$sql = "INSERT INTO searches (firstname, lastname, date, status, age, gender) VALUES ('$vardas', '$pavarde',now(),'3', '$amzius', '$lytis')";			
			}
			if (!mysqli_query($dbc, $sql))  die ("Klaida įrašant:" .mysqli_error($dbc));
		}
	}



		//  nuskaityti viska bei spausdinti 
	if (($userlevel == $user_roles["Darbuotojas"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {	
		echo "<center><h3>Ieškomi asmenys</h3></center>";
	echo "<tr><td>Nr</td><td>Vardas</td><td>Pavardė</td><td>Data</td><td>Būsena</td><td>Amžius</td><td>Lytis</td></tr>";
	$sql = "SELECT * FROM searches";
    $result = mysqli_query($dbc, $sql);
	{while($row = mysqli_fetch_assoc($result))
		{
		switch ($row['status']) {
    		case 0:
        		$status = "baigta";
        		break;
    		case 1:
				$status = "vykdoma";
				break;
    		case 2:
				$status = "nutraukta";
				break;
			case 3:
				$status = "nepatvirtinta";
				break;
		}
		if ($row['gender'] == "V") 
        	$gender = "vyras";
		else 
			$gender = "moteris";
		echo "<tr><td>".$row['id']."</td><td>".$row['firstname']."</td><td>".$row['lastname']."</td><td>".$row['date']."</td><td>".$status."</td><td>".$row['age']."</td><td>".$gender."</td><td> 		<button onclick=\"window.location.href = 'http://vartvald/edit.php/?id=".$row['id']."';\">Redaguoti</button>							 </td></tr>";
		} 
    };
	echo "</table>";
	}
?>
<br>
<center><h3>Įveskite ieškomo asmens duomenis</h3></center>		
<div class="container">
  <form method='post'>
     
	   <div class="col-xs-2" id="center">
          <label for="vardas" class="control-label">Vardas:</label>
          <input type="text" name='vardas' class="form-control input-sm">
          <label for="pavarde" class="control-label">Pavardė:</label>
          <input type="text" name='pavarde' class="form-control input-sm">
		  <label for="amzius" class="control-label">Amžius:</label>
          <input type="number" name='amzius' class="form-control input-sm">
		  <label for="lytis" class="control-label">Lytis:</label>
		   
		  <?php
				echo "<select name=\"lytis\" >";
						echo "<option value=\"V\">vyras</option>";
						echo "<option value=\"M\">moteris</option>";
				echo "</select>";
			?> 
		   
		   
		   
		   <br>
         <input type='submit' name='ok' value='siųsti' class="btnbtn-default">
		   
		   <br>
			<?php
				if($_POST !=null){
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
					
					$vardas2 = $_POST['vardas'];
					$pavarde2 = $_POST['pavarde'];
					$dupechecksql2 = "SELECT * FROM searches WHERE (firstname = '$vardas2' AND lastname = '$pavarde2')";
					if (mysqli_query($dbc, $dupechecksql2)->num_rows === 1)
						echo "Įrašyta";
					else if($_POST['amzius'] < 1)
						echo "Amžius negali būti mažesnis nei 0";
					else
						echo "Asmuo jau įvestas";
				}
			?>
		   
     </div>
	  
 </form>
</div>














<?php
// operacija2.php
// tiesiog rodomas  tekstas ir nuoroda atgal

//session_start();

//if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "index"))
//{ header("Location: logout.php");exit;}

?>

<html><!--
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Operacija 2</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td> <center><img src="../include/top.png"></center> </td></tr><tr><td>

      <table style="border-width: 2px; border-style: dotted;"><tr><td>
         Atgal į [<a href="index.php">Pradžia</a>]
      </td></tr></table><br>
			
		<div style="text-align: center;color:green"> <br><br>
            <h1>Operacija 2.</h1>
			Tuščias puslapis, tik nuoroda į pradžią. 
        </div><br>
-->