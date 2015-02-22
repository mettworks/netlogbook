<?php
  include('functions.php');
  checklogin();
  $mysql=mysql_c();

  $sql="SELECT log_id,log_call,log_loc FROM logs WHERE project_id=".$_SESSION['project_id'];

  if(is_numeric($_SESSION['map_settings']['mode_id']))
  {
    $sql.=" AND mode_id=".$_SESSION['map_settings']['mode_id'];
  }
  if(is_numeric($_SESSION['map_settings']['band_id']))
  {
    $sql.=" AND band_id=".$_SESSION['map_settings']['band_id'];
  }
  if(is_numeric($_SESSION['map_settings']['operator_id']))
  {
    $sql.=" AND operator_id=".$_SESSION['map_settings']['operator_id'];
  }

  header("Content-type: text/xml");
  print '<?xml version="1.0" encoding="UTF-8"?>';
  print '<markers>';

  if($contacts=mysql_fragen($sql,'log_id',$id))
  {
    $i=1;
    foreach($contacts as $contact)
    {
      if($contact['log_loc'] != "")
      {
	if($_SESSION['map_settings']['filter'] == "1")
	{
	  if(preg_match('/^[A-Z0-9]+$/',$contact['log_call']))
	  {
	    $deg=locator2degree($contact['log_loc']);
	    print '<marker id="'.$i.'" name="'.$contact['log_call'].'" lat="'.$deg['lat'].'" lng="'.$deg['lon'].'"/>';
	    $i++;
	  }	      
	}
	else
	{
	  $deg=locator2degree($contact['log_loc']);
	  print '<marker id="'.$i.'" name="'.$contact['log_call'].'" lat="'.$deg['lat'].'" lng="'.$deg['lon'].'"/>';
	  $i++;
	}
      }
    }
  }
  print '</markers>'
?>
