<?php
// admin.php
// vartotojų įgaliojimų keitimas ir naujo vartotojo registracija, jei leidžia nustatymai
// galima keisti vartotojų roles, tame tarpe uzblokuoti ir/arba juos pašalinti
// sužymėjus pakeitimus į procadmin.php, bus dar perklausta

session_start();
include("include/nustatymai.php");
include("include/functions.php");
// cia sesijos kontrole
if (!isset($_SESSION['prev']) || ($_SESSION['ulevel'] != $user_roles[ADMIN_LEVEL]))   { header("Location: logout.php");exit;}
$_SESSION['prev']="admin";
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Administratoriaus sąsaja</title>
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
			#center {
			  left: 42%;
			}
		</style>
    </head>
    <body>
		
		
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
            </td></tr><tr><td>
		<center><font size="5">Vartotojų registracija, peržiūra ir įgaliojimų keitimas</font></center></td></tr></table> <br>
		<center><b><?php echo $_SESSION['message']; ?></b></center>
		<form name="vartotojai" action="procadmin.php" method="post">
	    <table class="center" style=" width:75%; border-width: 2px; border-style: dotted;">
		         <tr><td width=30%><a href="index.php">[Atgal]</a></td><td width=30%> 
	<?php
		   if ($uregister != "self") echo "<a href=\"register.php\"><b>Registruoti naują vartotoją<b></a><td>";
		   else echo "</td>";
	?>
		   
			<td width="30%">Atlikite reikalingus pakeitimus ir</td><td width="10%"> <input type="submit" value="Vykdyti"></td></tr></table> <br> 
<?php
    
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
	
	$sql = "SELECT username,userlevel,email,timestamp "
            . "FROM " . TBL_USERS . " ORDER BY userlevel DESC,username";
	$result = mysqli_query($db, $sql);
	if (!$result || (mysqli_num_rows($result) < 1))  
			{echo "Klaida skaitant lentelę users"; exit;}
?>
    <table class="center"  border="1" cellspacing="0" cellpadding="3">
    <tr><td><b>Vartotojo vardas</b></td><td><b>Rolė</b></td><td><b>E-paštas</b></td><td><b>Paskutinį kartą aktyvus</b></td><td><b>Šalinti?</b></td></tr>
<?php
        while($row = mysqli_fetch_assoc($result)) 
	{	 
	    $level=$row['userlevel']; 
	  	$user= $row['username'];
	  	$email = $row['email'];
      	$time = date("Y-m-d G:i", strtotime($row['timestamp']));
      	echo "<tr><td>".$user. "</td><td>";
    	echo "<select name=\"role_".$user."\">";
      	$yra=false;
		foreach($user_roles as $x=>$x_value)
  			{echo "<option ";
        	 if ($x_value == $level) {$yra=true;echo "selected ";}
             echo "value=\"".$x_value."\" ";
         	 echo ">".$x."</option>";
        	 }
		if (!$yra)
        {echo "<option selected value=".$level.">Neegzistuoja=".$level."</option>";}
        $UZBLOKUOTAS=UZBLOKUOTAS; echo "<option ";
        if ($level == UZBLOKUOTAS) echo "selected ";
          echo "value=".$UZBLOKUOTAS." ";
        echo ">Užblokuotas</option>";      // papildoma opcija
      echo "</select></td>";
          
      echo "<td>".$email."</td><td>".$time."</td>";
      echo "<td><input type=\"checkbox\" name=\"naikinti_".$user."\">";
   }
?>
        </table>
        <br> <input type="submit" value="Vykdyti">
        </form>
		

<?php
		//if (($userlevel == $user_roles["Darbuotojas"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
