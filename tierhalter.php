<html>
<head>
<title>Tierhalter Database</title> <!-- title of tab -->
<link href="xampp.css" rel="stylesheet" type="text/css"> <!-- eventuell css einfügen (href=pfad; rel"definierung von doc"; type="wie css aufgebaut? bzw documentenaufbau"  -->
</head>
<body>
<h1>Tier-Halter-Liste</h1>
<p>Eine sehr einfache Halterverwaltung</p>
<h2>Halter:</h2>
<table border=0 cellpadding=0 cellspacing=0> <!-- setting up the table -->
<tr bgcolor=#f87820> <!-- setting background color -->
<td><img src=img/blank.gif width=10 height=25></td> <!-- leere spalte -->
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>Name</b></td> <!-- spalte nahmens Name in class tabhead mit blank.gif drinnen -->
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>Vorname</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>strasse</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>hausnummer</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>plz</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>ort</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>email</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>tnr</b></td>
<td class=tabhead><img src=img/blank.gif width=150 height=6><br><b>iban</b></td>
<td><img src=img/blank.gif width=10 height=25></td> <!-- leere spalte -->
</tr>
<?php
$db=mysqli_connect('localhost', 'root', '',"tierartztpraxis"); //connection setup ('server_addr', 'user', "database_name")
if (!$db) { //if connection failed
  echo "Fehler: konnte nicht mit MySQL verbinden." . PHP_EOL;
  echo "Debug-Fehlernummer: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debug-Fehlermeldung: " . mysqli_connect_error() . PHP_EOL;
  exit; //exit php scriped
}
if (isset($_GET['name'])) $name=$_GET['name']; //set value from url to php func (name in url wird zu $name in php gesetz sofern vorhanden)
if (isset($_GET['vorname'])) $vorname=$_GET['vorname'];
if (isset($_GET['strasse'])) $strasse=$_GET['strasse'];
if (isset($_GET['hausnummer'])) $hausnummer=$_GET['hausnummer'];
if (isset($_GET['plz'])) $plz=$_GET['plz'];
if (isset($_GET['ort'])) $ort=$_GET['ort'];
if (isset($_GET['email'])) $email=$_GET['email'];
if (isset($_GET['tnr'])) $tnr=$_GET['tnr'];
if (isset($_GET['iban'])) $iban=$_GET['iban'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_GET['nummer'])) $nummer=$_GET['nummer'];
  if($name != "" && ($email != "" || $tnr != "")) { //test ob php variable $name ungleich null, wenn ja (mit und '&&') überprüfung der variablen $email und $tnr ob eine von beiden unglich null ist( mit oder '||' ), wenn nicht go to else
      if(!isset($tnr)) $tnr="NULL"; //wenn $tnr leer -> $tnr = NULL
      if(!isset($iban)) $iban="NULL"; //wenn $iban leer -> $iban = NULL
          if (mysqli_query($db, "INSERT INTO halter (name, vorname, strasse, hausnummer, plz, ort, email, tnr, iban)VALUES (\"$name\", \"$vorname\", \"$strasse\", \"$hausnummer\", \"$plz\", \"$ort\", \"$email\", \"$tnr\", \"$iban\");") === TRUE) { //insert into database with values from php. \ = escape Zeichen damit " nicht als ende von "" gewertet wird sondern als einzelner string
            printf("Row inserted.\n");
          }
} else { 
  echo "Error: no email or tnr specified"; //wird ausgefürt wenn $name und $tnr bzw. $email fehlt
}
if(isset($action)&&$action=="del") {        //test url for action = delet
  if(mysqli_query($db,"DELETE FROM halter where nummer='$nummer';")=== TRUE) { //delet from database
      printf("Raw deleted.\n");
  }
}
$result=mysqli_query($db,"SELECT nummer, name, vorname, strasse, hausnummer, plz, ort, email, tnr, iban FROM halter ORDER BY name;", MYSQLI_USE_RESULT); //get values from database (more simple query: "SELCET * FROM halter ORDER BY name")
$i=0; //setup i variable for if($i>0) in loop
while ($row = mysqli_fetch_assoc($result)) { //loop bis $result nicht mehr gleich mit row ist (maybe $result null?)
  if($i>0) { //test if i größer 0 für statment(ka was das macht da img nicht vorhanden -> Zeilen 60-65 unnötig)
    echo "<tr valign=bottom>"; //neue Zeile in tabelle unten
    echo "<td bgcolor=#ffffff background='img/strichel.gif' colspan=6><img  
    src=img/blank.gif width=1 height=1></td>"; //in Zeile wird strichel.gif dargestellt
    echo "</tr>"; //Zeilenende
  }

  echo "<tr valign=center>"; //neue Zeile in Tabelle mitte, bzw. unter oberster Zeile mit namen
  echo "<td class=tabval><img src=img/blank.gif width=10 height=20></td>"; //Blank in erster Spallte
  echo "<td class=tabval><b>".$row['name']."</b></td>"; //set value from url name zu value in Tabelle in spalte name (nach reinfolge definiert was was ist(siehe reinfolge oben in html tabellen setup))
  echo "<td class=tabval>".$row['vorname']."&nbsp;</td>";
  echo "<td class=tabval>".$row['strasse']."&nbsp;</td>";
  echo "<td class=tabval><b>".$row['hausnummer']."</b></td>";
  echo "<td class=tabval>".$row['plz']."&nbsp;</td>";
  echo "<td class=tabval>".$row['ort']."&nbsp;</td>";
  echo "<td class=tabval><b>".$row['tnr']."</b></td>";
  echo "<td class=tabval>".$row['email']."&nbsp;</td>";
  echo "<td class=tabval>".$row['iban']."&nbsp;</td>";
  echo "<td class=tabval><a onclick=\"return confirm('Sind Sie sicher?');\" 
  href=tierhalter.php?action=del&nummer=".$row['nummer']."><span class=red>[Del]</span></a></td>"; // "del" button für Zeile. In url wird action= del gesetzt sowie nummer= id der Zeile (id in database)
  echo "<td class=tabval></td>"; //Blank in letzter Spallte
  echo "</tr>"; //ende Zeile
  $i++; // i + 1 für komisches gif
}
echo "<tr valign=bottom>"; //neue Zeile in Tabelle unten
echo "<td bgcolor=#fb7922 colspan=11><img src=img/blank.gif width=1 height=8></td>"; //orongener Balken in letzter Spallte von tabelle
echo "</tr>"; //ende Zeile
mysqli_free_result($result); //free memory up (just performence improvements) 
   
mysqli_close($db);  // Close connections 
?> <!-- ende php -->
<form action="tierhalter.php"method="get"> <!-- from für iput-->
<table>
<tr>
  <td>Name</td><td><input name="name"/></td><td>Vorname</td><td><input name="vorname"/></td>
</tr>
<tr>
  <td>Strasse</td><td><input name="strasse"/></td><td>Hausnummer</td><td><input name="hausnummer"/></td>
</tr>
<tr>
  <td>Postleitzahl</td><td><input name="plz"/></td><td>Ort</td><td><input name="ort"/></td>
</tr>
<tr>
  <td>EMail</td><td><input name="email"/></td><td>Telefon Nummer</td><td><input name="tnr"/></td>
</tr>
<tr>
  <td>IBAN</td><td><input name="iban"/></td>
</tr>
<tr>
  <td><button type="submit">ok</td><td><button type="reset">Reset</td> <!--Button ok für submit(setzt werte von From in URL ein); reset Button welcher die From resetet-->
</tr>
</table>
</form>
</body>
</html>
