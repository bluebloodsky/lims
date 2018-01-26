#!/usr/bin/perl
use strict;
use warnings FATAL => 'all';
use utf8;
use Data::Dumper;
use Cwd qw/cwd/;
use File::Basename qw();
use File::Spec;
use YAML qw/DumpFile/;


use 5.010;
binmode(STDIN, ':encoding(utf8)');
binmode(STDOUT, ':encoding(utf8)');
binmode(STDERR, ':encoding(utf8)');

say "current dir: ", cwd();
my ($name, $path, $suffix) = File::Basename::fileparse($0);
my $webDir = File::Spec->catdir($path, '../');
say "name: $name, path: $path, suffix: $suffix, webdir: $webDir";

my $frontendDir = File::Spec->catdir($path, '../frontend');
my $appDir = "$frontendDir/src/app";
my $ifDir = "$appDir/pages/interface";
my $serviceDir = "$appDir/shared/service";
my $cmuService = "$serviceDir/cmu.service.ts";
my $cmuGetRe = qr/\.CmuGet<((\w+)(?:\[\])?)>\(['`](.+)['`]/;
my $postArgRe = qr/(\w+)\s*:\s*(\w+)\s*\)\s*:\s*Observable<any>/;
my $cmuPostRe = qr/\.CmuPost\(['`](\w+)['`]\s*,\s*(\w+)/;
my $ifStartRe = qr/export\s*interface\s*(\w+)\s*\{/;
my $ifEndRe = qr/^\s*\}/;
my $subIfRe = qr/\<([A-Z]\w+Interface)\>/;

open(my $o1 , '>', 'tmp.md');
binmode($o1, ':encoding(utf8)');
open(my $fh, "<", $cmuService) or die  "openfile $cmuService failed: $!";
while(<$fh>) {
    chomp;
    given ($_) {
        when (/$cmuGetRe/) {
            my $ifNames = $1;
            my $ifName = $2;
            my $url = $3;
            $ifName =~ s/\$//g;
            print $o1 <<EOF

  /$url:
    get:
     description: ''
     responses:
      200:
        description: ''
        schema:
EOF
            ;
            if($ifName ne $ifNames)
            {
                print $o1 <<EOF
          type: array
          items:
            \$ref: '#/definitions/$ifName'
EOF
            }else {
                print $o1 <<EOF
          \$ref: '#/definitions/$ifName'
EOF
            }
#            $d->{$url}{'GET'} = { IF => $ifName, IS_ARRAY => ($ifName eq $ifNames) };
        }
#        when (/$postArgRe/) {
#            $arg = $1;
#            $postArgIfName = $2;
#        }
#        when (/$cmuPostRe/) {
#            my $url = $1;
#            my $arg1 = $2;
#            $postArgIfName = undef unless ($arg1 eq $arg);
#            $d->{$url}{'POST'} = { IF => $postArgIfName };
#        }

    }
}   #while

exit(0);

my %h = ();
open(my $out , '>', 'tmp.md');
binmode($out, ':encoding(utf8)');
for my $ifFile (<"$ifDir/*.ts">)
{
    open(my $fh, "<", "$ifFile") or die "openfile $ifFile failed: $!";
    my $ifName = undef;
    my $d = {};
    while (<$fh>) {
        chomp;
        if(/$ifStartRe/)
        {
            $ifName = $1;
            print $out <<EOF

  $ifName:
    type: object
    properties:
EOF
        }
        next unless $ifName;
        $ifName=undef if /$ifEndRe/ ;

        my($item, $type) = /(\w+)\s*:\s*(.+);/;
        next unless $item;

        print $out <<EOF
      $item:
        type: $type
EOF

    }

    close($fh);
}
close($out);
exit(0);

DumpFile('ff.yaml', \%h);
exit(0);
#my @files = glob("$serviceDir/*.ts");
#say "files in $serviceDir: ", @files;
#@files = glob("$ifDir/*.ts");
#say "files in $ifDir: ", @files;


my $d = {};
my $arg = undef;
my $postArgIfName = undef;

foreach my $url (keys(%$d)) {
    &MarkdownPrint($fh, $url, $d->{$url});
}

foreach my $ifName (keys(%h)) {
    say $fh qq!#### <a id="#$ifName"></a>$ifName!;
    say $fh qq/```/;
    say $fh qq/{/;
    my $d = $h{$ifName};
    say $fh $_ foreach(@$d);
    say $fh qq/}/;
    say $fh qq/```/;

}
close($fh);
exit(0);

=head
open(my $fh, "<", $cmuService) or die  "openfile $cmuService failed: $!";
while(<$fh>){
    next unless /$cmuGetRe/;

    my $ifNames = $1;
    my $ifName = $2;
    my $url = $3;
    next unless $h{$ifName};

    &MarkdownPrint($url, $h{$ifName});
}



