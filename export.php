<?php
  include('functions.php');
  checklogin();

  $mysql=mysql_c();

  $sql="SELECT * FROM logs";

  if($_GET['log_export_typ'] == "complete")
  {
    $sql.=" WHERE project_id=".$_SESSION['project_id'];
  } 
  else
  {
    $sql.=" WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id'];
  }

  if($_GET['log_export_incrementell'] == "on")
  {
    $settings=mysql_fragen("SELECT setting_incrementell_export_complete,setting_incrementell_export_operator FROM rel_operators_projects WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id'].";");
    if($_GET['log_export_typ'] == "complete")
    {
      if(is_numeric($settings[0]['setting_incrementell_export_complete']))
      {
	$sql.=" AND time >= ".$settings[0]['setting_incrementell_export_complete'];
      }
    }
    else
    {
      if(is_numeric($settings[0]['setting_incrementell_export_operator']))
      {
	$sql.=" AND time >= ".$settings[0]['setting_incrementell_export_operator'];
      }
    }
  }

  if(!$logs=mysql_fragen($sql))
  {
    ?>
    <script>alert('keine Logs vorhanden!')</script>
    <?
  }
  else
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $bands=mysql_fragen('SELECT * FROM bands;','band_id');
    $operators=mysql_fragen('SELECT operators.* FROM operators INNER JOIN rel_operators_projects WHERE project_id='.$_SESSION['project_id'],"operator_id");

    $data="";
    $data.="ADIF Export by Netlogbook v0.1, conforming to ADIF standard specification V 2.00\n";
    $data.=stringtoadif("Netlogbook","PROGRAMID");
    $data.=stringtoadif("0.1","PROGRAMVERSION");
    $data.="\n<eoh>\n";

    foreach($logs as $log)
    {
      $data.=stringtoadif($log['log_call'],"call");
      $data.=stringtoadif(time_from_timestamp_adif($log['log_time'],"date"),"qso_date");
      $data.=stringtoadif(time_from_timestamp_adif($log['log_time'],"time"),"time_on");
      $data.=stringtoadif($modes[$log['mode_id']]['mode_name'],"mode");
      $data.=stringtoadif($bands[$log['band_id']]['band_name'],"band");
      //$data.=stringtoadif($operators[$log['operator_id']]['operator_call'],"station_callsign");
      $data.=stringtoadif($log['log_freq']/1000,"freq");
      $data.="<eor>\n";
    }

    if($_GET['log_export_incrementell'] == "on")
    {
      if($_GET['log_export_typ'] == "complete")
      {
	mysql_schreib("UPDATE rel_operators_projects SET setting_incrementell_export_complete=".time()." WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id'].";");
      }
      else
      {
	mysql_schreib("UPDATE rel_operators_projects SET setting_incrementell_export_operator=".time()." WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id'].";");
      }
    }

    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=\"export.adif\"");
    header("Content-Length: ". strlen($data));
    echo $data;
  }
?>
