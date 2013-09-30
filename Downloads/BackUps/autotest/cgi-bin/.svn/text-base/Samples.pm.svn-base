package Samples;

use strict;
use warnings;

our (@ISA, @EXPORT_OK);
BEGIN {
	require Exporter;
	@ISA = qw(Exporter);
	@EXPORT_OK = qw(get_samples get_samples_files_arr );  # symbols to export on request
}

use TestConfig qw(%TESTCONFIG);

sub get_samples {
	my $dir = "/home/$TESTCONFIG{USER}/AutoClassifierTest/samples/";
	chdir($dir);
	my @out = `find | egrep -v '/\\\.svn|\\\.sw'`;
	foreach ( @out ) {
		chomp;
		s/^\.\///o;
	}
	@out = grep($_ && $_ ne '.', @out);

	my %h;
	foreach my $file ( @out ) {
		my @parts = split('/', $file);
		if ( @parts == 1 ) {
			$h{$parts[0]} ||= -d $file ? {} : count_sites($file);
		} elsif ( @parts == 2 ) {
			$h{$parts[0]}->{$parts[1]} ||= -d $file ? {} : count_sites($file);
		} elsif ( @parts == 3 ) {
			$h{$parts[0]}->{$parts[1]}->{$parts[2]} ||= -d $file ? {} : count_sites($file);
		} else {
			die "too deep\n";	
		}
	}
	return \%h;
}

sub count_sites {
	my $file = shift;
	open(FH, "<$file");
	my $cnt = 0;
	$cnt++ while <FH>;
	close(FH);
	return $cnt;
}

sub get_samples_files_arr {
	my $dir = "/home/$TESTCONFIG{USER}/AutoClassifierTest/samples/";
	chdir($dir);
	my @out = `find -type f | grep -vF '/.svn'`;
	foreach ( @out ) {
		chomp;
		s/^\.\///o;
	}
	@out = grep($_ && $_ ne '.', @out);
	return \@out;
}

1;
