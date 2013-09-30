#!/usr/bin/perl
use strict;

use utf8;
use Data::Dumper;
use CGI;
use File::Path qw(remove_tree);
use TestConfig qw(%TESTCONFIG);
use Samples qw( get_samples_files_arr );

my $CMD = "/home/$TESTCONFIG{USER}/autoclassifier-autotest-launch ";
my $SVN_PATH = $TESTCONFIG{SVN_PATH};
my $query = CGI->new;
print $query->header('text/html');
my %POST= $query->Vars;


sub footer {
	print '
	</div>
	</body>
	</html>';
} 

sub header {
	print '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Launch Autotest</title>
	</head>
	';
}

sub error {
	my $msg = shift;

	print '
	<div id = "error" style ="color:red;">
	<h4 style ="color:red;">'. $msg. '</h4>
	</div>';
}
sub success {
	my $msg = shift;

	print '
	<div id = "success" style ="color:green;">
	<h4 style ="color:green;">'. $msg. '</h4>
	</div>';
}

sub display {
	my $msg = shift;

	print '
	<div id = "display" style ="color:black;">
	<h5 style ="color:black;">'. $msg. '</h5>
	</div>';
}

sub validate_revision {

	my $branch = $POST{'branch'};
	if($branch ne 'trunk') {
		$branch = "branches/$branch";
	}
	my $cmd = "svn log -r$POST{'revision'} $SVN_PATH$branch \n";
	my @line = `$cmd`;

	return @line;
}

sub get_samples() {
	my $out = `ls /home/$TESTCONFIG{USER}/AutoClassifierTest/samples`;
	my @samples = split("\n", $out);
	return \@samples;
}


header();

# Validating the fields
my @required_fields = qw(branch revision reason);
foreach my $field (@required_fields) {
	if (!$POST{$field}) {
		error("Required entry on $field");
		exit;
	}
}
if ($POST{'revision'} !~ /^\d+$/) {
	error("Revision should be an integer: `$POST{'after_time_value'}`");
	exit;
}
if ($POST{'after_time_value'} !~ /^\d+$/) {
	error("Time should be an integer: `$POST{'after_time_value'}`");
	exit;
}

if($POST{'reason'} =~ m/\s/) {
	error("Reason should not contain whitespace: `$POST{reason}'");
	exit;
}

# If the line output is less than 2, it means no existing revision
if (validate_revision() < 2) {
	error("Revision number does not exist: `$POST{revision}`");
	exit;
}

# Populating the test samples base on the options
remove_tree("/home/$TESTCONFIG{USER}/AutoClassifierTest/ac_test_samples.txt");
my $samples = get_samples_files_arr();
foreach my $sample (@$samples) {
	if( exists $POST{"sites_$sample"} ) {
		system("cat /home/$TESTCONFIG{USER}/AutoClassifierTest/samples/$sample >> /home/$TESTCONFIG{USER}/AutoClassifierTest/ac_test_samples.txt");
	}
}


# Building the command line
$CMD .= $POST{branch} . " " . $POST{revision} . " " . $POST{reason};
$CMD .= ' -U' if !$POST{svn_update};
$CMD .= ' -R' if !$POST{build_repos};
$CMD .= ' -s' if $POST{'use-latest'};

# Build the 'at' commands
my $CMD_AT = "ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} at now";
if($POST{'after_time_value'}) {
	$CMD_AT .= " + $POST{after_time_value} $POST{after_time}";
}

$CMD = "ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} '$CMD'";

system("echo \"$CMD\" | $CMD_AT");

if ($? == -1 ) {
	error("Command failed: $!\n");
} else {
	# Display the list of job queue
	my @jobs = `ssh $TESTCONFIG{USER}\@$TESTCONFIG{HOSTNAME} at -l`;
	success("Job queue successful:");
	foreach (@jobs) {
		display($_);
	}
}
footer();
1;
