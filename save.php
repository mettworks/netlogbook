<?
  include('functions.php');

  ini_set('display_errors','0');

  checklogin();
  mysql_c();
  
  $data_temp=$_GET;
  $typ=$data_temp['typ'];
  $action=$data_temp['action'];
  $completed=$data_temp['completed'];
  //firebug_debug($_GET);
  //print_r($_GET);
  //die();

  $failed_logs=array();

  if($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $action=$_POST['action'];
    unset($_POST['action']);
    $data_temp=$_POST;
  }

  if($action == "save_project_session")
  {
    $_SESSION['project_id']=$_GET['project_id'];
    mysql_schreib("UPDATE operators SET last_project='".$_SESSION['project_id']."' WHERE operator_id='".$_SESSION['operator_id']."';");
    save_session_locator();
  }

  if($action == "save_settings_table_logs")
  {
    $temp=json_encode($data_temp);    
    $sql="UPDATE rel_operators_projects SET settings_table_logs='".$temp."'  WHERE operator_id='".$_SESSION['operator_id']."' AND project_id='".$_SESSION['project_id']."';";
    mysql_schreib($sql);
  }
  if($action == "save_settings_op")
  {
    $temp=json_encode($data_temp);   
    //$_SESSION['settings_op']=$data_temp; 
    $sql="UPDATE rel_operators_projects SET settings='".$temp."'  WHERE operator_id='".$_SESSION['operator_id']."' AND project_id='".$_SESSION['project_id']."';";
    mysql_schreib($sql);
  }

  if($action == "send_dxcluster_spot")
  {
    // http://www.dxcluster.org/main/usermanual_en-4.html#ss4.2
    $address="db0hgw.dyndns.org";
    $port="4111";
    if(($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) 
    {
       echo "socket_create() failed: reason: " . socket_strerror(socket_last_error());
    }
    if(socket_connect($sock, $address, $port) === false) 
    {
      echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock));
    }

    if(socket_write($sock,$data_temp['dxcluster_send_spotter']."\r\n") === false)
    {
      echo "putt";
    }
    sleep(1);
    if(socket_write($sock,"dx ".$data_temp['dxcluster_send_qrg']." ".$data_temp['dxcluster_send_call']." ".$data_temp['dxcluster_send_comment']."\r\n") === false)
    {
      echo "putt";
    }
    socket_close($sock);
  }

  if($action == "export_clublog")
  {
    export_clublog($_GET['project_id']);
  }

  if($action == "save_dxcluster_settings")
  {
    $_SESSION['dxcluster_settings']['band_id']=$_GET['band_id'];
  }

  if($action == "save_map_settings")
  {
    // TODO setting_map_map1
    $_SESSION['map_settings']['mode_id']=$_GET['mode_id'];
    $_SESSION['map_settings']['operator_id']=$_GET['operator_id'];
    $_SESSION['map_settings']['band_id']=$_GET['band_id'];
    $_SESSION['map_settings']['filter']=$_GET['filter'];
  }
  if($action == "savesettings")
  {
    $_SESSION['onlyoperator']=$_GET['onlyoperator'];
  }
  if($action == "savesettingsimport")
  {
    $_SESSION['qrzcache']=$_GET['qrzcache'];
  }

  if($typ == "log")
  {
    if(($action=="mod") || ($action=="import"))
    {
      $now=time();
      if($action == "mod")
      {
	//
	// zusammenpacken
	//$data['log_time']=mktime($time['3'],$time['4'],$time['5'],$time['2'],$time['3'],$time['0']);
	//$data_all[0]['log_time']=time_to_timestamp(mysql_real_escape_string($data_temp['log_time']));
	$data_all[0]['log_call']=strtoupper($data_temp['log_call']);
	$data_all[0]['project_id']=$_SESSION['project_id'];
	$data_all[0]['operator_id']=$_SESSION['operator_id'];
	$data_all[0]['mode_id']=$data_temp['mode_id'];
	$data_all[0]['log_freq']=$data_temp['log_freq'];
	$modes=mysql_fragen('SELECT * FROM modes;','mode_id');
        if($modes[$data_temp['mode_id']]['mode_rapport_signal'] == 0)
        {
	  $data_all[0]['log_rst_rx_0']=$data_temp['log_rst_rx_0'];
	  $data_all[0]['log_rst_rx_1']=$data_temp['log_rst_rx_1'];
	  $data_all[0]['log_rst_rx_2']=$data_temp['log_rst_rx_2'];
	  $data_all[0]['log_rst_tx_0']=$data_temp['log_rst_tx_0'];
	  $data_all[0]['log_rst_tx_1']=$data_temp['log_rst_tx_1'];
	  $data_all[0]['log_rst_tx_2']=$data_temp['log_rst_tx_2'];
        }
        else
        {
	  $data_all[0]['log_signal_rx']=$data_temp['log_signal_rx'];
	  $data_all[0]['log_signal_tx']=$data_temp['log_signal_tx'];
        }
	$data_all[0]['log_rst_rx_0']=$data_temp['log_rst_rx_0'];
	$data_all[0]['log_rst_rx_1']=$data_temp['log_rst_rx_1'];
	$data_all[0]['log_rst_rx_2']=$data_temp['log_rst_rx_2'];
	$data_all[0]['log_rst_tx_0']=$data_temp['log_rst_rx_0'];
	$data_all[0]['log_rst_tx_1']=$data_temp['log_rst_rx_1'];
	$data_all[0]['log_rst_tx_2']=$data_temp['log_rst_rx_2'];
	$data_all[0]['log_dok']=$data_temp['log_dok'];
	$data_all[0]['log_notes']=$data_temp['log_notes'];
	$data_all[0]['log_manager']=$data_temp['log_manager'];
	$data_all[0]['log_loc']=strtoupper($data_temp['log_loc']);
	$data_all[0]['log_qth']=$data_temp['log_qth'];
	$data_all[0]['log_name']=$data_temp['log_name'];
	$data_all[0]['log_id']=$data_temp['log_id'];
	$temp=explode(".",$data_temp['log_time_hr_date']);
	$data_all[0]['log_time']=mktime('0','0','0',$temp['1'],$temp['0'],$temp['2']);
	$temp=explode(':',$data_temp['log_time_hr_time']);
	$data_all[0]['log_time']=$data_all[0]['log_time']+(($temp['0']*3600)+($temp['1']*60));
	$data_all[0]['log_qsl_tx']=$data_temp['log_qsl_tx'];
	$data_all[0]['log_qsl_rx']=$data_temp['log_qsl_rx'];
      }
      else if($action == "import")
      {	
	$counter_ok=0;
	$counter_error=0;
	$errors['modes']=0;
	$errors['duplicate']=0;
	$errors['bands']=0;

	$modes=mysql_fragen('SELECT * FROM modes;','mode_name');
	$file="files/".$data_temp['filename'];
	$import=file_get_contents($file);	
	unlink($file);
	// Zeilenumbruch weg, Header weg
	$import=preg_replace('/\n+/','',$import);
	$import=preg_replace('/^.*<eoh>/','',$import);
	$import=preg_replace('/\r+/','',$import);

	$logs=preg_split('/<eor>/i',$import);
	$i=0;
	foreach($logs as $log)
	{
	  if(strlen($log) == 0)
	  {
	    break;
	  }
	  $data_all[$i]['line']=$log;
	  $temp=preg_split('/(<[a-z:_0-9]*>)/i',$log,'-1', PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
	  $c=0;
	  while($c < count($temp))
	  {
	    $key=trim($temp[$c]);
	    $value=trim($temp[$c+1]);
	    if(preg_match('/<call:.*/i',$key))
	    {
	      $data_all[$i]['log_call']=strtoupper($value);
	    }
	    else if(preg_match('/<freq:.*/i',$key))
	    {
	      $data_all[$i]['log_freq']=$value*1000;
	    }
	    else if(preg_match('/<mode:.*/i',$key))
	    {
	      $data_all[$i]['mode_id']=$modes[$value]['mode_id'];
	      $mode=$modes[$value];
	    }
	    else if(preg_match('/<gridsquare:.*/i',$key))
	    {
	      $data_all[$i]['log_loc']=$value;
	    }
	    else if(preg_match('/<qth:.*/i',$key))
	    {
	      $data_all[$i]['log_qth']=$value;
	    }
	    else if(preg_match('/<name:.*/i',$key))
	    {
	      $data_all[$i]['log_name']=$value;
	    }
	    else if(preg_match('/<notes:.*/i',$key))
	    {
	      $data_all[$i]['log_notes']=$value;
	    }
	    else if(preg_match('/<qsl_via:.*/i',$key))
	    {
	      $data_all[$i]['log_manager']=$value;
	    }
	    else if(preg_match('/<station_callsign:.*/i',$key))
	    {
	      $data_all[$i]['log_project_call']=$value;
	    }
	    else if(preg_match('/<my_gridsquare:.*/i',$key))
	    {
	      $data_all[$i]['log_project_locator']=$value;
	    }
	    else if(preg_match('/<darc_dok:.*/i',$key))
	    {
	      $data_all[$i]['log_dok']=$value;
	    }
	    else if(preg_match('/<band:-*/i',$key))
	    {
	      $data_all_temp[$i]['log_band']=$value;
	    }
	    else if(preg_match('/<qsl_rcvd:.*/i',$key))
	    {
	      if($value == "Y")
	      {
		$data_all[$i]['log_qsl_rx']='1';
	      }
	      else
	      {
		$data_all[$i]['log_qsl_rx']='0';
	      }
	    }
	    else if(preg_match('/<qsl_sent:.*/i',$key))
	    {
	      if($value == "Y")
	      {
		$data_all[$i]['log_qsl_tx']='1';
	      }
	      else
	      {
		$data_all[$i]['log_qsl_tx']='0';
	      }
	    }
	    else if(preg_match('/<rst_rcvd:.*/i',$key))
	    {
	      if($mode['mode_rapport_signal'] == 0)
	      {
		$rst=str_split($value);
		$data_all[$i]['log_rst_rx_0']=$rst['0'];
		$data_all[$i]['log_rst_rx_1']=$rst['1'];
		$data_all[$i]['log_rst_rx_2']=$rst['2'];
	      }
	      else
	      {
		$data_all[$i]['log_signal_rx']=$value;
	      }
	    }
	    else if(preg_match('/<rst_sent:.*/i',$key))
	    {
	      if($mode['mode_rapport_signal'] == 0)
              {
		$rst=str_split($value);
		$data_all[$i]['log_rst_tx_0']=$rst['0'];
		$data_all[$i]['log_rst_tx_1']=$rst['1'];
		$data_all[$i]['log_rst_tx_2']=$rst['2'];
	      }
	      else
	      {
		$data_all[$i]['log_signal_tx']=$value;
	      }
	    }
    	    else if(preg_match('/<qso_date:.*/i',$key))
	    {
	      $temp_date=$value;
	    }
	    else if(preg_match('/<time_on:.*/i',$key))
	    {
	      $temp_time=$value;
	    }
	    $c=$c+2;
	  }
	  // date and time string to timestamp
	  if(is_string($temp_date))
	  {
	    $temp_date=str_split($temp_date);
	  }
	  if(is_string($temp_time))
	  {
	    $temp_time=str_split($temp_time);
	  }
	  $data_all[$i]['log_time']=mktime($temp_time[0].$temp_time[1],$temp_time[2].$temp_time[3],'00',$temp_date[4].$temp_date[5],$temp_date[6].$temp_date[7],$temp_date[0].$temp_date[1].$temp_date[2].$temp_date[3]);
          $data_all[$i]['project_id']=$_SESSION['project_id'];
	  $data_all[$i]['operator_id']=$_SESSION['operator_id'];
	  $data_all[$i]['time']=$now;
	  $data_all[$i]['typ']="1";
	  $i++;
	}
      }
      $ok=0;
      //firebug_debug($_SESSION['operator_id']);
      foreach(mysql_fragen('SELECT operator_id FROM rel_operators_projects WHERE project_id='.$_SESSION['project_id']) as $temp_operator)
      {
	//firebug_debug($temp_operator);
	if($temp_operator['operator_id'] == $_SESSION['operator_id'])
	{
	  $ok=1;
	}
      }

      if($ok==0)
      {
	div_err("du bist zwar Superuser, aber dem Projekt nicht zugewiesen!","logs");
	die();
      }

      if($_SESSION['operator_id'] == 0)
      {
	div_err("User ADMIN darf das nicht","logs");
	die();
      }

      // 
      // validieren!

      $baender=mysql_fragen('SELECT bands.* FROM bands INNER JOIN rel_bands_projects ON rel_bands_projects.band_id=bands.band_id WHERE rel_bands_projects.project_id='.$_SESSION['project_id']);
      //firebug_debug($data_all);
      
      foreach($data_all as $dataid => $data)
      {
	$error=0;
	// Doubles?
	if((mysql_fragen('SELECT log_id FROM logs WHERE project_id="'.$_SESSION['project_id'].'" AND operator_id="'.$_SESSION['operator_id'].'" AND log_call="'.$data['log_call'].'" AND log_time="'.$data['log_time'].'"')) && (!is_numeric($data['log_id'])))
	{
	  /*
	  firebug_debug("DUP:");
          firebug_debug($dataid);
          firebug_debug($data);
	  */
	  $errors['duplicate']++;
	  //$error=1;
	}
	  if(strlen($data['log_freq']) != '0')
	  {
	    //
	    // PHP want's ".", replacing a "." ...
	    $data['log_freq']=preg_replace('/,/','.',$data['log_freq']);

	    //
	    // Check if frequency is valid, the db eats only kHz!
	    // Frequency is calculated for kHz
	    //
	    // valid input format from frontend is:
	    // 123 OR 123k OR 123.0 OR 123,0 -> 123kHz
	    // m OR M stands for Mhz
	    // g or G stands for GHz
 
	    if(preg_match('/^[0-9]+[.]?[0-9]*[gG]{1}$/',$data['log_freq']))
	    {
	      $data['log_freq']=preg_replace('/[gG]$/','',$data['log_freq']);
	      $data['log_freq']=$data['log_freq']*1000000;
	    }
	    else if(preg_match('/^[0-9]+[.]?[0-9]*[mM]{1}$/',$data['log_freq']))
	    {
	      $data['log_freq']=preg_replace('/[mM]$/','',$data['log_freq']);
	      $data['log_freq']=$data['log_freq']*1000;
	    }
	    else if(preg_match('/^[0-9]+[.]?[0-9]*[kK]?$/',$data['log_freq']))
	    {
	      $data['log_freq']=preg_replace('/[kK]$/','',$data['log_freq']);
	    }
	    else
	    {
	      if($action == "import")
	      {
		$failed_logs[]="keine valide Frequenz:";
		$failed_logs[]=$data['line'];
		//firebug_debug("break:");
		//firebug_debug($dataid);
		//firebug_debug($data);
		$error=1; 
	      }
	      else
	      {
		div_err("keine valide Frequenz erkannt! (Frequenz: ".$data['log_freq'].")","logs");
		die();
	      }
	    }
 
	    //
	    // Check if frequency is within valid band range AND the band is added to the project

	    foreach($baender as $band)
	    {
	      if(($data['log_freq'] >= $band['band_start']) && ($data['log_freq'] <= $band['band_end'])) 
	      {
		$data['band_id']=$band['band_id'];
	      }
	    }

	    if(!(is_numeric($data['band_id'])))
	    {
	      if($action == "import")
	      {
		$failed_logs[]="kein valides Band:";
		$failed_logs[]=$data['line'];
		//firebug_debug("fehlerhaftes Band:");
		//firebug_debug($data);
		$errors['bands']++;
		$error=1;
	      }
	      else
	      {
		div_err("kein valides Band erkannt! (Frequenz: ".$data['log_freq'].")","logs");
		die(); 
	      }
	    }
	  }
	  else
	  {
	    firebug_debug('keine QRG!');
	    die();
	  }
	if((!is_numeric($data['log_id'])) && ($action != "import"))
	{
	  $data['time']=$now;
	  $data['typ']="0";
	  # #44 , #43, #61
	  $data['log_project_call']=$_SESSION['project_call'];
	  $data['log_project_locator']=$_SESSION['project_locator'];
	}

	if(!(is_numeric($data['mode_id'])))
	{
	  if($action == "import")
	  {
	    $failed_logs[]="keine valide Modulationsart:";
	    $failed_logs[]=$data['line'];
	    //$error_text.="kein validen Mode erkannt!";
	    //firebug_debug("fehlerhafter Mode:");
	    //firebug_debug($data);
	    $errors['modes']++;
	    $error=1;
	  }
	  else
	  {
	    div_err("keine valide Betriebsart!","logs");
	    die();
	  }
	}

	  if(strlen($data['log_call']) < 1) 
	  {
	    div_err("Rufzeichen zu kurz...","logs");
	    die();
	  }
	  if(strlen($data['log_time']) <1) 
	  {
	    div_err("Zeit ist nicht okay","logs");
	    die();
	  }
	  if(strlen($data['log_freq']) <1) 
	  {
	    div_err("Frequenz ist nicht okay","logs");
	    die();
	  }
	  unset($data['line']);

	  if($error == 0)
	  {
	    $data_all_complete[$dataid]=$data;
	    $counter_ok++;
	  }
	  else
	  {
	    $counter_error++;
	  }
	}
      if(is_array($data_all_complete))
      {
	foreach($data_all_complete as $data)
	{
	  //firebug_debug("schreiben:");
	  //firebug_debug($data);
	  /*
	  if($_SESSION['qrzcache'] == 1)
	  {
	    qrz_lookup_call($data['log_call']);
	  }
	  */
	  mysql_write_array('logs',$data,'log_id',$data_temp['log_id']);
	}
      }

      if($action == "mod")
      {
	if($data_temp['log_time_auto'] == "true")
	{
	  mysql_schreib("UPDATE rel_operators_projects SET setting_log_time_auto='1' WHERE project_id='".$_SESSION['project_id']."' AND operator_id='".$_SESSION['operator_id']."';");
	}
	else
	{
	  mysql_schreib("UPDATE rel_operators_projects SET setting_log_time_auto='0' WHERE project_id='".$_SESSION['project_id']."' AND operator_id='".$_SESSION['operator_id']."';");
	}
      }
    }
    /*
    else if($action=="del")
    {
      mysql_schreib("DELETE FROM logs WHERE log_id='".$data['log_id']."';");
    }
    */
    if($action == "import")
    {
      $counter_ok=$counter_ok-$errors['duplicate'];
      if($counter_ok == 0)
      {
	$error_text="Import fertig. Es wurden ".$errors['duplicate']." Duplikate gefunden.";
      }
      else
      {
	$error_text="Import fertig. Es wurden ".$counter_ok." Logs erfolgreich importiert (".$errors['duplicate']." Duplikate gefunden).";
      }
      if($counter_error != 0)
      {
	?>  
	<script>
	document.getElementById('div_log_import_error').style.visibility='visible';
	$('#div_log_import_error_text').empty();
	<?
	foreach($failed_logs as $failed_log)
	{ 
	  ?>
	  $('#div_log_import_error_text').append($("<a><?php print htmlentities($failed_log)?></a><br>"));
	  <?
	}
	?>
	</script>
	<?
	$error_text.="Es gab ".$counter_error." fehlerhafte(s) Logs. ";
	$error_text.="(";
	$error_text.=" ungueltige Betriebsart: ".$errors['modes'];
	$error_text.=" ungueltiges Band: ".$errors['bands'];
	$error_text.=")";
      }
      ?>
      <script>
      alert('<?print $error_text?>');
      </script> 
      <?
    }
    end_edit("log",$completed);
  }

  if($typ == "operator")
  {
    $data_temp['operator_id']=mysql_real_escape_string($data_temp['operator_id']);
    $data['operator_role']=$data_temp['operator_role'];
    $data['operator_mail']=$data_temp['operator_mail'];
    $data['operator_name']=$data_temp['operator_name'];

    if($action=="mod")
    {
      $data['operator_call']=strtoupper($data_temp['operator_call']);
      if(strlen($data['operator_call']) < 1) 
      {
	div_err("Rufzeichen zu kurz...");
	die();
      }
      if($data_temp['operator_pass1'] != $data_temp['operator_pass2'])
      {
	div_err("Passwoerter sind unterschiedlich");
	die();
      }
      if(strlen($data_temp['operator_pass1']) > 4)
      {
	$data['operator_pass']=md5($data_temp['operator_pass1']);
      }
      if($data_temp['operator_pwm'] == "true")
      {
	$data['operator_pass']=substr(md5(rand()),0,10);
	if(!mail($data['operator_mail'],"Zugang zu Netlogbook","Username: ".$data['operator_call']."\nPassword: ".$data['operator_pass']."\n"))
	{
	  div_err("Problem beim Mailversand");
	}
	$data['operator_pass']=md5($data['operator_pass']);
      }
      $operator_id=mysql_write_array('operators',$data,'operator_id',$data_temp['operator_id']);
      if(is_numeric($operator_id))
      {
	mysql_schreib("INSERT INTO projects SET project_operator='1';");
	$project_id=mysql_insert_id();
	mysql_schreib("INSERT INTO rel_operators_projects SET operator_id='".$operator_id."', project_id='".$project_id."';");
      }
    }
    /*
    else if($action=="del")
    {
      $logs=mysql_fragen("SELECT COUNT(*) FROM logs WHERE operator_id='".$data['operator_id']."';"); 
      firebug_debug($logs);
      //mysql_schreib("DELETE FROM operators WHERE operator_id='".$data['operator_id']."';");
    }
    */
    end_edit("operator");
  }

  if($typ == "project_kill_qrz_sess")
  {
    $data['project_id']=mysql_real_escape_string($data_temp['project_id']);
    if($action=="mod")
    {
      mysql_schreib("UPDATE projects SET project_qrz_sess='' WHERE project_id='".$data['project_id']."';");
    }
    end_edit("project");
  }

  if($typ == "project")
  {
    $data['project_id']=mysql_real_escape_string($data_temp['project_id']);
    $data['project_qrz_user']=mysql_real_escape_string($data_temp['project_qrz_user']);
    $data['project_locator']=mysql_real_escape_string(strtoupper($data_temp['project_locator']));
    $data['project_call']=mysql_real_escape_string(strtoupper($data_temp['project_call']));
    $data['project_mode']=mysql_real_escape_string($data_temp['project_mode']);
    $data['project_operator']=mysql_real_escape_string($data_temp['project_operator']);
    if($action=="mod")
    {
      if((strlen($data_temp['project_short_name']) < 1) && ($data_temp['project_operator'] == 0)) 
      {
	div_err("Name zu kurz...");
	die();
      }
      else
      {
	$data['project_short_name']=mysql_real_escape_string($data_temp['project_short_name']);
      }
   
      if($data_temp['project_qrz_pass1'] != $data_temp['project_qrz_pass2'])
      {
	div_err("qrz.com Passwoerter stimmen nicht ueberein");
	die();
      }

      if(strlen($data_temp['project_qrz_pass1']) != 0)
      { 
	$data['project_qrz_pass']=$data_temp['project_qrz_pass1'];
      }

      if($data_temp['project_clublog_ena'] == "true")
      {
	$data['project_clublog_ena']="1";
	$data['project_clublog_auto']=mysql_real_escape_string($data_temp['project_clublog_auto']);
	$data['project_smtp_emailfrom']=mysql_real_escape_string($data_temp['project_smtp_emailfrom']);

	if($data_temp['project_smtp_pass1'] != $data_temp['project_smtp_pass2'])
	{	
	  div_err("SMTP Passwoerter stimmen nicht ueberein");
	  die();
	}
	if(strlen($data_temp['project_smtp_pass1']) != 0)
	{ 
	  $data['project_smtp_pass']=$data_temp['project_smtp_pass1'];
	}

      	$data['project_smtp_server']=mysql_real_escape_string($data_temp['project_smtp_server']);
      	$data['project_smtp_username']=mysql_real_escape_string($data_temp['project_smtp_username']);
      	$data['project_smtp_port']=mysql_real_escape_string($data_temp['project_smtp_port']);
      }
      else
      {
	$data['project_clublog_ena']="0";
      }

      $project_id_new=mysql_write_array('projects',$data,'project_id',$data_temp['project_id']);
      if(is_numeric($project_id_new))
      {
	$data['project_id']=$project_id_new;
      }

      //
      // Schreiben der Relation zwischen Projekt und OP
      if($data['project_operator'] == '0')
      {
	$project_members=split(",",$data_temp['project_members']);
	if($data_temp['project_members'] != 'null')
	{
	  $project_members_old=mysql_fragen("SELECT operator_id FROM rel_operators_projects WHERE project_id='".$data['project_id']."'",'operator_id');
	  foreach($project_members as $project_member)
	  {
	    //
	    // the operator is already member
	    if(!array_key_exists($project_member,$project_members_old))
	    {
	      $sql="INSERT INTO rel_operators_projects (project_id,operator_id) VALUES ('".$data['project_id']."','".$project_member."');";
	      mysql_schreib($sql);
	    }
	  }
	  foreach($project_members_old as $project_member_old)
	  {
	    //
	    // the operator is deleted from project
	    if(!in_array($project_member_old['operator_id'],$project_members))
	    {
	      $sql="DELETE FROM rel_operators_projects WHERE project_id='".$data['project_id']."' AND operator_id='".$project_member_old['operator_id']."'";
	      mysql_schreib($sql);
	    }
	  }
	}
	else
	{
	  $sql="DELETE FROM rel_operators_projects WHERE project_id='".$data['project_id']."';";
          mysql_schreib($sql);
	}
      }
      //
      // Schreiben der Relation zwischen Projekt und Baender
      mysql_schreib("DELETE FROM rel_bands_projects WHERE project_id='".$data['project_id']."';");
      $project_bands=split(",",$data_temp['project_bands']);
      if($data_temp['project_bands'] != 'null')
      {
	foreach($project_bands as $project_band)
	{
	  mysql_schreib("INSERT INTO rel_bands_projects (project_id,band_id) VALUES ('".$data['project_id']."','".$project_band."');");
	}
      }
      //
      // Schreiben der Relation zwischen Projekt und Modis 
      mysql_schreib("DELETE FROM rel_modes_projects WHERE project_id='".$data['project_id']."';");
      $project_modes=split(",",$data_temp['project_modes']);
      if($data_temp['project_modes'] != 'null')
      {
	foreach($project_modes as $project_mode)
	{
	  mysql_schreib("INSERT INTO rel_modes_projects (project_id,mode_id) VALUES ('".$data['project_id']."','".$project_mode."');");
	}
      }
    }
    /*
    else if($action=="del")
    {
      //mysql_schreib("DELETE FROM operators WHERE operator_id='".$data['operator_id']."';");
    }
    */
    mysql_schreib("UPDATE projects SET project_qrz_sess_valid_until = NULL, project_qrz_sess = NULL, project_qrz_sess_created = NULL WHERE project_id='".$data['project_id']."'");
    save_session_locator();
    end_edit("project");
  }
  if($action == "del")
  {
    $data['id']=mysql_real_escape_string($data_temp['id']);
    if($typ == "log")
    {
      mysql_schreib("DELETE FROM logs WHERE log_id='".$data['id']."';");
      end_del($typ);
    }
    else if($typ == "project")
    {
      mysql_schreib("DELETE FROM logs WHERE project_id='".$data['id']."';");
      mysql_schreib("DELETE FROM rel_bands_projects WHERE project_id='".$data['id']."';");
      mysql_schreib("DELETE FROM rel_modes_projects WHERE project_id='".$data['id']."';");
      mysql_schreib("DELETE FROM rel_operators_projects WHERE project_id='".$data['id']."';");
      mysql_schreib("DELETE FROM projects WHERE project_id='".$data['id']."';");
      end_del($typ);
    }
    else if($typ == "operator")
    {
      $r=mysql_query("SELECT log_id FROM logs WHERE operator_id='".$data['id']."';");
      if(mysql_num_rows($r) != 0)
      {
	?>
	<script>alert('Der Operator kann nicht geloescht werden, es sind noch Logeintraege vorhanden.');</script>
	<?
      }
      else
      {
	mysql_schreib("DELETE FROM rel_operators_projects WHERE operator_id='".$data['id']."';");
	mysql_schreib("DELETE FROM operators WHERE operator_id='".$data['id']."';");
	end_del($typ);
      }
    }
  } 
?>  
