#!/usr/bin/perl

# benoetigt: libhttp-server-simple-perl und libhamlib-utils

$SIG{INT} = 'IGNORE';
$SIG{INT} = \&interrupt;

my $pid2 = (open(my $fh,"rigctld -m204 -r /dev/ttyS4 -s 38400 |"));
sleep 1;
my $pid;

$| = 1;

sub interrupt {
  kill(15, $pid);
  kill(15, $pid2);
  exit;  # or just about anything else you'd want to do
}

package MyWebServer;

my $state_vox;

use HTTP::Server::Simple::CGI;
use base qw(HTTP::Server::Simple::CGI);
use IO::Socket::INET;

sub rigctl_open{
  $socket = new IO::Socket::INET (
    PeerHost => '127.0.0.1',
    PeerPort => '4532',
    Proto => 'tcp',
  ) or die 'ERROR in Socket Creation : $!\n';
  return $socket;
}

sub rigctl_close{
  $socket->close();
}

my %dispatch = (
  '/get_freq' => \&resp_get_freq,
  '/vox_start' => \&resp_vox_start,
  '/vox_stop' => \&resp_vox_stop,
  '/voice_play' => \&resp_voice_play,
  '/voice_stop' => \&resp_voice_stop,
);

sub handle_request {
  my $self = shift;
  my $cgi  = shift;

  my $path = $cgi->path_info();
  my $handler = $dispatch{$path};

  if (ref($handler) eq "CODE") {
    print "HTTP/1.0 200 OK\r\n";
    $handler->($cgi);
  } else {
    print "HTTP/1.0 404 Not found\r\n";
    print $cgi->header,
    $cgi->start_html('Not found'),
    $cgi->h1('Not found'),
    $cgi->end_html;
  }
}
sub resp_get_freq {
  my $cgi  = shift;   # CGI.pm object
  return if !ref $cgi;
  my $who = $cgi->param('name');
  $socket = rigctl_open();
  $socket->send('f\n');
  $socket->recv($data,1024);

  print $cgi -> header(
    -access_control_allow_origin => '*',
    -type => 'text/plain',
    );
  print $data/1000;
  rigctl_close($socket);
}

sub resp_voice_play {
  my $cgi  = shift;   # CGI.pm object
  return if !ref $cgi;
  my $who = $cgi->param('name');
  $socket = rigctl_open();
  $socket->send("w PB1;\n");
  #$socket->recv($data,1024);
  print $cgi -> header(
    -access_control_allow_origin => '*',
    -type => 'text/plain',
    );
  print "OK";
  rigctl_close($socket);
}
sub resp_voice_stop {
  my $cgi  = shift;   # CGI.pm object
  return if !ref $cgi;
  my $who = $cgi->param('name');
  $socket = rigctl_open();
  $socket->send("w PB0;\n");
  #$socket->recv($data,1024);
  print $cgi -> header(
    -access_control_allow_origin => '*',
    -type => 'text/plain',
    );
  print "OK";
  rigctl_close($socket);
}

sub resp_vox_start {
  my $cgi  = shift;   # CGI.pm object
  return if !ref $cgi;
  my $who = $cgi->param('name');
  $socket = rigctl_open();
  $socket->send("w VX;\n");
  $socket->recv($state_vox,1024);
  if($state_vox =~ /^VX0/)
  {
    $socket->send("w VX1;\n");
  }
  print $cgi -> header(
    -access_control_allow_origin => '*',
    -type => 'text/plain',
    );
  print "OK";
  rigctl_close($socket);
}
sub resp_vox_stop {
  my $cgi  = shift;   # CGI.pm object
  return if !ref $cgi;
  my $who = $cgi->param('name');
  if($state_vox =~ /^VX0/)
  {
    $socket = rigctl_open();
    $socket->send("w VX0;\n");
  }
  print $cgi -> header(
    -access_control_allow_origin => '*',
    -type => 'text/plain',
    );
  print "OK";
  if($state_vox == "VX0")
  {
    rigctl_close($socket);
  }
}

$pid = MyWebServer->new(8080)->background();

while(1)
{
}
