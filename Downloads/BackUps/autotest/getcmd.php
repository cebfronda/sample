<?php

print_r($_GET);

/*$CMD = $_GET['branch'] . " " . $_GET['revision'] . " " . $_GET['reason'];
$CMD .= ' -U' if !$_GET['svn_update'];
/*$CMD .= ' -R' if !$_GET['build_repos'];
$CMD .= ' -s' if $_GET['use-latest'];

# Build the 'at' commands
/*$CMD_AT = "ssh ".$TESTCONFIG[USER].i$TESTCONFIG[HOSTNAME]." at now";
if($GET['after_time_value']) [
    $CMD_AT .= " + ".$GET[after_time_value]." ".$GET[after_time];
]

$CMD = "ssh ".$TESTCONFIG[USER]."\."$TESTCONFIG[HOSTNAME]." ".$CMD;   
*/
echo "\n".$CMD;

?>

ssh $TESTCONFIG[USER]\$TESTCONFIG[HOSTNAME] waqa 23761 rerun -U -R -s 
