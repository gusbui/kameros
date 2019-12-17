<?php
// meniu.php  rodomas meniu pagal vartotojo rolę

if (!isset($_SESSION)) { header("Location: logout.php");exit;}
include("include/nustatymai.php");
$user=$_SESSION['user'];
$userlevel=$_SESSION['ulevel'];
$role="";
{foreach($user_roles as $x=>$x_value)
			      {if ($x_value == $userlevel) $role=$x;}
} 

     echo "<table width=100% border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"meniu\">";
        echo "<tr><td>";
        echo "Prisijungęs vartotojas: <b>".$user."</b>     Rolė: <b>".$role."</b> <br>";
        echo "</td></tr><tr><td>";
        if ($_SESSION['user'] != "guest") echo "[<a href=\"useredit.php\">Redaguoti paskyrą</a>] &nbsp;&nbsp;";
        if ($userlevel == $user_roles["Darbuotojas"] || $userlevel == $user_roles[ADMIN_LEVEL]){
			echo "[<a href=\"operacija1.php\">Visos paieškos</a>] &nbsp;&nbsp;";
		}
		else if ($_SESSION['user'] != "guest")	{
			echo "[<a href=\"operacija1.php\">Registruoti ieškoma asmenį</a>] &nbsp;&nbsp;";
		}
		//if ($userlevel == $user_roles["Darbuotojas"]) {
        //	echo "[<a href=\"operacija2.php\">Redaguoti/patvirtinti ieškomus asmenis(Demo operacija2)</a>] &nbsp;&nbsp;";
		//}
	//Trečia operacija tik rodoma pasirinktu kategoriju vartotojams, pvz.:
        //if (($userlevel == $user_roles["Dėstytojas"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
        //    echo "[<a href=\"operacija3.php\">Demo operacija3</a>] &nbsp;&nbsp;";
       	//	}   
		//if (($userlevel == $user_roles["Dalyvis"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
            //echo "[<a href=\"operacija3.php\">Demo operacija3</a>] &nbsp;&nbsp;";
       		//} 
        //Administratoriaus sąsaja rodoma tik administratoriui
        if ($userlevel == $user_roles[ADMIN_LEVEL] ) {
            echo "[<a href=\"admin.php\">Administratoriaus sąsaja</a>] &nbsp;&nbsp;";
        }
        echo "[<a href=\"logout.php\">Atsijungti</a>]";
      echo "</td></tr></table>";
?>       
    
 