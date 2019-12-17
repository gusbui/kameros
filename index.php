<?php
    echo "PHP"
	$dbc=mysqli_connect('gusbuiserveris.database.windows.net','gusbui', 'Passw0rd');
	if(!$dbc){
		die ("Negaliu prisijungti prie MySQL:"	.mysqli_error($dbc));
	}
	else {
		echo "CONNECTION SUCCESS";
	}
?>
<html>
<body>
TEST
</body>
</html>