<?php

function recLogs($notes, $parts)
{    
global $dbs;

    if ($notes<>"" and $parts<>"") {
        $log['userID'] = $_SESSION['userID'];
        $log['parts'] = $parts;
        $log['notes'] = $notes;
        $log['recorded'] = date('Y-m-d H:i:s');
        $log['ipid'] = $_SERVER['REMOTE_ADDR'];
        $sql_text = "INSERT INTO `logs` (`userID`,`parts`,`notes`,`recorded`,`ipid`) VALUES (";
        $sql_text .= "'".$log['userID']."', '".$log['parts']."', '".$log['notes']."', '".$log['recorded']."', '".$log['ipid']."')";
		$insert = $dbs->query($sql_text);
        die($sql_text);
    }
}
?>