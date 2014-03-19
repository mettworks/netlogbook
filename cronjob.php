#!/usr/bin/env php
<?php
  if(isset($_SERVER['HTTP_HOST']))
  {
    die();
  }
  include('functions.php');
  include('settings.php');

  $lockfile='/tmp/cronjobrunning';

  mysql_c();

  if(is_file($lockfile))
  {
    die("Filelocking active\n");
  }
  else
  {
    touch($lockfile);
  }

  global $qrzcom_cachetime;
  global $cachepath;

  $timestamp=time()-$qrzcom_cachetime;

  if($qrz_caches=mysql_fragen("SELECT qrz_call,qrz_cache_id,image FROM qrz_cache WHERE timestamp <= '".$timestamp."'"))
  {
    foreach($qrz_caches as $qrz_cache)
    {
      if(isset($qrz_cache['image']))
      {
	unlink($cachepath.'/qrzcom/'.$qrz_cache['image']); 
      }
      mysql_schreib("DELETE FROM qrz_cache WHERE qrz_cache_id='".$qrz_cache['qrz_cache_id']."'");
    } 
  }
  mysql_schreib("UPDATE cronjob SET lastrun='".time()."' WHERE id='0';");
  unlink($lockfile);
?>
