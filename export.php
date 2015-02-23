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
    $data=make_adif($logs,$_SESSION['project_id']);

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
