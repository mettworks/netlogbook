<?php
  include('functions.php');
  checklogin();
  $mysql=mysql_c();

  $sql="SELECT log_time,operator_id,mode_id,log_freq,log_id,log_call,log_loc FROM logs WHERE project_id=".$_SESSION['project_id'];

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

  $modes=mysql_fragen('SELECT * FROM modes;','mode_id');  
  $operators=mysql_fragen('SELECT * FROM operators;','operator_id');  

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
	    print '<marker id="'.$i.'" time="'.date('d.m.Y H:i',$contact['log_time']).'" operator="'.$operators[$contact['operator_id']]['operator_call'].'" call="'.$contact['log_call'].'" mode="'.$modes[$contact['mode_id']]['mode_name'].'" freq="'.$contact['log_freq'].'" lat="'.$deg['lat'].'" lng="'.$deg['lon'].'"/>';
	    $i++;
	  }	      
	}
	else
	{
	  $deg=locator2degree($contact['log_loc']);
	  print '<marker id="'.$i.'" time="'.date('d.m.Y H:i',$contact['log_time']).'" operator="'.$operators[$contact['operator_id']]['operator_call'].'" call="'.$contact['log_call'].'" mode="'.$modes[$contact['mode_id']]['mode_name'].'" freq="'.$contact['log_freq'].'" lat="'.$deg['lat'].'" lng="'.$deg['lon'].'"/>';
	  $i++;
	}
      }
    }
  }
  print '</markers>'
?>
