<?php

require_once '../lib/req_apertura_sessione.php';

/*
  Copyright (C) 2015 Pietro Tamburrano
  Questo programma è un software libero; potete redistribuirlo e/o modificarlo secondo i termini della
  GNU Affero General Public License come pubblicata
  dalla Free Software Foundation; sia la versione 3,
  sia (a vostra scelta) ogni versione successiva.

  Questo programma é distribuito nella speranza che sia utile
  ma SENZA ALCUNA GARANZIA; senza anche l'implicita garanzia di
  POTER ESSERE VENDUTO o di IDONEITA' A UN PROPOSITO PARTICOLARE.
  Vedere la GNU Affero General Public License per ulteriori dettagli.

  Dovreste aver ricevuto una copia della GNU Affero General Public License
  in questo programma; se non l'avete ricevuta, vedete http://www.gnu.org/licenses/
 */
/* programma per l'inserimento di un amministrativo
  riceve in ingresso i valori del amministrativo */
@require_once("../php-ini" . $_SESSION['suffisso'] . ".php");
@require_once("../lib/funzioni.php");

// istruzioni per tornare alla pagina di login 

$tipoutente = $_SESSION["tipoutente"]; //prende la variabile presente nella sessione
if ($tipoutente == "")
{
    header("location: ../login/login.php?suffisso=" . $_SESSION['suffisso']);
    die;
}

$titolo = "Inserimento amministrativo";
$script = "";
stampa_head($titolo, "", $script, "SDMAP");
stampa_testata("<a href='../login/ele_ges.php'>PAGINA PRINCIPALE</a> - <a href='vis_imp.php'>ELENCO amministrativi</a> - $titolo", "", $_SESSION['nome_scuola'], $_SESSION['comune_scuola']);

$idamministrativo = stringa_html('codice');
$cognome = stringa_html('cognome');
//print $cognome;
$nome = stringa_html('nome');

$aa = stringa_html('datadinasca')!=''?stringa_html('datadinasca'):'0001';
$gg = stringa_html('datadinascg')!=''?stringa_html('datadinascg'):'01';
$mm = stringa_html('datadinascm')!=''?stringa_html('datadinascm'):'01';

$comnasc = stringa_html('idcomn')!=''?stringa_html('idcomn'):'0';
$indirizzo = stringa_html('indirizzo');
$comresi = stringa_html('idcomr')!=''?stringa_html('idcomr'):'0';
$email = stringa_html('email');

$telefono = stringa_html('telefono');
$cellulare = stringa_html('telcel');

$con = mysqli_connect($db_server, $db_user, $db_password, $db_nome);
if (!$con)
{
    print("<H1>connessione al server mysql fallita</H1>");
    exit;
}
$DB = true;
if (!$DB)
{
    print("<H1>connessione al database stage fallita</H1>");
    exit;
}
// $query="insert into tbl_amministrativi (cognome,nome)values ('$cognome','$nome')";
// VERIFICO SE E' IL PRIMO amministrativo, IN QUESTO CASO AGGIUNGO 1000000 ALL'idamministrativo

$que = "select * from tbl_amministrativi where idamministrativo>2000000000";
$res = eseguiQuery($con,$que);
if (mysqli_num_rows($res) == 0)
{
    $idamministrativo = 2000000001;

    $query = "insert into tbl_amministrativi (idamministrativo,cognome,nome,datanascita,idcomnasc,indirizzo,idcomres,telefono,telcel,email,idutente) values ('$idamministrativo','$cognome','$nome','$aa-$mm-$gg','$comnasc','$indirizzo','$comresi','$telefono','$cellulare','$email','$idamministrativo')";
} else
    $query = "insert into tbl_amministrativi (cognome,nome,datanascita,idcomnasc,indirizzo,idcomres,telefono,telcel,email) values ('$cognome','$nome','$aa-$mm-$gg','$comnasc','$indirizzo','$comresi','$telefono','$cellulare','$email')";
$err = 0;
$b = 0;
$flag = 0;
$mes = "";

if ($cognome == "")
{
    $err = 1;
    $mes = "Il cognome non è stato inserito <br/>";
} else
{
    if (controlla_stringa($cognome) == 1)
    {
        $err = 1;
        $mes = "Il cognome non può contenere valori numerici <br/>";
    }
}

