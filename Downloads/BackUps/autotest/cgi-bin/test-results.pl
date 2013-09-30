#!/usr/bin/perl
use CGI;
use Data::Dumper;
use strict;
use utf8;

my $query = CGI->new;

# Make the sorting consistent with the ones sent through mails.
$ENV{LC_ALL} = 'en_CA.UTF-8'; # Not really needed to assign here

print $query->header('text/plain');
# Store the post variables into a hash
my %POST = $query->Vars;
my $header = "cd ~/ && ./AutoClassifierTest/diff-results.pl ~/AutoClassifierTest/release.test-result ~/AutoClassifierTest/test-results/$POST{revision}/test-result | grep  \"^#\"";
print `$header`;
my $cmd = "cd ~/ && export LC_ALL='$ENV{LC_ALL}' && ./AutoClassifierTest/diff-results.pl -r ~/AutoClassifierTest/release.test-result ~/AutoClassifierTest/test-results/$POST{revision}/test-result | sort -k 2,2 -k 1,1 -k 4 ";
print `$cmd`;
