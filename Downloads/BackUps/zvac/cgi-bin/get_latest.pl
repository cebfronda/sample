#!/usr/bin/perl
use strict;
use warnings;
use CGI;
use JSON;
use Data::Dumper;

my $query = CGI->new;
print $query->header('text/plain');
my %POST = $query->Vars;
die 'branch required!' if !$POST{branch};
if ($POST{branch} eq 'trunk') {
	my $revision = `ssh autobuild\@ac-dev svn info svn+ssh://svn/TPTDev/AutoClassifier/trunk | grep 'Last Changed Rev:' | awk '{print \$NF}'`; 
	print $revision;
} else {
	my $revision = `ssh autobuild\@ac-dev svn info svn+ssh://svn/TPTDev/AutoClassifier/branches/$POST{branch}| grep 'Last Changed Rev:' | awk '{print \$NF}'`; 
	print $revision;
}
