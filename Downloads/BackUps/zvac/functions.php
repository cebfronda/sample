<?php


// print out the line 2 of the results file and parse out the revision number
function get_revision($results_file) {
    $revision_out = `sed -n '2 p' $results_file`;
    preg_match('/\d+/',$revision_out, $match);

    return $match[0];
}

// print out the line 4 of the results file and parse out the branch
function get_branch($results_file) {
    $branch_out = `sed -n '4 p' $results_file`;
    preg_match('/=>\s*\'(.+)\',/', $branch_out, $match);

    return $match[1];
}

// Get all available test_revision
function get_test_revisions() {
	global $CONFIG;

	$cmd = "ssh $CONFIG[USER]@$CONFIG[HOSTNAME] ls -d1 /home/$CONFIG[USER]/AutoClassifierTest/test-results/[0-9]*/ | grep -v old";
	$out = `$cmd`;
	
    $revisions = array();

    // Parse out the revision number of the directories
    foreach (split("\n",$out) as $dir) {
      preg_match('/\d+/', $dir, $match);
      if(!$match['0']) {
        continue;
      }
      $rev_num = $match['0'];
      array_push($revisions, $rev_num);
    }
    return $revisions;
}

function get_current_jobs() {
	global $CONFIG;

    $out = `ssh $CONFIG[USER]@$CONFIG[HOSTNAME] ps xjf | egrep "autoclassifier-autotest-launch|PID" | grep "\/bin\/bash" | awk '{print $3}' | sort | uniq`;
    $current = array();

    foreach (split("\n",$out) as $pid) {
        preg_match('/\d+/', $pid, $match);
        if(!$match['0']) {
            continue;
        }
        $valid_pid = $match['0'];
        array_push($current, $valid_pid);
    }
    return $current;
}

function get_cmd($pid) {
	global $CONFIG;

    $cmd = `ssh $CONFIG[USER]@$CONFIG[HOSTNAME] ps xjf | egrep "autoclassifier-autotest-launch|PID" | grep "\/bin\/bash" | grep $pid`;

    # strips off path from the command
    $pure_cmd = end(explode("/", $cmd));
    return $pure_cmd;
}

function get_samples() {
	global $CONFIG;

    $cmd= "ls /home/". $CONFIG["USER"]. "/AutoClassifierTest/samples/";
	$out = `$cmd`;
    $samples = array();
    foreach ( split("\n", $out) as $sample ) {
        if(!$sample) {
            break;
        }
        $line_count = `wc -l /home/$CONFIG[USER]/AutoClassifierTest/samples/$sample | awk '{print $1}'`;
        $samples[$sample] = $line_count;
    }
    return $samples;
}
?>
