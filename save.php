<?
  include('functions.php');

  checklogin();
  mysql_c();
  
  $data_temp=$_GET;
  $typ=$data_temp['typ'];
  $action=$data_temp['action'];
  $completed=$data_temp['completed'];
  //firebug_debug($_FILE);
  //firebug_debug($_GET);
  //die();

  if($action == "savesettings")
  {
    $_SESSION['onlyoperator']=$_GET['onlyoperator'];
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
	$temp=explode(".",$data_temp['log_time_hr_date']);
	$data_all[0]['log_time']=mktime('0','0','0',$temp['1'],$temp['0'],$temp['2']);
	$temp=explode(':',$data_temp['log_time_hr_time']);
	$data_all[0]['log_time']=$data_all[0]['log_time']+(($temp['0']*3600)+($temp['1']*60));
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
	
	//unlink($file);
	// Zeilenumbruch weg, Header weg
	$import=preg_replace('/\n+/','',$import);
	$import=preg_replace('/^.*<eoh>/','',$import);
	$import=preg_replace('/\r+/','',$import);

	$logs=preg_split('/<eor>/',$import);
	$i=0;
	foreach($logs as $log)
	{
	  if(strlen($log) == 0)
	  {
	    break;
	  }
	  $temp=preg_split('/(<[a-z:_0-9]*>)/',$log,'-1', PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
	  $c=0;
	  while($c < count($temp))
	  {
	    $key=trim($temp[$c]);
	    $value=trim($temp[$c+1]);
	    if(preg_match('/<call:.*/',$key))
	    {
	      $data_all[$i]['log_call']=strtoupper($value);
	    }
	    else if(preg_match('/<freq:.*/',$key))
	    {
	      $data_all[$i]['log_freq']=$value*1000;
	    }
	    else if(preg_match('/<mode:.*/',$key))
	    {
	      $data_all[$i]['mode_id']=$modes[$value]['mode_id'];
	    }
	    else if(preg_match('/<rst_rcvd:.*/',$key))
	    {
	      $rst=str_split($value);
	      $data_all[$i]['log_rst_rx_0']=$rst['0'];
	      $data_all[$i]['log_rst_rx_1']=$rst['1'];
	      $data_all[$i]['log_rst_rx_2']=$rst['2'];
	    }
	    else if(preg_match('/<rst_sent:.*/',$key))
	    {
	      $rst=str_split($value);
	      $data_all[$i]['log_rst_tx_0']=$rst['0'];
	      $data_all[$i]['log_rst_tx_1']=$rst['1'];
	      $data_all[$i]['log_rst_tx_2']=$rst['2'];
	    }
    	    else if(preg_match('/<qso_date:.*/',$key))
	    {
	      $temp_date=$value;
	    }
	    else if(preg_match('/<time_on:.*/',$key))
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
      // 
      // validieren!

      $baender=mysql_fragen('SELECT bands.* FROM bands INNER JOIN rel_bands_projects ON rel_bands_projects.band_id=bands.band_id WHERE rel_bands_projects.project_id='.$_SESSION['project_id']);
      //firebug_debug($data_all);

      foreach($data_all as $dataid => $data)
      {
	$error=0;
	//firebug_debug($data_all);
	// Doubles?
	if(mysql_fragen('SELECT log_id FROM logs WHERE project_id="'.$_SESSION['project_id'].'" AND operator_id="'.$_SESSION['operator_id'].'" AND log_call="'.$data['log_call'].'" AND log_time="'.$data['log_time'].'"'))
	{
	  /*
	  firebug_debug("DUP:");
          firebug_debug($dataid);
          firebug_debug($data);
	  */
	  $errors['duplicate']++;
	  $error=1;
	}
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
	      firebug_debug("break:");
	      firebug_debug($dataid);
	      firebug_debug($data);
	      $error=1; 
	    }

	    div_err("keine valide Frequenz erkannt! (Frequenz: ".$data['log_freq'].")","logs");
	    die();
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
	      firebug_debug("fehlerhaftes Band:");
	      firebug_debug($data);
	      $errors['bands']++;
	      $error=1;
	    }
	    else
	    {
	      div_err("kein valides Band erkannt! (Frequenz: ".$data['log_freq'].")","logs");
	      die(); 
	    }
	  }

	if((!is_numeric($data['log_id'])) && ($action != "import"))
	{
	  $data['time']=$now;
	  $data['typ']="0";
	}

	if(!(is_numeric($data['mode_id'])))
	{
	  if($action == "import")
	  {
	    firebug_debug("fehlerhafter Mode:");
	    firebug_debug($data);
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

      //firebug_debug($data_all);
      //die();  
      foreach($data_all_complete as $data)
      {
	/*
	firebug_debug("schreiben:");
	firebug_debug($data);
	*/
	qrz_lookup_call($data['log_call']);

	mysql_write_array('logs',$data,'log_id',$data_temp['log_id']);
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
    else if($action=="del")
    {
      mysql_schreib("DELETE FROM logs WHERE log_id='".$data['log_id']."';");
    }

    if($action == "import")
    {
      $error_text="Import fertig. Es wurden ".$counter_ok." Logs erfolgreich importiert. ";
      if($counter_error != 0)
      {
	$error_text.="Es gab ".$counter_error." fehlerhafte(s) Logs. ";
	$error_text.="(";
	$error_text.=" ungueltige Betriebsart: ".$errors['modes'];
	$error_text.=" ungueltiges Band: ".$errors['bands'];
	$error_text.=" Duplikate: ".$errors['duplicate'];
	$error_text.=")";
      }

      ?>
      <script>
      alert("<?print $error_text?>");
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
      mysql_write_array('operators',$data,'operator_id',$data_temp['operator_id']);
    }
    else if($action=="del")
    {
      mysql_schreib("DELETE FROM operators WHERE operator_id='".$data['operator_id']."';");
    }
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
    //$data['project_qrz_pass1']=mysql_real_escape_string($data_temp['project_qrz_pass1']);
    //$data['project_qrz_pass2']=mysql_real_escape_string($data_temp['project_qrz_pass2']);
    $data['project_locator']=mysql_real_escape_string($data_temp['project_locator']);

    if($action=="mod")
    {
      if(strlen($data_temp['project_short_name']) < 1) 
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
      $project_id_new=mysql_write_array('projects',$data,'project_id',$data_temp['project_id']);
      if(is_numeric($project_id_new))
      {
	$data['project_id']=$project_id_new;
      }

      //
      // Schreiben der Relation zwischen Projekt und OP
      mysql_schreib("DELETE FROM rel_operators_projects WHERE project_id='".$data['project_id']."';");
      $project_members=split(",",$data_temp['project_members']);

      if($data_temp['project_members'] != 'null')
      {
	foreach($project_members as $project_member)
	{
	  mysql_schreib("INSERT INTO rel_operators_projects (project_id,operator_id) VALUES ('".$data['project_id']."','".$project_member."');");
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
    else if($typ == "operator")
    {
      mysql_schreib("DELETE FROM operators WHERE operator_id='".$data['id']."';");
      end_del($typ);
    }
  } 
?>  
