#!/usr/bin/perl

use strict;
use CGI; 
use TestConfig qw(%TESTCONFIG);

sub header {
	print "
		<html>
			<head>
				<title>Terminate Jobs</title>
			</head>
			<body>
				<div>
		";
}

sub footer {
	print "
				</div>
			</body>
		</html>
		";
}

header();
my $query = new CGI;
my @pending_jobs = $query->param("delete_pjobs");
my @current_jobs = $query->param("delete_cjobs");
my $has_job = 0;
if (@current_jobs) {
	$has_job = 1;
	foreach(@current_jobs) {
		my $pid = $_;
		my $path = `ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} ps xjf | egrep "autoclassifier-autotest-launch|PID" | grep "\/bin\/bash" | grep $pid | grep -v grep`;
		print "Deleting $pid... \n";
		$path =~ s/.*\/autoclassifier\-autotest\-launch //;
		$path =~ s/ \-U//;
		$path =~ s/ \-R//;
		$path =~ s/\s+$//;
		$path =~ s/ /\\ /g;
		print "Removing path: $path... \n";
		`ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} kill -9 -$pid`;
		`ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} rm -rf ~/test-queue/$path`;
		print "done! <br/>";
	}
}
if (@pending_jobs) {
	$has_job = 1;
	foreach(@pending_jobs) {
		print "Deleting $_... ";
		`ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} atrm $_`;
		print "done! <br/>";
	}
}
if (!$has_job) {
	print "No jobs to terminate\n";
}

print "<br/>Redirecting.. ";
print "<META HTTP-EQUIV=refresh CONTENT=\"1;URL='http://$TESTCONFIG{USER}.$TESTCONFIG{HOSTNAME}/autotest/job-control.php'\">\n";

footer();
1;
