<html>
<head>
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
	Atgal į [<a href="http://vartvald/operacija1.php">Pradžia</a>]
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
	
	$id=$_GET['id'];
	if($_POST !=null){
		$vardas = $_POST['vardas'];
		$pavarde = $_POST['pavarde'];
		$status = $_POST['status'];
		$age = $_POST['amzius'];
		$gender = $_POST['lytis'];
		include("include/nustatymai.php");
		$userlevel=$_SESSION['ulevel'];
				$ip=$_SERVER['REMOTE_ADDR'];
		$sql = "UPDATE searches SET firstname = '$vardas', lastname ='$pavarde', status='$status', age='$age', gender='$gender' WHERE id='$id'";
		if (!mysqli_query($dbc, $sql))  die ("Klaida įrašant:" .mysqli_error($dbc));
		
	}
	?>

	<div class="container">
		<form method='post'>
			<div class="col-xs-2" id="center">
								
				<?php
				$sql = "SELECT firstname, lastname, status, age, gender FROM searches WHERE id='".$id."'";
				$result =  mysqli_query($dbc, $sql);
				$row = mysqli_fetch_assoc($result);
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
				$status = $row['status'];
				$age = $row['age'];
				$gender = $row['gender'];
				?>
				
				<label for="vardas" class="control-label">Vardas:</label>
				<?php
				echo "<input type=\"text\" name='vardas' class=\"form-control input-sm\" value=".$firstname.">"
				?>
				<label for="pavarde" class="control-label">Pavardė:</label>
				<?php
				echo "<input type=\"text\" name='pavarde' class=\"form-control input-sm\" value=".$lastname.">"
				?>			
				<label for="status" class="control-label">Paieškos būsena:</label>				
				<?php
				echo "<select name=\"status\" >";
						if ($status == 0)
							echo "<option value=\"0\" selected>baigta</option>";
						else
							echo "<option value=\"0\">baigta</option>";
						if ($status == 1)
							echo "<option value=\"1\" selected>vykdoma</option>";
						else
							echo "<option value=\"1\">vykdoma</option>";
						if ($status == 2)
							echo "<option value=\"2\" selected>nutraukta</option>";
						else
							echo "<option value=\"2\">nutraukta</option>";
						if ($status == 3)
							echo "<option value=\"3\" selected>nepatvirtinta</option>";
						else
							echo "<option value=\"3\">nepatvirtinta</option>";
				echo "</select>";
				?>
				
				
				
				<label for="amzius" class="control-label">Amžius:</label>
				<?php
				echo "<input type=\"text\" name='amzius' class=\"form-control input-sm\" value=".$age.">"
				?>
				
				<label for="lytis" class="control-label">Lytis:</label>	
				<br>
				<?php
				echo "<select name=\"lytis\" >";
						if ($gender == "V")
							echo "<option value=\"V\" selected>vyras</option>";
						else
							echo "<option value=\"V\">vyras</option>";
						if ($gender == "M")
							echo "<option value=\"M\" selected>moteris</option>";
						else
							echo "<option value=\"M\">moteris</option>";
				echo "</select>";
				?>
				
				<br><br>
				<input type='submit' name='ok' value='siųsti' class="btnbtn-default">
				<br>
				<?php
				if($_POST !=null)
				echo "Įrašyta";

				?>
			</div>
		</form>
	</div>
</body>
</html>