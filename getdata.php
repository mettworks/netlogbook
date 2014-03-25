<?php
  include('functions.php');
  checklogin();
  ini_set('display_errors', '0');
  $table=$_GET['table'];
  $typ=$_GET['typ'];
  $id=$_GET['id'];
  //print "<pre>";

  // http://www.phpbar.de/w/Multidimensionales_Array_sortieren
  // Vergleichsfunktion
  function vergleich($wert_a, $wert_b)
  {
    global $key;
    global $direction;
    // Sortierung nach dem zweiten Wert des Array (Index: 1)
    $a = $wert_a[$key];
    $b = $wert_b[$key];

    if ($a == $b)
    {
      return 0;
    }
    if($direction == "asc")
    {
      return ($a > $b) ? -1 : +1;
    }
    else
    {
      return ($a < $b) ? -1 : +1;
    } 
 }

  function ar_sortieren($array)
  {
    usort($array, 'vergleich');
    return $array;
  }

  $mysql=mysql_c();

  if($table == "locinfo")
  {
    $data_plain=locinfo($_GET['loc']);
  }
  else if($table == "cronjob")
  {
    $data_plain=mysql_fragen("SELECT * FROM cronjob");
  }

  else if($table == "callinfo")
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $data_plain['qrzcom']=qrz_lookup_call($_GET['call']);
    $i=0;
    if($temp=mysql_fragen("SELECT * FROM logs log_time WHERE log_call='".$_GET['call']."' AND project_id=".$_SESSION['project_id']." ORDER BY log_time DESC"))
    foreach($temp as $data_temp)
    {
      $callinfo_project[$i]['log_call']=$data_temp['log_call'];
      $callinfo_project[$i]['log_time']=time_from_timestamp($data_temp['log_time'],"date");
      $callinfo_project[$i]['log_freq']=$data_temp['log_freq'];
      $callinfo_project[$i]['mode_name']=$modes[$data_temp['mode_id']]['mode_name'];
      $i++;
    }
    $data_plain['callinfo_project']=$callinfo_project;
    $i=0;
    if($temp=mysql_fragen("SELECT * FROM logs WHERE log_call='".$_GET['call']."' AND operator_id=".$_SESSION['operator_id']." AND project_id=".$_SESSION['project_id']." ORDER BY log_time DESC"))
    foreach($temp as $data_temp)
    {
      $callinfo_operator[$i]['log_call']=$data_temp['log_call'];
      $callinfo_operator[$i]['log_time']=time_from_timestamp($data_temp['log_time'],"date");
      $callinfo_operator[$i]['log_freq']=$data_temp['log_freq'];
      $callinfo_operator[$i]['mode_name']=$modes[$data_temp['mode_id']]['mode_name'];
      $i++;
    }
    $data_plain['callinfo_operator']=$callinfo_operator;
    $data_plain['callinfo_total_project']=mysql_fragen("SELECT COUNT(*) FROM logs log_time WHERE project_id=".$_SESSION['project_id']);
    $data_plain['callinfo_total_operator']=mysql_fragen("SELECT COUNT(*) FROM logs log_time WHERE operator_id=".$_SESSION['operator_id']." AND project_id=".$_SESSION['project_id']);
  }

  if($table == "logs")
  {
    $operators=mysql_fragen('SELECT operators.* FROM operators INNER JOIN rel_operators_projects WHERE project_id='.$_SESSION['project_id'],"operator_id");
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $sql="SELECT * FROM logs WHERE project_id=".$_SESSION['project_id'];
    if($_SESSION['onlyoperator'] == "1")
    {
      $sql.=" AND operator_id=".$_SESSION['operator_id'];
    }
    $i=0;
    if($data_plain=mysql_fragen($sql,'log_id',$id))
    {
      if($typ == "datatable")
      {
	foreach($data_plain as $data_temp)
	{
	  $data_c[$i][0]=$data_temp['log_time'];
	  $data_c[$i][1]="";
	  $data_c[$i][2]=$data_temp['log_call'];
	  $data_c[$i][3]=$data_temp['log_freq'];
	  $data_c[$i][4]=$modes[$data_temp['mode_id']]['mode_name'];
	  $data_c[$i][5]=$data_temp['log_rst_tx_0'].$data_temp['log_rst_tx_1'].$data_temp['log_rst_tx_2'];
	  $data_c[$i][6]=$data_temp['log_rst_rx_0'].$data_temp['log_rst_rx_1'].$data_temp['log_rst_rx_2'];
	  $data_c[$i][7]=$data_temp['log_name'];
	  $data_c[$i][8]=$data_temp['log_qth'];
	  $data_c[$i][9]=$data_temp['log_loc'];
	  $data_c[$i][10]=$data_temp['log_dok'];
	  $data_c[$i][11]=$data_temp['log_manager'];
          $data_c[$i][12]=$operators[$data_temp['operator_id']]['operator_call'];
	  $data_c[$i][13]=$data_temp['log_notes'];
	  $data_c[$i][14]=$data_temp['log_id'];
	  $data_c[$i][15]="";
	  $data_c[$i][16]="";
	  $i++;
	}
      }
      else
      {
	foreach($data_plain as $id => $data_temp)
	{
	  $timestamp=$data_temp['log_time'];
	  $data_temp['log_time_hr_date']=time_from_timestamp($timestamp,"date");
	  $data_temp['log_time_hr_time']=time_from_timestamp($timestamp,"time");
	  $data_plain[$id]=$data_temp;
	}
      }
    }
  }

  else if($table == "monitor_total")
  {
    if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE project_id=".$_SESSION['project_id'].";"))
    {
      $data_plain=array();
      $count=mysql_result($result,0); 
      $data_c[0][0]=$count;
    }
  }

  else if(($table == "monitor_modes") || ($table == "monitor_bands") || ($table == "monitor_qsos"))
  {
    $data_plain=array();
    if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE project_id=".$_SESSION['project_id'].";"))
    {
      $total=mysql_result($result,0); 
    }
    if($table == "monitor_modes")
    {
      $modes=mysql_fragen('SELECT modes.mode_name,modes.mode_id FROM modes INNER JOIN rel_modes_projects ON rel_modes_projects.mode_id=modes.mode_id WHERE rel_modes_projects.project_id='.$_SESSION['project_id'],'mode_id'); 
      foreach($modes as $mode)
      {
	if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE mode_id=".$mode['mode_id'].";"))
	{
	  $count=mysql_result($result,0); 
	  $percent=round((($count*100)/$total),1);
	  if($percent != 0)
	  {
	    $counter[$mode['mode_name']]=round($percent,1);
	  }
	}
      }
    }
    if($table == "monitor_bands")
    {
      $bands=mysql_fragen('SELECT bands.band_name,bands.band_id FROM bands INNER JOIN rel_bands_projects ON rel_bands_projects.band_id=bands.band_id WHERE rel_bands_projects.project_id='.$_SESSION['project_id'],'band_id'); 
      foreach($bands as $band)
      {
	if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE band_id=".$band['band_id'].";"))
	{
	  $count=mysql_result($result,0); 
	  $percent=round((($count*100)/$total),1);
	  if($percent != 0)
	  { 
	    $counter[$band['band_name']]=$percent;
	  }
	}
      }
    }
    if($table == "monitor_qsos")
    {
      $operators=mysql_fragen('SELECT operators.operator_call,operators.operator_id FROM operators INNER JOIN rel_operators_projects ON rel_operators_projects.operator_id=operators.operator_id WHERE rel_operators_projects.project_id='.$_SESSION['project_id'],'operator_id');
      foreach($operators as $operator)
      {
	if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE operator_id=".$operator['operator_id'].";"))
	{
	  $count=mysql_result($result,0); 
	  $percent=round((($count*100)/$total),1);
	  if($percent != 0)
	  { 
	    $counter[$operator['operator_call']]=$percent;
	  }
	}
      }

    }

    $counter=array_slice($counter,'0','5');
    asort($counter);
    $i=0;
    foreach($counter as $name => $counts)
    {
      $data_c[$i][0]=$name;
      $data_c[$i][1]=$counts;
      $i++;
    }
  }

  else if($table == "monitor_logs")
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $operators=mysql_fragen('SELECT operators.* FROM operators INNER JOIN rel_operators_projects WHERE project_id='.$_SESSION['project_id'],"operator_id");
    $sql="SELECT * FROM logs WHERE project_id=".$_SESSION['project_id']." ORDER BY log_time DESC LIMIT 5";
    $i=0;
    if($data_plain=mysql_fragen($sql,'log_id',$id))
    {
      asort($data_plain);
      if($typ == "datatable")
      {
	foreach($data_plain as $data_temp)
	{
          $data_c[$i][0]=$data_temp['log_call'];
          $data_c[$i][1]=$data_temp['log_freq'];
          $data_c[$i][2]=$modes[$data_temp['mode_id']]['mode_name'];
          $data_c[$i][3]=$data_temp['log_qth'];
          $data_c[$i][4]=$operators[$data_temp['operator_id']]['operator_call'];
	  $i++;
	}
      }
    }
  }

  else if($table == "logsfromme")
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $sql="SELECT * FROM logs WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id']." ORDER BY log_time DESC LIMIT 5";
    $i=0;
    if($data_plain=mysql_fragen($sql,'log_id',$id))
    {
      if($typ == "datatable")
      {
	foreach($data_plain as $data_temp)
	{
          $data_c[$i][0]=$data_temp['log_time'];
          $data_c[$i][1]="";
          $data_c[$i][2]=$data_temp['log_call'];
          $data_c[$i][3]=$data_temp['log_freq'];
          $data_c[$i][4]=$modes[$data_temp['mode_id']]['mode_name'];
          $data_c[$i][5]=$data_temp['log_rst_tx_0'].$data_temp['log_rst_tx_1'].$data_temp['log_rst_tx_2'];
          $data_c[$i][6]=$data_temp['log_rst_rx_0'].$data_temp['log_rst_rx_1'].$data_temp['log_rst_rx_2'];
          $data_c[$i][7]=$data_temp['log_name'];
          $data_c[$i][8]=$data_temp['log_qth'];
          $data_c[$i][9]=$data_temp['log_loc'];
          $data_c[$i][10]=$data_temp['log_dok'];
          $data_c[$i][11]=$data_temp['log_notes'];
          //$data_c[$i][12]=$data_temp['log_manager'];
	  $i++;
	}
      }
    }
  }
  else if($table == "logsfromall")
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $operators=mysql_fragen('SELECT operators.* FROM operators INNER JOIN rel_operators_projects WHERE project_id='.$_SESSION['project_id'],"operator_id");
    // TODO SORT!
    $sql="SELECT * FROM logs WHERE project_id=".$_SESSION['project_id']." ORDER BY log_time DESC LIMIT 5";
    $i=0;
    if($data_plain=mysql_fragen($sql,'log_id',$id))
    {
      if($typ == "datatable")
      {
	foreach($data_plain as $data_temp)
	{
          $data_c[$i][0]=$data_temp['log_time'];
          $data_c[$i][1]="";
          $data_c[$i][2]=$data_temp['log_call'];
          $data_c[$i][3]=$data_temp['log_freq'];
          $data_c[$i][4]=$modes[$data_temp['mode_id']]['mode_name'];
          $data_c[$i][5]=$data_temp['log_rst_tx_0'].$data_temp['log_rst_tx_1'].$data_temp['log_rst_tx_2'];
          $data_c[$i][6]=$data_temp['log_rst_rx_0'].$data_temp['log_rst_rx_1'].$data_temp['log_rst_rx_2'];
          $data_c[$i][7]=$data_temp['log_name'];
          $data_c[$i][8]=$data_temp['log_qth'];
          $data_c[$i][9]=$data_temp['log_loc'];
          $data_c[$i][10]=$data_temp['log_dok'];
          $data_c[$i][11]=$data_temp['log_notes'];
          $data_c[$i][12]=$operators[$data_temp['operator_id']]['operator_call'];
	  $i++;
	}
      }
    }
  }

  else if($table == "operators")
  {
    $sql="SELECT * FROM operators";
    $i=0;
    $data_plain=mysql_fragen($sql,'operator_id',$id);
    if($typ == "datatable")
    {
      foreach($data_plain as $data_temp)
      {
	$data_c[$i][0]=$data_temp['operator_call'];
	$data_c[$i][1]=$data_temp['operator_id'];
	$data_c[$i][2]="";
	$data_c[$i][3]="";
	$i++;
      }
    }
  }
  else if($table == "projects")
  {
    $sql="SELECT * FROM projects";
    $i=0;
    $data_plain=mysql_fragen($sql,'project_id',$id);
    if($typ == "datatable")
    {
      foreach($data_plain as $data_temp)
      {
	$data_c[$i][0]=$data_temp['project_short_name'];
	$data_c[$i][1]=$data_temp['project_id'];
	$data_c[$i][2]="";
	$data_c[$i][3]="";
	$i++;
      }
    }
  }

  else if($table == "rel_project_band")
  {
    $data_plain=mysql_fragen('SELECT bands.* FROM bands INNER JOIN rel_bands_projects ON rel_bands_projects.band_id=bands.band_id WHERE rel_bands_projects.project_id='.$id);
  }
  else if($table == "rel_project_operator")
  {
    $data_plain=mysql_fragen('SELECT operator_id FROM rel_operators_projects WHERE project_id='.$id);
  }
  else if($table == "rel_project_mode")
  {
    $data_plain=mysql_fragen('SELECT modes.mode_name,modes.mode_id FROM modes INNER JOIN rel_modes_projects ON rel_modes_projects.mode_id=modes.mode_id WHERE rel_modes_projects.project_id='.$_SESSION['project_id']);
  }
  else if($table == "rel_log_mode")
  {
    $data_plain=mysql_fragen('SELECT mode_id FROM logs WHERE log_id='.$id);
  } 
  else if($table == "modes")
  {
    $data_plain=mysql_fragen('SELECT * from modes','mode_id',$id);
  }
  else if($table == "bands")
  {
    $data_plain=mysql_fragen('SELECT * from bands','band_id',$id);
  }
  else if($table == "log_last")
  {
    $data_plain=mysql_fragen('SELECT * from logs WHERE operator_id='.$_SESSION['operator_id'].' AND project_id='.$_SESSION['project_id'].' ORDER BY log_time DESC LIMIT 1');
  }
  else if($table == "settings")
  {
    $data_plain=mysql_fragen('SELECT * FROM rel_operators_projects WHERE operator_id='.$_SESSION['operator_id'].' AND project_id='.$_SESSION['project_id']);
  }

  if(!is_array($data_plain))
  {
    $data_c=array(); 
  }
 
  if($typ == "datatable") 
  {
    if(!preg_match('/^monitor_.*$/',$table))
    {
      // TODO: ahh... bloed...
      if(($_GET['iSortCol_0'] == '0') || ($_GET['iSortCol_0'] == '3'))
      {
	$key=$_GET['iSortCol_0'];
	$direction=$_GET['sSortDir_0'];
	$data_c=ar_sortieren($data_c); 
      }
      if(!isset($_GET['iSortCol_0']))
      {
	$key=0;
	$data_c=ar_sortieren($data_c);
      }
    }

    //
    // After Sorting, there should be some translations...
    if(($table == "logs") || ($table == "logsfromme") || ($table == "logsfromall"))
    {
      foreach($data_c as $data_c_id => $temp)
      {
        //$data_c[$i][0]=time_from_timestamp($data_temp['log_time'],"date");
        //$data_c[$i][1]=time_from_timestamp($data_temp['log_time'],"time");
	$timestamp=$data_c[$data_c_id][0];
	$data_c[$data_c_id][0]=time_from_timestamp($timestamp,"date");
	$data_c[$data_c_id][1]=time_from_timestamp($timestamp,"time");
	$freq=$data_c[$data_c_id][3];
	if($freq > 1000)
	{
	  $freq=($freq/1000)."Mhz";
	}
	else if($freq > 1000000)
	{
	  $freq=($freq/1000000)."Ghz";
	}
	else
	{
	  $freq=$freq."khz";
	}
	$data_c[$data_c_id][3]=preg_replace('/\./',',',$freq);
      }
    }
    if(($_GET['iSortCol_0'] != '0') && ($_GET['iSortCol_0'] != '3'))
    {
      $key=$_GET['iSortCol_0'];
      $direction=$_GET['sSortDir_0'];
      $data_c=ar_sortieren($data_c);
    }
    $count_total=count($data_c);

    // 
    // Searching
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
      $z=0;
      foreach($data_c as $id_temp => $data_temp)
      {
	foreach($data_temp as $value)
	{
	  if(preg_match('/^.*'.$_GET['sSearch'].'/i',$value))
	  {
	    $data_s[$z]=$data_c[$id_temp];
	    $z++;
	    break;
	  }
	}
      }
      $data_c=$data_s;
      $count_total=count($data_c);
    }

    //
    // Pagination
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1' )
    {
      $z=0;
      // start
      $i=$_GET['iDisplayStart'];
      // end
      $e=$_GET['iDisplayStart']+$_GET['iDisplayLength'];
      while($i < $e)
      {
	if(is_array($data_c[$i]))
	{
	  $data[$z]=$data_c[$i];
	  $z++;
	}
	$i++;
      } 
      $count_display=$count_total;
    }
    else
    {
      $data=$data_c;
    }
    $output = array(
      "sEcho" => intval($_GET['sEcho']),
      "iTotalRecords" => $count_total,
      "iTotalDisplayRecords" => $count_display,
      );
    if(is_array($data))
    {
      $output['aaData']=$data;
    }
    else
    {
      $output['aaData']="";
    }
  }
  else
  {
    $output=$data_plain;
  }
  echo json_encode($output);
?>
