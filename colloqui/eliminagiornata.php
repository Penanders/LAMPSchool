<?php
require_once '../lib/req_apertura_sessione.php';
@require_once("../php-ini" . $_SESSION['suffisso'] . ".php");
@require_once("../lib/funzioni.php");

$con = mysqli_connect($db_server, $db_user, $db_password, $db_nome);
$idgiornatacolloqui = stringa_html('idgiornata');

$d = "DELETE FROM tbl_giornatacolloqui WHERE idgiornatacolloqui=$idgiornatacolloqui";

eseguiQuery($con, $d);
$d = "DELETE FROM tbl_colloquiclasse WHERE idgiornatacolloqui=$idgiornatacolloqui";

eseguiQuery($con, $d);
$d = "DELETE FROM tbl_assenzedocenticolloqui WHERE idgiornatacolloqui=$idgiornatacolloqui";

eseguiQuery($con, $d);
header('Location:insgiornatecoll.php');

mysqli_close($con);
