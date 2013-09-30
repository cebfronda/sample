<?php require_once("header.php"); ?>
    <form id="job_control" class="appnitro"  method="post" action="cgi-bin/job-control.pl">
        <div class="form_description">
            <h2>Job Controller</h2>
            <p>View and Delete Autotest jobs in queue</p>
        </div>
        <div>
            <div class="current" style ="color:green;">
                <?php
                    $current_jobs = get_current_jobs();
                    if (!empty($current_jobs)) { 
                        foreach ( $current_jobs as $job ) {
                            $cmd = get_cmd($job);
                            echo "<input type=\"checkbox\" name=\"delete_cjobs\" value=\"$job\" />$cmd (ongoing)<br/>\n";
                        }
                    }
                ?>
            </div>
            <div>
                <?php
                    $pending_jobs = array();
                    exec("ssh $CONFIG[USER]@$CONFIG[HOSTNAME] at -l",$pending_jobs);
                    for ($i=0; $i < count($pending_jobs); $i++) {
                        if (strstr($pending_jobs[$i], '=')) {
                            unset($pending_jobs[$i]);
                        }
                    }
                    if (!empty($pending_jobs)) {
                        foreach ($pending_jobs as $jobs) {
                            $job = explode("\t",$jobs);
                            echo "<input type=\"checkbox\" name=\"delete_pjobs\" value=\"$job[0]\" />$job[0]\t$job[1] (pending)<br/>\n";
                        }
                    }
                    if (!empty($current_jobs) || !empty($pending_jobs)) {
                        echo "<p>\n<br/>";
                        echo "<input type=\"hidden\" name=\"to_delete_jobs\" value=\"job_control\" />";
                        echo "<input type=\"submit\" name=\"submit\" value=\"Terminate\" />";
                        echo "</p>";
                    } else {
                        echo "<br/>No pending autotest.<br/><br/>";
                    }
                ?>
            </div>
        </div>
    </form>
<?php require_once('footer.php' ); ?>
