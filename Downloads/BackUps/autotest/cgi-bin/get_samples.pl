#!/usr/bin/perl
use strict;
use warnings;

use Samples qw( get_samples );
use CGI;
use JSON;

print CGI->new->header('application/json');
print encode_json(get_samples());
