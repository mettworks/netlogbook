#!/usr/bin/env php
<?php

  if($_SERVER['argv'][1] == "dev")
  {
    $_SERVER['APPLICATION']="dev";
  }

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

  if($export_clublogs=mysql_fragen("SELECT project_clublog_auto,project_id,project_clublog_lastrun FROM projects WHERE project_clublog_ena = '1' AND project_clublog_auto != '0'"))
  {
    foreach($export_clublogs as $export_clublog)
    {
      if($export_clublog['project_clublog_auto'] == 1)
      {
	$min='10';
      }
      if(time()-$export_clublog['project_clublog_lastrun'] > $min*60)
      {
	if(export_clublog($export_clublog['project_id']))
	{
	  mysql_schreib("UPDATE projects SET project_clublog_lastrun='".time()."' WHERE project_id='".$export_clublog['project_id']."';");
	}
      }
    }
  }

  $timestamp=time()-$qrzcom_cachetime;

  if($qrz_caches=mysql_fragen("SELECT qrz_call,qrz_cache_id,image FROM qrz_cache WHERE timestamp <= '".$timestamp."'"))
  {
    foreach($qrz_caches as $qrz_cache)
    {
      if(isset($qrz_cache['image']))
      {
	unlink($_SERVER['pwd'].'/cache/qrzcom/'.$qrz_cache['image']); 
      }
      mysql_schreib("DELETE FROM qrz_cache WHERE qrz_cache_id='".$qrz_cache['qrz_cache_id']."'");
    } 
  }
  mysql_schreib("UPDATE cronjob SET lastrun='".time()."' WHERE id='0';");
  unlink($lockfile);
?>