if ($nome == "")
{
    $err = 1;
    $mes = $mes . "Il nome non è stato inserito <br/>";
} else
{
    if (controlla_stringa($nome) == 1)
    {
        $err = 1;
        $mes = "Il nome non può contenere valori numerici <br/>";
    }
}
/**
  if (!$datadinascg)
  {
  $err=1;
  $mes=$mes."Il giorno di nascita non � stato inserito <br/>";
  }
  else
  {
  if (is_numeric($datadinascg)==false)
  {
  $err=1;
  $mes=$mes."Il giorno di nascita pu� contenere solo valori numerici <br/>";
  }
  else
  {
  if (($datadinascg<1) or ($datadinascg>31))
  {
  $err=1;
  $mes=$mes." Il giorno di nascita deve essere compreso tra 1 e 31 <br/>";
  }
  else
  {
  if ((($datadinascm==4) or ($datadinascm==6) or ($datadinascm==9) or ($datadinascm==11)) and ($datadinascg>30))
  {
  $err=1;
  $mes=$mes." Il giorno di nascita deve essere compreso tra 1 e 30 <br/>";
  }
  else
  {
  if (($datadinascm==2) and ($datadinascg>29))
  {
  $err=1;
  $mes=$mes." Il giorno di nascita deve essere compreso tra 1 e 29 <br/>";
  }
  }
  }
  }
  }
  if (!$datadinascm)
  {
  $err=1;
  $mes=$mes." Il mese di nascita non � stato inserito <br/>";
  }
  else
  {
  if (is_numeric($datadinascm)==false)
  {
  $err=1;
  $mes=$mes." Il mese di nascita pu� contenere solo valori numerici <br/>";
  }
  else
  {
  if (($datadinascm>12) or ($datadinascm<1))
  {
  $err=1;
  $mes=$mes." Il mese di nascita deve essere compreso tra 1 e 12 <br/>";
  }
  }
  }

  if (!$datadinasca)
  {
  $err=1;
  $mes=$mes." L'anno di nascita non � stato inserito <br/>";
  }
  else
  {
  if (is_numeric($datadinasca)==false)
  {
  $err=1;
  $mes=$mes." L'anno di nascita pu� contenere solo valori numerici <br/>";
  }

  }

  if (!$idcomn)
  {
  $err=1;
  $mes=$mes." Il comune di nascita non � stato inserito <br/>";
  }
  if (!$indirizzo)
  {
  $err=1;
  $mes=$mes." L'indirizzo non � stato inserito <br/>";
  }
  if (!$idcomr)
  {
  $err=1;
  $mes=$mes."Il comune di residenza non � stato inserito <br/>";
  }
  IF (!$telefono)
  {
  $app=1;
  }
  IF (!$telcel)
  {
  $app1=1;
  }
  if (($app==1)and($app1==1))
  {
  $err=1;
  $mes=$mes."Inserire il telefono o il cellulare <br/>";
  }
  else
  {
  if ($app==0)
  {
  if (is_numeric($telefono)==false)
  {
  $err=1;
  $mes=$mes." Il telefono pu� contenere solo valori numerici <br/>";
  }

  }
  if ($app1==0)
  {

  if (is_numeric($telcel)==false)
  {
  $err=1;
  $mes=$mes." Il cellulare pu� contenere solo valori numerici <br/>";
  }
  }
  }
 */
if ($err == 1)
{
    print("<center><font size='3' color='red'><b>Correzioni:</b></font><br/>");
    print("$mes");
    print("<br/><form NAME='hid' action='ins_imp.php' method='POST'>");

    print(" <input type ='hidden' size='20' name='codi' value= '$idamministrativo'>");
    print(" <input type ='hidden' size='20' name='cog' value= '$cognome'>");
    print(" <input type ='hidden' size='20' name='no' value= '$nome'>");

    print(" <input type ='hidden' size='2'maxlength='2' name='datag' value=$gg><input type ='hidden' size='2' maxlength='2'name='datam' value=$mm><input type ='hidden' size='4' maxlength='4'name='dataa' value=$aa>");
    print(" <input type ='hidden' size='20' name='idcomn' value= '$comnasc'>");
    print(" <input type ='hidden' size='20' name='idcomr' value= '$comresi'>");

    print(" <input type ='hidden' size='20' name='ind' value= '$indirizzo'> ");
    print("  <input type ='hidden' size='20' name='tel' value= '$telefono'>");
    print(" <input type ='hidden' size='20' name='telc' value= '$cellulare'>");
    print(" <input type ='hidden' size='20' name='em' value= '$email'>");
    print(" <input type ='hidden' size='20' name='flag' value= '1'>");
    print("<INPUT TYPE='SUBMIT' VALUE='<< Indietro'>");
    print("</form></center>");
} else
{
    $res = eseguiQuery($con, $query);

    if (!$res)
    {
        print("<h2>Il amministrativo non &eacute; stato inserito</h2>$query");
    } else
    {
        $idamministrativoinserito = mysqli_insert_id($con);
        // Aggiorno l'idutente del amministrativo
        $query = "update tbl_amministrativi set idutente=$idamministrativoinserito where idamministrativo=$idamministrativoinserito";
        if (!$res = eseguiQuery($con,$query))
            die("Errore aggiornamento id utente del amministrativo!");
        // INSERISCO ANCHE IL RECORD NELLA TABELLA DEGLI tbl_utenti
        $utente = "amm" . ($idamministrativoinserito - 2000000000);
        $password = creapassword();
        $sqlt = "insert into tbl_utenti(idutente,userid,password,tipo) values ('$idamministrativoinserito','$utente',md5('" . md5($password) . "'),'A')";
        $res = eseguiQuery($con, $sqlt);

        // print "risultato inserimento $idamministrativoinserito<br/>"; 
        print "<FONT SIZE='+2'><CENTER>Inserimento eseguito</CENTER></FONT>";
        print "<p align='center'>Dati di autenticazione per $nome $cognome";
        print "<br/>Utente: $utente<br/>Password:$password </p>";
        print "<br><br><center>";
        print "<form target='_blank' name='stampa' action='stampa_pass_imp.php' method='POST'>
                   <input type='hidden' name='iddoc1' value='$idamministrativoinserito'> 
                   <input type='hidden' name='utdoc1' value='$utente'> 
                   <input type='hidden' name='pwdoc1' value='$password'> 
                   <input type='hidden' name='numpass' value='1'> 
                   
                   <input type='submit' value='STAMPA'>
                   </form>";
        print "</center>";
    }
}

mysqli_close($con);
stampa_piede("");

