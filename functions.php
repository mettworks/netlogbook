<?php
  include('settings.php');
  date_default_timezone_set('Europe/Berlin');

  function stringtoadif($string,$name)
  {
    $data="<".$name.":".strlen($string).">".$string." ";
    return $data;
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
    //print "<pre>";
    //print $locator_temp."\n";
    //
    // http://www.thestorff.de/amateurfunk-locator.php
    // http://db6zh.darc.de/qthzz/qthxx03.htm
    $locator=strtoupper($locator_temp);
    $alphabet = range('A', 'Z');
    $locator=str_split($locator);

    //
    // Laenge
    $temp=(array_search($locator[0],$alphabet));
    //print $temp."\n";
    if($temp <= 9)
    {
      $laenge=($temp*20)-180;
    }
    else
    {
      $laenge=($temp*20)-180;
    }
    $laenge=$laenge+($locator['2']*2);

    $temp=(array_search($locator[4],$alphabet));
    $laenge=$laenge+($temp*0.0833333333333333333333333333333333333333333)+(0.0833333333333333333333333333333333333333333/2);

    //print "Laenge: ".$laenge."\n"; 

    // 
    // Breite
    $temp=(array_search($locator[1],$alphabet));
    if($temp <= 9)
    {
      $breite=-90+(($temp)*10);
    }
    else
    {
      $breite=($temp*10)-90;
    }
    $breite=$breite+($locator['3']*1);

    $temp=(array_search($locator[5],$alphabet));
    // 2,5 Bogenminuten entsprechen 0,0416666666666666666666666666666666666666667 Grad
    $breite=$breite+($temp*0.0416666666666666666666666666666666666666667)+(0.0416666666666666666666666666666666666666667/2);

    //print "Breite: ".$breite."\n";

    $data['lat']=$breite;
    $data['lon']=$laenge;
    //firebug_debug("Loc: ".$locator_temp."/ Lat: ".$breite." / Lon: ".$laenge);
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

  function locinfo($loc)
  {
    if(strlen($loc) != 0)
    {
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
    file_put_contents('/usr/local/www/dxpad/cache/qrzcom/'.$destname, file_get_contents($url));
    if(filesize('/usr/local/www/dxpad/cache/qrzcom/'.$destname) != $size)
    {
      return false;
    }
    else
    {
      return $destname;
    } 
  }

  function qrz_lookup_call($call)
  {
    if(strlen($call) != 0)
    {
      global $qrzcom_cachetime;

      if(preg_match('/^([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}))$/i',$call))
      {
	$call=preg_replace('/^(([a-z0-9])+)(\\/)((p{1})|(m{1})|(mm{1}))$/i','$1',$call);
      }
      else if(preg_match('/^([a-z0-9]+)(\\/)([a-z0-9]+)$/i',$call))
      {
	$call=preg_replace('/^([a-z0-9]+)(\\/)([a-z0-9]+)$/i','$3',$call);
      }
      else if(preg_match('/^([a-z0-9]+)(\\/)([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}))$/i',$call))
      {
	$call=preg_replace('/^([a-z0-9]+)(\\/)([a-z0-9]+)(\\/)((p{1})|(m{1})|(mm{1}))$/i','$3',$call);
      }

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
      }
      else
      {
	$project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
	// TODO 300s gilt die Session?
	if((strlen($project[$_SESSION['project_id']]['project_qrz_sess_created']) < 1 ) || (time() - $project[$_SESSION['project_id']]['project_qrz_sess_created'] > 300))
	{	
	  qrz_session();
	  $project=mysql_fragen('SELECT * from projects','project_id',$_SESSION['project_id']);
	}
	$qrz_sess=$project[$_SESSION['project_id']]['project_qrz_sess'];

	if($response=xmlget('http://xmldata.qrz.com/xml/current/?s='.$qrz_sess.';callsign='.$call))
	{
	  //firebug_debug($response);
	  if(!isset($response['Session']['Error']))
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

	    $return=$data2write;
	  }
	  else
	  {
	    $return['error']=$response['Session']['Error'];
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
    document.getElementById('div_delete_data_ask').style.visibility='hidden';
    table_<?=$typ?>s.fnDraw();
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
    if($typ == "log")
    { 
      ?>
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
	<?
      }
    }
    ?>
    table_<?=$typ?>s.fnDraw();
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
	if(strlen($value) > 0)
	{
	  $string1.=$name."='".$value."',";
	}
      }
      else
      {
	if(strlen($value) > 0)
	{
	  // insert
	  $string1.=$name.",";
	  $string2.="'".$value."',";
	}
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
    if(!$result = mysql_query($sql))
    {
      print $sql."\n";
      print mysql_error()."\n";
      die();
    }
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


  function firebug_debug ($data) {
    echo "<script>\r\n//<![CDATA[\r\nif(!console){var console={log:function(){}}}";
    $output    =    explode("\n", print_r($data, true));
    foreach ($output as $line) {
        if (trim($line)) {
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
