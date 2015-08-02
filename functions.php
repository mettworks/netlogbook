<?php
  include('settings.php');
  include('settings2.php');
  date_default_timezone_set('Europe/Berlin');

  function utf8_urldecode($str) 
  {
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    return html_entity_decode($str,null,'UTF-8');;
  }

  function stringtoadif($string,$name)
  {
    $data="<".$name.":".strlen($string).">".$string." ";
    return $data;
  }

  function check()
  {
    $sql="SELECT lastrun FROM cronjob WHERE id='0';";
    $lastrun=mysql_fragen($sql);
    if($lastrun['0']['lastrun'] < time()-600)
    {
      print "<script language='javascript'>alert('Achtung, Cronjob lief nicht oder hat Fehler!');</script>";
    }
    if(!file_put_contents('cache/qrzcom/testfile','bla'))
    {
      print "<script language='javascript'>alert('Verzeichnis /cache/qrzcom/ ist nicht schreibbar!');</script>";
    }
    if(!file_put_contents('files/testfile','bla'))
    {
      print "<script language='javascript'>alert('Verzeichnis /files/ ist nicht schreibbar!');</script>";
    }

  }

  function export_clublog($project_id)
  {
    require('phpmailer/PHPMailerAutoload.php');
    $project=mysql_fragen("SELECT project_call,project_smtp_emailfrom,project_smtp_server,project_smtp_pass,project_smtp_username,project_smtp_port FROM projects WHERE project_id=".$project_id.";");
    $sql="SELECT * FROM logs WHERE project_id=".$project_id." AND log_project_call='".$project['0']['project_call']."'";
    if($logs=mysql_fragen($sql))
    {
      file_put_contents('/tmp/export.adif',make_adif($logs,$project_id));
      $mail = new PHPMailer;
      $mail->isSMTP();
      $mail->Host = $project[0]['project_smtp_server'];
      $mail->SMTPAuth = true;
      $mail->Username = $project[0]['project_smtp_username'];
      $mail->Password = $project[0]['project_smtp_pass'];
      $mail->SMTPSecure = 'tls';
      $mail->Port = $project[0]['project_smtp_port'];
      $mail->From = $project[0]['project_smtp_emailfrom'];
      $mail->FromName = 'Netlogbook';
      if($_SERVER['APPLICATION'] == "dev")
      {
        $mail->addAddress('abcdefg@blacktux.de','');
      }
      else
      {
        $mail->addAddress('upload@clublog.org', '');
      }
      $mail->addAttachment('/tmp/export.adif');
      $mail->Body='empty';
      $mail->Timeout='5';
      $mail->SMTPDebug = 0;
      if(!$mail->send())
      {
	if(isset($_SERVER['HTTP_HOST']))
	{
	  div_err($mail->ErrorInfo);
	}
	else
	{
	  return false;
	}
      }
      else
      {
	if(isset($_SERVER['HTTP_HOST']))
	{
	  div_err('Export erfolgreich!');
	}
	else
	{
	  return true;
	}
      }
    }
  }

  function make_adif($logs,$project_id)
  {
    $modes=mysql_fragen('SELECT * FROM modes;','mode_id');
    $bands=mysql_fragen('SELECT * FROM bands;','band_id');
    $operators=mysql_fragen('SELECT operators.* FROM operators INNER JOIN rel_operators_projects WHERE project_id='.$project_id,"operator_id");

    $data="";
    $data.="ADIF Export by Netlogbook v0.1, conforming to ADIF standard specification V 2.00\n";
    $data.=stringtoadif("Netlogbook","PROGRAMID");
    $data.=stringtoadif("0.1","PROGRAMVERSION");
    $data.="\n<eoh>\n";

    foreach($logs as $log)
    {
      if($log['log_qsl_rx'] == '0')
      {
	$data.=stringtoadif('N',"QSL_RCVD");
      }
      else
      {
	$data.=stringtoadif('Y',"QSL_RCVD");      
      }
      if($log['log_qsl_tx'] == '0')
      {
	$data.=stringtoadif('N',"QSL_SENT");
      }
      else
      {
	$data.=stringtoadif('Y',"QSL_SENT");      
      }

      $data.=stringtoadif($log['log_call'],"CALL");
      $data.=stringtoadif(time_from_timestamp_adif($log['log_time'],"date"),"QSO_DATE");
      $data.=stringtoadif(time_from_timestamp_adif($log['log_time'],"time"),"TIME_ON");
      $data.=stringtoadif($modes[$log['mode_id']]['mode_name'],"MODE");
      $data.=stringtoadif($bands[$log['band_id']]['band_name'],"BAND");
      if($modes[$log['mode_id']]['mode_rapport_signal'] == '0')
      {
	if($modes[$log['mode_id']]['mode_digital'] == '0')
	{
	  $data.=stringtoadif($log['log_rst_rx_0'].$log['log_rst_rx_1'],"RST_RCVD");
	  $data.=stringtoadif($log['log_rst_tx_0'].$log['log_rst_tx_1'],"RST_SENT");
	}
	else
	{
	  $data.=stringtoadif($log['log_rst_rx_0'].$log['log_rst_rx_1'].$log['log_rst_rx_2'],"RST_RCVD");
	  $data.=stringtoadif($log['log_rst_tx_0'].$log['log_rst_tx_1'].$log['log_rst_tx_2'],"RST_SENT");
	}
      }
      else
      {
	$data.=stringtoadif($log['log_signal_rx'],"RST_RCVD");
    	$data.=stringtoadif($log['log_signal_tx'],"RST_SENT");
      }
      $data.=stringtoadif($log['log_loc'],"GRIDSQUARE");
      $data.=stringtoadif($log['log_qth'],"QTH");
      $data.=stringtoadif($log['log_name'],"NAME");
      $data.=stringtoadif($log['log_notes'],"NOTES");
      $data.=stringtoadif($log['log_manager'],"QSL_VIA");
      $data.=stringtoadif($log['log_freq']/1000,"FREQ");
      $data.=stringtoadif($log['log_project_call'],"STATION_CALLSIGN");
      $data.=stringtoadif($log['log_project_locator'],"MY_GRIDSQUARE");
      $data.=stringtoadif($log['log_dok'],"DARC_DOK");
      $data.=stringtoadif($operators[$log['operator_id']]['operator_call'],"OPERATOR");
      $data.=stringtoadif($operators[$log['operator_id']]['operator_name'],"MY_NAME");
      $data.="<eor>\n";
    }
    return $data;
  }

  function save_session_locator()
  {
    $sql="SELECT project_operator,project_call,project_locator FROM projects WHERE project_id='".$_SESSION['project_id']."';";
    $result = mysql_query($sql);
    $data=mysql_fetch_assoc($result);
    if(strlen($data['project_locator']) < 6)
    {
      $data['project_locator']="JO53DM";
    }
    $_SESSION['project_locator']=$data['project_locator'];
    $temp=locator2degree($_SESSION['project_locator']);
    $_SESSION['project_lon']=$temp['lon'];
    $_SESSION['project_lat']=$temp['lat'];
    $_SESSION['project_call']=$data['project_call'];
    $_SESSION['project_operator']=$data['project_operator'];
  }

  //  
  // erwaret: 
  // gibt den Timestamp
  function time_to_timestamp($string)
  {
    $time=explode('/',$string);
    return(mktime($time['3'],$time['4'],$time['5'],$time['2'],$time['3'],$time['0']));
  }

  //
  // erwartet: timestamp
  // gibt einen schoenen String
  function time_from_timestamp($timestamp,$typ)
  {
    if($typ == "date")
    {
      return date('d.m.Y',$timestamp);
    }
    else if($typ == "time")
    {
      return date('H:i',$timestamp);  
    }
  }
  function time_from_timestamp_adif($timestamp,$typ)
  {
    if($typ == "date")
    {
      return date('Ymd',$timestamp);
    }
    else if($typ == "time")
    {
      return date('Hi',$timestamp);
    }
  }

  function locator2degree($locator_temp)
  {
    if(preg_match('/[A-Z]{2}[0-9]{2}[A-Z]{2}/i',$locator_temp))
    {
      //
      //  http://dev.unclassified.de/files/source/MaidenheadLocator.cs
      // http://www.thestorff.de/amateurfunk-locator.php
      $locator=strtoupper($locator_temp);
      $alphabet = range('A', 'Z');
      $locator=str_split($locator);

      //
      // Laenge
      $laenge=
              (array_search($locator[0],$alphabet)*20)+
              ($locator['2']*2)+
              (array_search($locator[4],$alphabet)/12);

      if(isset($locator['6']))
      {
	$laenge=$laenge+($locator['6']/120);
      }

      if(isset($locator['8']))
      {
	$laenge=$laenge+(array_search($locator[8],$alphabet)/120/24);
      }

      $laenge=$laenge-180;

      // 
      // Breite
      $breite=
              (array_search($locator[1],$alphabet)*10)+
              ($locator['3'])+
              (array_search($locator[5],$alphabet)/24);

      if(isset($locator['7']))
      {
	$breite=$breite+($locator['7']/240);
      }

      if(isset($locator['9']))
      {
	$breite=$breite+(array_search($locator[9],$alphabet)/240/24);
      }

      $breite=$breite-90;

      $data['lat']=$breite;
      $data['lon']=$laenge;
      return $data;
    }
    else
    {
      return false;
    }
  }

  function degree2locator($lon,$lat)
  {
    $alphabet = range('A', 'Z');

    $lon_field=floor(($lon+180)/20);
    $lat_field=floor(($lat+90)/10);

    $loc=$alphabet[$lon_field];
    $loc.=$alphabet[$lat_field];

    $lon_square=floor((($lon+180)-($lon_field*20))/2);
    $lat_square=floor((($lat+90)-($lat_field*10))/1);

    $loc.=$lon_square;
    $loc.=$lat_square;

    $lon_subsquare=floor((($lon+180)-($lon_field*20)-($lon_square*2))/0.0833333333333333333333333333333333333333333);
    $lat_subsquare=floor((($lat+90)-($lat_field*10)-($lat_square*1))/0.0416666666666666666666666666666666666666667); 

    $loc.=$alphabet[$lon_subsquare];
    $loc.=$alphabet[$lat_subsquare];

    $data['loc']=$loc;	
    return $data;
  }

  function distance($locator)
  {
    $project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
    $a=locator2degree($project[$_SESSION['project_id']]['project_locator']);  
    $b=locator2degree($locator);
    
    return(distance2($a['lat'],$a['lon'],$b['lat'],$b['lon'])); 
  }
  function bearing($locator)
  {
    $project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
    $a=locator2degree($project[$_SESSION['project_id']]['project_locator']);  
    $b=locator2degree($locator);
    
    return(bearing2($a['lat'],$a['lon'],$b['lat'],$b['lon'])); 
  }

  //
  // stolen from http://fil.ya1.ru/PHP_5_in_Practice/index.htm#page=0768667437/ch02lev1sec6.html
  function _deg2rad_multi() 
  {
    // Grab all the arguments as an array & apply deg2rad to each element
    $arguments = func_get_args();
    return array_map('deg2rad', $arguments);
  }
  //
  // stolen from http://fil.ya1.ru/PHP_5_in_Practice/index.htm#page=0768667437/ch02lev1sec6.html
  function bearing2($lat_a, $lon_a, $lat_b, $lon_b) 
  {
    // Convert our degrees to radians:
    list($lat1, $lon1, $lat2, $lon2) = _deg2rad_multi($lat_a, $lon_a, $lat_b, $lon_b);

    // Run the formula and store the answer (in radians)
    $rads = atan2(
            sin($lon2 - $lon1) * cos($lat2),
            (cos($lat1) * sin($lat2)) -
                  (sin($lat1) * cos($lat2) * cos($lon2 - $lon1)) );

    // Convert this back to degrees to use with a compass
    $degrees = rad2deg($rads);

    // If negative subtract it from 360 to get the bearing we are used to.
    $degrees = ($degrees < 0) ? 360 + $degrees : $degrees;

    return round($degrees,0);
  }

  function distance2($lat1, $lng1, $lat2, $lng2)
  {
    $pi80 = M_PI / 180;
    $lat1 *= $pi80;
    $lng1 *= $pi80;
    $lat2 *= $pi80;
    $lng2 *= $pi80;
 
    $r = 6372.797; // mean radius of Earth in km
    $dlat = $lat2 - $lat1;
    $dlng = $lng2 - $lng1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $km = $r * $c;
    return round($km,0);
  }

  function deginfo($lon,$lat)
  {
    return degree2locator($lon,$lat);
  }

  function locinfo($loc)
  {
    if(strlen($loc) != 0)
    {
      $temp=locator2degree($loc);
      $data['lon']=$temp['lon'];
      $data['lat']=$temp['lat'];
      $data['distance']=distance($loc);
      $data['bearing']=bearing($loc);
    }
    else
    {
      $data['distance']="";
      $data['bearing']="";
    }
    return $data;
  }

  function qrz_fetch_image($url,$call,$size)
  {
    $temp=explode(".",strrev(basename($url)));
    $destname=strtoupper($call).".".strrev($temp['0']);
    $opts = array('http' =>
      array(
        'method'  => 'GET',
        //'header'  => "Content-Type: text/xml\r\n",
        //'content' => $body,
	'timeout' => 8
      )
    );

    $context  = stream_context_create($opts);

    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/qrzcom/'.$destname, file_get_contents($url,false,$context));
    if(filesize($_SERVER['DOCUMENT_ROOT'].'/cache/qrzcom/'.$destname) != $size)
    {
      return false;
    }
    else
    {
      return $destname;
    } 
  }

  function aprs_lookup_call($call)
  {
    global $d22_apikey;
    if(strlen($call) != 0)
    {
      //$aprs=0;
      unlink($aprspos);
      if(preg_match('/^([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}))$/i',$call))
      {
	$call=preg_replace('/^(([a-z0-9])+)(\\/)((p{1})|(m{1})|(mm{1}))$/i','$1',$call);
	//$aprs=1;
      }
      // */CALL
      else if(preg_match('/^([a-z0-9]+)(\\/)([a-z0-9]+)$/i',$call))
      {
	$call=preg_replace('/^([a-z0-9]+)(\\/)([a-z0-9]+)$/i','$3',$call);
	//$aprs=1;
      }
      // */CALL/p OR */CALL/m OR */CALL/mm
      else if(preg_match('/^([a-z0-9]+)(\\/)([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}))$/i',$call))
      {
	$call=preg_replace('/^([a-z0-9]+)(\\/)([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}))$/i','$3',$call);
	//$aprs=1;
      }

      //
      // APRS stuff
      $url="http://api.dg3az.de/data.php?apikey=".$d22_apikey."&typ=aprs_last_pos&call=".$call;
      $json=file_get_contents($url);
      $data=json_decode($json,TRUE);
      if(time() - $data['data']['aprs_last_pos'][0]['ReportTime'] < 43200) 
      {
        $aprspos=degree2locator($data['data']['aprs_last_pos'][0]['Longitude'],$data['data']['aprs_last_pos'][0]['Latitude']);
        return $aprspos;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }

  function qrz_lookup_call($call)
  {
    if(strlen($call) != 0)
    {
      // CALL/p OR CALL/m OR CALL/mm
      if(preg_match('/^([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1})|(qrp{1}))$/i',$call))
      {
        $return=qrz_lookup_call_real($call);
        if(isset($return['error']))
        {
          $call=preg_replace('/^(([a-z0-9])+)(\\/)((p{1})|(m{1})|(mm{1})|(qrp{1}))$/i','$1',$call);
          $return=qrz_lookup_call_real($call);
        }
      }
      // */CALL
      else if(preg_match('/^([a-z0-9]+)(\\/)([a-z0-9]+)$/i',$call))
      {
        $return=qrz_lookup_call_real($call);
        if(isset($return['error']))
        {
          $call=preg_replace('/^([a-z0-9]+)(\\/)([a-z0-9]+)$/i','$3',$call);
          $return=qrz_lookup_call_real($call);
        } 
      }
      // */CALL/p OR */CALL/m OR */CALL/mm
      else if(preg_match('/^([a-z0-9]+)(\\/)([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1})|(qrp{1}))$/i',$call))
      {
        $return=qrz_lookup_call_real($call);
        if(isset($return['error']))
        {
          $call=preg_replace('/^([a-z0-9]+)(\\/)([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}|(qrp{1})))$/i','$3',$call);
          $return=qrz_lookup_call_real($call);
        }
      }
      else
      {
        $return=qrz_lookup_call_real($call);
      }
      return $return;
    }
    else
    {
      return false;
    }
  } 
  function qrz_lookup_call_real($call)
  {
    if(strlen($call) != 0)
    {
      global $qrzcom_cachetime;
      $timestamp=time()-$qrzcom_cachetime;
      if($data_temp=mysql_fragen("SELECT * FROM qrz_cache WHERE qrz_call='".$call."' AND timestamp >= '".$timestamp."'"))
      {
	$return=$data_temp['0'];
	if($data_temp['0']['imagestatus']=="1")
	{
	  if($img=qrz_fetch_image($data_temp['0']['imageurl'],$call,$data_temp['0']['imagesize']))
	  {
	    $data2write['image']=$img;
	    $data2write['imagestatus']="0";
	    mysql_write_array('qrz_cache',$data2write,'qrz_cache_id',$data_temp['0']['qrz_cache_id']);
	  }
	}
	//firebug_debug($return);
      }
      else
      {
	$project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
	// TODO 300s gilt die Session?
	/*
	if((strlen($project[$_SESSION['project_id']]['project_qrz_sess_created']) < 1 ) || (time() - $project[$_SESSION['project_id']]['project_qrz_sess_created'] > 300))
	{
	  */	
	  qrz_session();
	  /*
	  $project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
	}
	*/
	$qrz_sess=$project[$_SESSION['project_id']]['project_qrz_sess'];

	if($response=xmlget('http://xmldata.qrz.com/xml/current/?s='.$qrz_sess.';callsign='.$call))
	{
	  firebug_debug('http://xmldata.qrz.com/xml/current/?s='.$qrz_sess.';callsign='.$call);
	  firebug_debug($response);
	  if((!isset($response['Session']['Error'])) && ($response['Session']['SubExp'] != "non-subscriber"))
	  {
	    if($data_temp=mysql_fragen("SELECT qrz_cache_id FROM qrz_cache WHERE qrz_call='".$call."'"))
	    {
	      $id=$data_temp['0']['qrz_cache_id'];
	    }

	    $imagedata=preg_split('/:/',$response['Callsign']['imageinfo']);
	    //$imageratio=(float)str_replace(',', '.',  round($imagedata[0]/$imagedata[1],2));;

	    $data2write['fname']=$response['Callsign']['fname'];
	    $data2write['name']=$response['Callsign']['name'];
	    $data2write['addr1']=$response['Callsign']['addr1'];
	    $data2write['addr2']=$response['Callsign']['addr2'];
	    $data2write['url']=$response['Callsign']['url'];
	    $data2write['grid']=$response['Callsign']['grid'];
	    $data2write['qslmgr']=$response['Callsign']['qslmgr'];
	    $data2write['qrz_call']=$call;
	    $data2write['timestamp']=time();
	    $data2write['imageheight']=$imagedata[0];
	    $data2write['imagewidth']=$imagedata[1];
	    $data2write['imagesize']=$imagedata[2];
	    $data2write['error']=$response['Session']['Error'];
	    $data2write['imageurl']=$response['Callsign']['image'];

	    if(isset($response['Callsign']['image']))
	    {
	      if($img=qrz_fetch_image($response['Callsign']['image'],$call,$imagedata[2]))
	      {
		$data2write['image']=$img;
		$data2write['imagestatus']="0";
	      }
	      else
	      {
		$data2write['imagestatus']="1";
	      } 
	    }
	    else
	    {
	      $data2write['imagestatus']="2";
	    }
	    mysql_write_array('qrz_cache',$data2write,'qrz_cache_id',$id);

	    // 
	    // return cached data from mysql
	    $return=$data2write;
	  }
	  else
	  {
	    if($response['Session']['Error'])
	    {
	      $return['error']=$response['Session']['Error'];
	    }
	    else
	    {
	      $return['error']=$response['Session']['Message'];
	    }
	  }
	}
	else
	{
	  $return['error']="Problem bei der QRZ.COM Abfrage";
	}
      }
    }
    else
    {
      $return['fname']="";
      $return['name']="";
      $return['addr1']="";
      $return['addr2']="";
      $return['url']="";
      $return['grid']="";
      $return['qslmgr']="";
      $return['qrz_call']="";
      $return['timestamp']="";
      $return['imageheight']="";
      $return['imagewidth']="";
      $return['error']="kein Call";
    }
    return($return);
  }

  function qrz_session()
  {
    $project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
    $qrz_user=$project[$_SESSION['project_id']]['project_qrz_user'];
    $qrz_pass=$project[$_SESSION['project_id']]['project_qrz_pass'];
    $response=xmlget('http://xmldata.qrz.com/xml/1.27/?username='.$qrz_user.';password='.$qrz_pass.';agent=q5.0');
    $qrz_sess=$response['Session']['Key'];
    mysql_schreib("UPDATE projects SET project_qrz_sess='".$qrz_sess."',project_qrz_sess_created='".time()."' WHERE project_id='".$_SESSION['project_id']."';");
    return $response;
  }

  function xmlget($url)
  {
    $opts = array('http' =>
      array(
	'method'  => 'GET',
	'header'  => "Content-Type: text/xml\r\n",
	'content' => $body,
      'timeout' => 5
      )
    );
                       
    $context  = stream_context_create($opts);
    if($xmlstring = file_get_contents($url, false, $context, -1, 40000))
    {
      $xml = new SimpleXMLElement($xmlstring);
      $xml=(array)$xml;
      foreach($xml as $name => $key)
      {
	foreach($key as $key2 => $value)
	{
	  $return[$name][$key2]=strval($value);
	}
      }
      return $return;
    }
    else
    {
      return false;
    }
  }

  function div_err($msg,$typ='')
  {
    if($typ == "logs")
    {
      ?>
      <script>
      $("div#div_log_change_error").html('<?php echo "<p class=error_text>".$msg?>');
      </script>
      <?
    }
    else
    {
      ?>
      <script>
      //$("div#div_error").html('<?php echo "<p class=error_text>".$msg?>');
      alert('<?php echo $msg?>');
      </script>
      <?php
    }
  }

  function end_del($typ)
  {
    ?>
    <script>
    //document.getElementById('div_delete_data_ask').style.visibility='hidden';
    table_<?=$typ?>s.draw();
    </script>
    <?
  }

  function end_edit($typ,$completed='1')
  {
    ?>
    <script>
    $("div#div_fehler").html('');
    document.getElementById('div_error').style.visibility='hidden';
    <?
    if($completed == "1") 
    {
      ?>
      document.getElementById('div_<?=$typ?>_change').style.visibility='hidden';
      <?
    }
    
    if($typ == "project")
    {
      ?>
      load();
      loadXML();
      <?
    }
    if($typ == "log")
    { 
      ?>
      fill_dxcluster_setting();
      document.getElementById('div_log_import').style.visibility='hidden';
      <?
      if($completed=="0")
      {
	?>
	change_log();
	<?
      }
      else
      {
	?>
	shortcut.remove("Ctrl+S");
	shortcut.remove("Ctrl+X");
	clearInterval(interval_log_change);
	set_qrg_auto(0);
	<?
      }
    }
    ?>
    table_<?=$typ?>s.draw();
    </script>
    <?php
  }

  function checklogin()
  {
    session_start();
    // wenn keine g�tige Anmeldung da ist, gleich auf die Index Seite zur�k
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'])
    {
      header('Location: /login.php');
      die();
    }
  }

  function mysql_write_array($table,$fields,$idname,$id)
  {
    $id=mysql_real_escape_string($id);
    foreach($fields as $name => $value)
    {
      $value=mysql_real_escape_string($value);
      if(is_numeric($id))
      {
	// update
	//if(strlen($value) > 0)
	//{
	  $string1.=$name."='".$value."',";
	//}
      }
      else
      {
	//if(strlen($value) > 0)
	//{
	  // insert
	  $string1.=$name.",";
	  $string2.="'".$value."',";
	//}
      }
    }
    $string1=preg_replace('/\,$/','',$string1);
    $string2=preg_replace('/\,$/','',$string2);

    if(is_numeric($id)) 
    {
      $sql="UPDATE ".$table." SET ".$string1." WHERE ".$idname."='".$id."';";
    }
    else
    {
      $sql="INSERT INTO ".$table." (".$string1.") VALUES (".$string2.");";
    }
    //firebug_debug($sql);
    if(!mysql_schreib($sql))
    {
      div_err("Fehler beim schreiben:".mysql_error());
      firebug_debug($sql);
    }
    if(!is_numeric($id))
    {
      return(mysql_insert_id());
    }
    else
    {
      return true;
    }
  }

  //
  // MySQL Connect
  function mysql_c()
  {
    global $mysql_host;
    global $mysql_user;
    global $mysql_pass;
    global $mysql_db;

    if($mysql=mysql_connect($mysql_host,$mysql_user,$mysql_pass))
    {
      mysql_select_db($mysql_db);
      //mysql_query("SET NAMES 'utf8'");
      mysql_set_charset("utf8",$mysql);
      return $mysql;
    }
    else
    {
      print mysql_error()."\n";
      die("MySQL Connect geht nicht!\n");
    }
  }

  //
  // MySQL Abfrage, liefert ein Array zurueck
  function mysql_fragen($sql,$idname='',$id='')
  {
    if(is_numeric($id))
    {
      //
      // VODOO! ;-)
      if((preg_match('/^.*WHERE/',$sql)) == 0)
      {
	$sql=$sql." WHERE ".$idname."=".$id;
      }
      else
      {
	$sql=$sql." AND ".$idname."=".$id;
      }
    }
    $sql=$sql.";";
    //firebug_debug($sql);
    if(!$result = mysql_query($sql))
    {
      firebug_debug(mysql_error());
      print $sql."\n";
      print mysql_error()."\n";
      die();
    }
    //firebug_debug($sql);
    if(mysql_num_rows($result) != 0 )
    {
      if(strlen($idname) == 0)
      {
	$z=0;
      }
      while($row=mysql_fetch_assoc($result))
      {
	if(strlen($idname) != 0)
	{
	  $data[$row[$idname]]=$row;
	}
	else
	{
	  $data[$z]=$row;
	  $z++;	
	}
      }
      //firebug_debug($data);
      return $data;
    }
    else
    {
      return false;
    }
  }

  function mysql_schreib($sql,$debug="false")
  {
    //firebug_debug("mysql_schreib():");
    //firebug_debug($sql);
    if(!mysql_query($sql))
    {
      //firebug_debug(mysql_error());
      die(mysql_error());
    }
    return true;
  }


function firebug_debug ($data) 
{
  echo "<script>\r\n//<![CDATA[\r\nif(!console){var console={log:function(){}}}";
  $output    =    explode("\n", print_r($data, true));
  foreach ($output as $line) 
  {
    if (trim($line)) 
    {
      $line    =    addslashes($line);
      echo "console.log(\"{$line}\");";
    }
  }
  echo "\r\n//]]>\r\n</script>";
}

  function hole_daten($typ,$id='',$iDisplayStart='',$iDisplayLength='',$sEcho='')
  {

    if((isset($iDisplayStart)) && (isset($iDisplayLength)))
    {
      $limit=" LIMIT ".intval( $iDisplayStart ).", ".intval( $iDisplayLength );
    }
    else
    {
      $limit="";
    }

    if($typ=="operator")
    {
      if(is_numeric($id))
      {
	$sql="SELECT * from operators WHERE operator_id='".$id."';";
      }
      else
      { 
	$sql="SELECT * from operators";
      }
      //$data_temp=mysql_fragen($sql);
      // leeres Feld ist fuer bearbeiten!
      $felder=array('operator_id','operator_call','','');
    }
    else if($typ=="project")
    {
      if(is_numeric($id))
      {
	$sql="SELECT * from projects WHERE project_id='".$id."';";
      }
      else
      { 
	$sql="SELECT * from projects";
      }
      //$data_temp=mysql_fragen($sql);
      // leeres Feld ist fuer bearbeiten!
      $felder=array('project_id','project_short_name','project_long_name','project_qrz_user','project_qrz_pass','project_qrz_sess','project_qrz_sess_created','project_locator','','');
    }
    else if($typ == "settings")
    { 
      $sql="SELECT * FROM rel_operators_projects WHERE operator_id='".$_SESSION['operator_id']."' AND project_id='".$_SESSION['project_id']."';";
      $felder=array('setting_log_time_auto');
    }
    else if(($typ=="log") || ($typ=="log_last"))
    {
      if(is_numeric($id))
      {
	$sql="SELECT * from logs WHERE project_id='".$_SESSION['project_id']."' AND log_id='".$id."';";
      }
      else if($typ=="log_last")
      {
	$sql="SELECT * from logs WHERE operator_id='".$_SESSION['operator_id']."' AND project_id='".$_SESSION['project_id']."' ORDER BY log_time DESC LIMIT 1;";
      }
      else
      { 
	$sql="SELECT * from logs WHERE project_id='".$_SESSION['project_id']."'";
      }
      // leeres Feld ist fuer bearbeiten!
      $felder=array(  'log_id',
		      'log_call',
		      'log_freq',
                      'log_time',
                      'log_rst_rx_0',
                      'log_rst_rx_1',
                      'log_rst_rx_2',
                      'log_rst_tx_0',
                      'log_rst_tx_1',
                      'log_rst_tx_2',
                      'log_dok',
                      'log_iota',
                      'log_notes',
                      'log_via',
                      'project_id',
                      'operator_id',
		      'mode_id',
		    );
      $prepare=array(
			'log_time' => 'time_from_timestamp',
		      );
    }
    else if($typ=="band")
    {
      if(is_numeric($id))
      {
	$sql="SELECT * from bands WHERE band_id='".$id."';";
      }
      else
      { 
	$sql="SELECT * from bands;";
      }
      // leeres Feld ist fuer bearbeiten!
      $felder=array('band_id','band_name','','');
    }
    else if($typ=="mode")
    {
      if(is_numeric($id))
      {
	$sql="SELECT * from modes WHERE mode_id='".$id."';";
      }
      else
      { 
	$sql="SELECT * from modes;";
      }
      // leeres Feld ist fuer bearbeiten!
      $felder=array('mode_id','mode_name','mode_digital','','');
    }
    //
    // Relation zwischen Projekt und Operator anhand der projekt id
    else if($typ == "rel_project_operator")
    {
      $sql="SELECT operator_id FROM rel_operators_projects WHERE project_id='".$id."';";
      $felder=array('operator_id');
    }
    else if($typ == "rel_log_mode")
    {
      $sql="SELECT mode_id FROM logs WHERE log_id='".$id."';";
      $felder=array('mode_id');
    }
    else if($typ == "rel_project_mode")
    {
      if(!is_numeric($id))
      {
	$id=$_SESSION['project_id'];
      }
      $sql="SELECT modes.mode_name,modes.mode_id FROM modes 
		INNER JOIN rel_modes_projects ON rel_modes_projects.mode_id=modes.mode_id 
	    WHERE rel_modes_projects.project_id='".$id."';";

      //$sql="SELECT mode_id FROM rel_modes_projects WHERE project_id='".$id."';";
      $felder=array('mode_id','mode_name');
    }
    else if($typ == "rel_project_band")
    {
      if(!is_numeric($id))
      {
	$id=$_SESSION['project_id'];
      }
      $sql="SELECT bands.* FROM bands 
                INNER JOIN rel_bands_projects ON rel_bands_projects.band_id=bands.band_id 
            WHERE rel_bands_projects.project_id='".$id."';";

      //$sql="SELECT band_id FROM rel_bands_projects WHERE project_id='".$id."';";
      $felder=array('band_id','band_start','band_end');
    }
    else if($typ == "rel_operator_project")
    {
      $sql="select rel_operators_projects.project_id, projects.project_short_name FROM projects 
		INNER JOIN rel_operators_projects ON projects.project_id=rel_operators_projects.project_id 
	    WHERE rel_operators_projects.operator_id='".$id."';"; 

      $felder=array('project_id','project_short_name');
    }


    //
    // erst zaehlen
    $data_temp=mysql_fragen($sql.";");
    $total=count($data_temp);
    $data_temp=mysql_fragen($sql.$limit.";");
    $shown=count($data_temp); 
    

    $i=0;

    if(is_array($data_temp))
    { 
      foreach($data_temp as $data)
      {
	$z=0;
	foreach($felder as $feld)
	{
	  if(is_array($prepare))
	  {
	    if(array_key_exists($feld,$prepare))
	    {
	      $data[$feld]=$prepare[$feld]($data[$feld]);
	    } 
	  }
	  if($data[$feld] === NULL)
	  {
	    $data[$feld]="";
	  }
	  $return['aaData'][$i][$z]=$data[$feld];
	  $z++;
	}
      $i++; 
      }
    }
    else
    {
      $return['aaData']=array();
      $total=0;
    }
    
    $return['iTotalRecords']=$total;
    $return['iTotalDisplayRecords']=$shown;
    $return['sEcho'] = intval($sEcho);
    return $return;
    
  }

?>