$dbc=mysqli_connect('localhost','stud', 'stud','vartvald');
		if(!$dbc){
			die ("Negaliu prisijungti prie MySQL:"	.mysqli_error($dbc));
		}	
		if($_POST !=null){
	$vardas = $_SESSION['user']; 
	$epastas =$_SESSION['umail'];
	//$kam = $_POST['kam'];
	$zinute = $_POST['zinute'];
			$ip=$_SERVER['REMOTE_ADDR'];
	//$sql = "INSERT INTO gustasbuividavicius (vardas, epastas, kam, zinute, data,ip) VALUES ('$vardas', '$epastas','$kam', '$zinute',now(),'$ip')";
    //if (!mysqli_query($dbc, $sql))  die ("Klaida įrašant:" .mysqli_error($dbc));
	}
			//  nuskaityti viska bei spausdinti 
	echo "<center><h2>Statistika</h2></center>";
	echo "<center><h4>Ieškomų asmenų skaičius pagal mėnesį</h4></center>";
	
	$months = [
    	"1"=> null,
    	"2" => null,
		"3" => null,
		"4" => null,
		"5" => null,
		"6" => null,
		"7" => null,
		"8" => null,
		"9" => null,
		"10" => null,
		"11" => null,
		"12" => null,
	];	
		
	for ($x = 1; $x <= 12;$x++) {
    	$sql = "SELECT COUNT(Month(date)) AS gruodis FROM searches WHERE Month(date) = ".$x;
		$result = mysqli_query($dbc, $sql);
		$row = mysqli_fetch_assoc($result);
		$months[$x] = $row['gruodis'];
	}	
		
	echo "<table style=\"margin: 0px auto;\" id=\"zinutes\">";	
	echo "<tr><td>Mėnuo</td><td>Sausis</td><td>Vasaris</td><td>Kovas</td><td>Balandis</td><td>Gegužė</td><td>Birželis</td><td>Liepa</td><td>Rugpjūtis</td><td>Rugsėjis</td><td>Spalis</td><td>Lapkritis</td><td>Gruodis</td></tr>";
	echo "<tr><td>Ieškomų asmenų skaičius</td><td>".$months[1]."</td><td>".$months[2]."</td><td>".$months[3]."</td><td>".$months[4]."</td><td>".$months[5]."</td><td>".$months[6]."</td><td>".$months[7]."</td><td>".$months[8]."</td><td>".$months[9]."</td><td>".$months[10]."</td><td>".$months[11]."</td><td>".$months[12]."</td></tr>";
	echo "</table>";	
	
	echo "<center><h4>Ieškomų asmenų skaičius pagal pastaruosius 10 metų</h4></center>";	
		
	$years = [
    	"1"=> null,
    	"2" => null,
		"3" => null,
		"4" => null,
		"5" => null,
		"6" => null,
		"7" => null,
		"8" => null,
		"9" => null,
		"10" => null,
	];	
		
	for ($x = 1; $x <= 10;$x++) {
    	$sql = "SELECT COUNT(Year(date)) AS metai FROM searches WHERE Year(date) = ".(date("Y")-10+$x);
		$result = mysqli_query($dbc, $sql);
		$row = mysqli_fetch_assoc($result);
		$years[$x] = $row['metai'];
	}	

	echo "<table style=\"margin: 0px auto;\" id=\"zinutes\">";	
	echo "<tr><td>Mėnuo</td><td>".(date("Y")-9)."</td><td>".(date("Y")-8)."</td><td>".(date("Y")-7)."</td><td>".(date("Y")-6)."</td><td>".(date("Y")-5)."</td><td>".(date("Y")-4)."</td><td>".(date("Y")-3)."</td><td>".(date("Y")-2)."</td><td>".(date("Y")-1)."</td><td>".date("Y")."</td></tr>";
	echo "<tr><td>Ieškomų asmenų skaičius</td><td>".$years[1]."</td><td>".$years[2]."</td><td>".$years[3]."</td><td>".$years[4]."</td><td>".$years[5]."</td><td>".$years[6]."</td><td>".$years[7]."</td><td>".$years[8]."</td><td>".$years[9]."</td><td>".$years[10]."</td></tr>";
	echo "</table>";	
?>
	<div style="width: 100%; display: table;" align="center">
		<div style="display: table-row" align="center">
			<div id="piechart" style="display: table-cell;" align="right"></div>
			<div id="piechart2" style="display: table-cell;" align="left"></div>
		</div>
	</div>
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	 <script type="text/javascript">
	// Load google charts
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	// Draw the chart and set the chart values
	function drawChart() {
	  var data = google.visualization.arrayToDataTable([
	  ['Amžius', 'Asmenų skaičius'],
	  ['0-17', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE (age > 0 AND age < 18)")); echo $row['COUNT(id)'];?>],
	  ['18-35', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE (age > 17 AND age < 36)")); echo $row['COUNT(id)'];?>],
	  ['36-53', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE (age > 35 AND age < 54)")); echo $row['COUNT(id)'];?>],
	  ['54-71', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE (age > 53 AND age < 72)")); echo $row['COUNT(id)'];?>],
	  ['72-89', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE (age > 71 AND age < 90)")); echo $row['COUNT(id)'];?>],
	  ['90+', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE (age > 89)")); echo $row['COUNT(id)'];?>]
	]);

	  // Optional; add a title and set the width and height of the chart
	  var options = {'title':'Ieškomi asmenys pagal amžių', 'width':550, 'height':400};

	  // Display the chart inside the <div> element with id="piechart"
	  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	  chart.draw(data, options);
	}
	</script> 	
	
		
	

	 <script type="text/javascript">
	// Load google charts
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	// Draw the chart and set the chart values
	function drawChart() {
	  var data = google.visualization.arrayToDataTable([
	  ['Lytis', 'Asmenų skaičius'],
	  ['Moterys', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE gender = 'M'")); echo $row['COUNT(id)'];?>],
	  ['Vyrai', <?php $row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(id) FROM searches WHERE gender = 'V'")); echo $row['COUNT(id)'];?>]
	]);

	  // Optional; add a title and set the width and height of the chart
	  var options = {'title':'Ieškomi asmenys pagal lytį', 'width':550, 'height':400};

	  // Display the chart inside the <div> element with id="piechart"
	  var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
	  chart.draw(data, options);
	}
	</script>
		
    </body></html>
