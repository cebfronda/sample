package TestConfig;
use strict;
use warnings;
use JSON;
use Exporter;
our @ISA = qw( Exporter );
our @EXPORT_OK = qw( 
	%TESTCONFIG
);

our %TESTCONFIG;
open(FILE, "../config.js");
my @str = <FILE>;
close(FILE);
my $conf = decode_json("@str");
%TESTCONFIG = %$conf;

1;
