<?php
  include('functions.php');
  checklogin();
  ini_set('display_errors', '0');
  $table=$_GET['table'];
  $typ=$_GET['typ'];
  $id=$_GET['id'];
  //print "<pre>";
  //print_r($_SESSION);
  //die();

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
  function vergleich2($wert_a, $wert_b)
  {
    $a = $wert_a['count'];
    $b = $wert_b['count'];

    if ($a == $b)
    {
      return 0;
    }
    if($a > $b)
    {
      return -1;
    }
    else
    {
      return +1;
    }
  }
  function ar_sortieren($array)
  {
    usort($array, 'vergleich');
    return $array;
  }

  function ar_sortieren2($array)
  {
    usort($array,'vergleich2');
    return $array;
  }

  $mysql=mysql_c();

  if($table == "session")
  {
    $data_plain=$_SESSION;
  }
  if($table == "locinfo")
  {
    $data_plain=locinfo($_GET['loc']);
  }
  if($table == "deginfo")
  {
    $data_plain=deginfo($_GET['lon'],$_GET['lat']);
  }
  else if($table == "cronjob")
  {
    $data_plain=mysql_fragen("SELECT * FROM cronjob");
  }
  else if(($table == "settings_table_logs") && (is_numeric($_SESSION['project_id'])))
  {
    $data_plain=mysql_fragen("SELECT settings_table_logs FROM rel_operators_projects WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id'].";");
    $data_output=$data_plain[0]['settings_table_logs'];
  }
  else if($table == "settings_op")
  {
    $data_plain=mysql_fragen("SELECT settings FROM rel_operators_projects WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id'].";");
    $data_output=$data_plain[0]['settings'];
  }
  else if($table == "callinfo_aprs")
  {
    $data_plain=aprs_lookup_call($_GET['call']);
  }
  else if($table == "callinfo_qrz")
  {
    $data_plain=qrz_lookup_call($_GET['call']);
  }
  else if($table == "callinfo_last_log")
  {
    $sql="SELECT log_freq,band_id,log_project_call FROM logs WHERE project_id=".$_SESSION['project_id']." AND operator_id=".$_SESSION['operator_id']." ORDER BY log_time DESC LIMIT 1";
    $data_plain=mysql_fragen($sql);
  }
  else if($table == "callinfo_logs")
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
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
    $data_plain['project']=$callinfo_project;
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
    $data_plain['operator']=$callinfo_operator;
    $data_plain['total_project']=mysql_fragen("SELECT COUNT(*) FROM logs log_time WHERE project_id=".$_SESSION['project_id']);
    $data_plain['total_operator']=mysql_fragen("SELECT COUNT(*) FROM logs log_time WHERE operator_id=".$_SESSION['operator_id']." AND project_id=".$_SESSION['project_id']);
  }

  if($table == "dxcluster")
  {
    $data_plain=array();
    /*

    0 Spotter
    1 QRG
    2 DX Call
    3 comment
    4 spot date
    5 lotw (L = true)
    6 eQSL (E = true)
    7 ?
    8 band
    9 QTH
    */
    if(is_numeric($_SESSION['dxcluster_settings']['band_id']))
    {
      $band=mysql_fragen("SELECT band_name FROM bands WHERE band_id='".$_SESSION['dxcluster_settings']['band_id']."';");
      $url="http://www.hamqth.com/dxc_csv.php?limit=20&band=".$band['0']['band_name'];
    }
    else
    {
      $url="http://www.hamqth.com/dxc_csv.php?limit=20";
    } 
    $entrys=file_get_contents($url);
    $entrys=preg_replace('/\n$/','',$entrys); 
    $entrys=preg_split("/\n/",$entrys);
    $i=0;
    foreach($entrys as $entry)
    {
      $entry_a=preg_split("/\^/",$entry);
      $data_c[$i][0]=$entry_a[0];
      $data_c[$i][1]=$entry_a[1];
      $data_c[$i][2]=$entry_a[2];
      $data_c[$i][3]=$entry_a[3];
      $data_c[$i][4]=$entry_a[4];
      $data_c[$i][5]=$entry_a[8];
      $data_c[$i][6]=$entry_a[9];
      $i++;
    }
  }

  if(($table == "logs") && (is_numeric($_SESSION['project_id'])))
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
	  $data_c[$i][3]=$data_temp['log_project_call'];  // eigenes Call
	  $data_c[$i][4]=$operators[$data_temp['operator_id']]['operator_call'];
	  $data_c[$i][5]=$data_temp['log_project_locator'];  // Locator
	  $data_c[$i][6]=$data_temp['log_freq'];
	  $data_c[$i][7]=$modes[$data_temp['mode_id']]['mode_name'];
	  if($modes[$data_temp['mode_id']]['mode_rapport_signal'] == '0')
	  {
	    if($modes[$data_temp['mode_id']]['mode_digital'] == '0')
	    {
	      $data_c[$i][8]=$data_temp['log_rst_tx_0'].$data_temp['log_rst_tx_1'];
	      $data_c[$i][9]=$data_temp['log_rst_rx_0'].$data_temp['log_rst_rx_1'];
	    }
	    else
	    {
	      $data_c[$i][8]=$data_temp['log_rst_tx_0'].$data_temp['log_rst_tx_1'].$data_temp['log_rst_tx_2'];
	      $data_c[$i][9]=$data_temp['log_rst_rx_0'].$data_temp['log_rst_rx_1'].$data_temp['log_rst_rx_2'];
	    }
	  }
	  else
	  {
	    $data_c[$i][8]=$data_temp['log_signal_tx'];
	    $data_c[$i][9]=$data_temp['log_signal_rx'];
	  }
	  $data_c[$i][10]=$data_temp['log_name'];
	  $data_c[$i][11]=$data_temp['log_qth'];
	  $data_c[$i][12]=$data_temp['log_loc'];
	  $data_c[$i][13]=$data_temp['log_dok'];
	  $data_c[$i][14]=$data_temp['log_manager'];
	  $data_c[$i][15]=$data_temp['log_qsl_tx'];
	  $data_c[$i][16]=$data_temp['log_qsl_rx'];
	  $data_c[$i][17]=$data_temp['log_notes'];
	  $data_c[$i][18]=$data_temp['log_id'];
	  $data_c[$i][19]="";
	  $data_c[$i][20]="";
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

  else if(($table == "monitor_total") && (is_numeric($_SESSION['project_id'])))
  {
    if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE project_id=".$_SESSION['project_id'].";"))
    {
      $data_plain=array();
      $count=mysql_result($result,0); 
      $data_c[0][0]=$count;
    }
  }

  else if((($table == "monitor_modes") || ($table == "monitor_bands") || ($table == "monitor_qsos")) && (is_numeric($_SESSION['project_id'])))
  {
    $data_plain=array();
    if($result=mysql_query("SELECT COUNT(*) FROM logs WHERE project_id=".$_SESSION['project_id'].";"))
    {
      $total=mysql_result($result,0); 
    }
    if($table == "monitor_modes")
    {
      $modes=mysql_fragen('select modes.mode_name, count(logs.mode_id) FROM logs LEFT OUTER JOIN modes ON modes.mode_id=logs.mode_id WHERE logs.project_id='.$_SESSION['project_id'].' GROUP BY modes.mode_id ORDER by COUNT(*) DESC LIMIT 6');
      foreach($modes as $mode)
      {
	$percent=round((($mode['count(logs.mode_id)']*100)/$total),1);
	if($percent != 0)
	{
	  $counter[$mode['mode_name']]['percent']=round($percent,1);
	}
      }
    }
    if(($table == "monitor_bands") && (is_numeric($_SESSION['project_id'])))
    {
      $bands=mysql_fragen('select bands.band_name, count(logs.band_id) FROM logs LEFT OUTER JOIN bands ON bands.band_id=logs.band_id WHERE logs.project_id='.$_SESSION['project_id'].' GROUP BY bands.band_id ORDER by COUNT(*) DESC LIMIT 6;');
      foreach($bands as $band)
      {
	$percent=round((($band['count(logs.band_id)']*100)/$total),1);
	if($percent != 0)
	{ 
	  $counter[$band['band_name']]['percent']=$percent;
	}
      }
    }
    if(($table == "monitor_qsos") && (is_numeric($_SESSION['project_id'])))
    {
      $operators=mysql_fragen('select operators.operator_call, count(logs.operator_id) FROM logs LEFT OUTER JOIN operators ON operators.operator_id=logs.operator_id WHERE logs.project_id='.$_SESSION['project_id'].' GROUP BY operators.operator_id ORDER by COUNT(*) DESC LIMIT 6;');
      foreach($operators as $operator)
      {
	$percent=round((($operator['count(logs.operator_id)']*100)/$total),1);
	if($percent != 0)
	{ 
	  $counter[$operator['operator_call']]['percent']=$percent;
	  $counter[$operator['operator_call']]['count']=$operator['count(logs.operator_id)'];
	}
      }
    }

    $i=0;

    foreach($counter as $name => $counts)
    {
      $data_c[$i][0]=$name;
      $data_c[$i][1]=$counts['percent'];
      $data_c[$i][2]=$counts['count'];
      $i++;
    }
  }

  else if(($table == "monitor_logs") && (is_numeric($_SESSION['project_id'])))
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

  else if(($table == "logsfromme") && (is_numeric($_SESSION['project_id'])))
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

  else if(($table == "operators") || ($table == "operators_all"))
  {
    if($table == "operators_all")
    {
      $sql="SELECT * FROM operators";
    }
    else
    {
      $sql="SELECT * FROM operators WHERE operator_id != '0'";
    }
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
  else if(($table == "projects") || ($table == "projects_all"))
  {
    if($table == "projects")
    {
      $sql="SELECT * FROM projects WHERE project_operator='0'";
    }
    else
    {
      $sql="SELECT * FROM projects";
    }
    $i=0;
    $data_plain=mysql_fragen($sql,'project_id',$id);
    if($typ == "datatable")
    {
      foreach($data_plain as $data_temp)
      {
	$data_c[$i][0]=$data_temp['project_short_name'];
	$data_c[$i][1]=$data_temp['project_mode'];
	$data_c[$i][2]=$data_temp['project_call'];
	$data_c[$i][3]=$data_temp['project_id'];
	$data_c[$i][4]="";
	$data_c[$i][5]="";
	$i++;
      }
    }
  }

  else if($table == "rel_project_band")
  {
    if(!is_numeric($id))
    {
      $id=$_SESSION['project_id'];
    }
    $data_plain=mysql_fragen('SELECT bands.* FROM bands INNER JOIN rel_bands_projects ON rel_bands_projects.band_id=bands.band_id WHERE rel_bands_projects.project_id='.$id);
  }
  else if($table == "rel_project_operator")
  {
    if(!is_numeric($id))
    {
      $id=$_SESSION['project_id'];
    }
    $sql="select operators.operator_call, operators.operator_id FROM operators INNER JOIN rel_operators_projects ON rel_operators_projects.operator_id=operators.operator_id WHERE rel_operators_projects.project_id='".$id."'";
    $data_plain=mysql_fragen($sql);
  }
  else if($table == "rel_project_mode")
  {
    if(!is_numeric($id))
    {
      $id=$_SESSION['project_id'];
    }
    $sql="SELECT modes.mode_name,modes.mode_id FROM modes INNER JOIN rel_modes_projects ON rel_modes_projects.mode_id=modes.mode_id WHERE rel_modes_projects.project_id='".$id."'";
    $data_plain=mysql_fragen($sql);
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
    if((!preg_match('/^monitor_.*$/',$table)) && ($table != "dxcluster"))
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
	/*
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
	*/
      }
    }


    if(($table != "dxcluster") && (!preg_match('/^monitor_.*$/',$table)))
    {
      if(($_GET['iSortCol_0'] != '0') && ($_GET['iSortCol_0'] != '3'))
      {
	$key=$_GET['iSortCol_0'];
	$direction=$_GET['sSortDir_0'];
	$data_c=ar_sortieren($data_c);
      }
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
      $output['aaData']=array();
    }
  }
  else
  {
    $output=$data_plain;
  }
  if($data_output)
  {
    echo $data_output;
  }
  else if(json_encode($output))
  {
    echo json_encode($output);
  }
  else
  { 
    firebug_debug("kaputt!");
    firebug_debug($json_errors[json_last_error()]);
    //echo 'Letzter Fehler : ', $json_errors[json_last_error()], PHP_EOL, PHP_EOL;
  }
?>
