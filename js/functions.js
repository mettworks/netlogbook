function set_title()
{
  document.title='NetLogBook Projekt:'+($('#projects option:selected').text());
}
function settings_op_save()
{
  settings_op={};
  settings_op['action']='save_settings_op';
  settings_op['frequency_prefix']=$('#setting_frequency_prefix').val();
  if($('#setting_interface_aprs_ena').prop("checked") == true) { settings_op['aprs_ena']="true"; } else { settings_op['aprs_ena']="false"; }
  if($('#setting_interface_qrz_ena').prop("checked") == true) { settings_op['qrz_ena']="true"; } else { settings_op['qrz_ena']="false"; }
  //if($('#setting_interface_gm_ena').prop("checked") == true) { settings_op['gm_ena']="true"; } else { settings_op['gm_ena']="false"; }
  save_settings_op();
  set_table_logs();
}

function fill_form_settings_op()
{
  settings_op=get_data('settings_op','');
  if(typeof(settings_op['aprs_ena']) == "string")
  {
    if(settings_op['aprs_ena'] == "true") { $('#setting_interface_aprs_ena').prop( "checked", true ); } else { $('#setting_interface_aprs_ena').prop( "checked", false ); }
    if(settings_op['qrz_ena'] == "true") { $('#setting_interface_qrz_ena').prop( "checked", true ); } else { $('#setting_interface_qrz_ena').prop( "checked", false ); }
    //if(settings_op['gm_ena'] == "true") { $('#setting_interface_gm_ena').prop( "checked", true ); } else { $('#setting_interface_gm_ena').prop( "checked", false ); }
    $('#setting_frequency_prefix').val(settings_op['frequency_prefix']);
  }
  else
  {
    $('#setting_interface_aprs_ena').prop( "checked", true );
    $('#setting_interface_qrz_ena').prop( "checked", true );
    //$('#setting_interface_gm_ena').prop( "checked", true );
    $('#setting_frequency_prefix').val('0');
    settings_op_save();
  }
}

function fill_form_settings_op_table_logs()
{
  settings_op_table_logs=get_data('settings_table_logs','');
  if(settings_op_table_logs != null)
  {
    if(typeof(settings_op_table_logs['columns']) == "object") 
    {
      if(settings_op_table_logs['columns'][0]['visible'] == "true") { $('#setting_table_logs_date_ena').prop( "checked", true ); } else { $('#setting_table_logs_date_ena').prop( "checked", false ); } 
      if(settings_op_table_logs['columns'][1]['visible'] == "true") { $('#setting_table_logs_time_ena').prop( "checked", true ); } else { $('#setting_table_logs_time_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][2]['visible'] == "true") { $('#setting_table_logs_call_ena').prop( "checked", true ); } else { $('#setting_table_logs_call_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][3]['visible'] == "true") { $('#setting_table_logs_freq_ena').prop( "checked", true ); } else { $('#setting_table_logs_freq_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][4]['visible'] == "true") { $('#setting_table_logs_mode_ena').prop( "checked", true ); } else { $('#setting_table_logs_mode_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][5]['visible'] == "true") { $('#setting_table_logs_rst_tx_ena').prop( "checked", true ); } else { $('#setting_table_logs_rst_tx_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][6]['visible'] == "true") { $('#setting_table_logs_rst_rx_ena').prop( "checked", true ); } else { $('#setting_table_logs_rst_rx_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][7]['visible'] == "true") { $('#setting_table_logs_name_ena').prop( "checked", true ); } else { $('#setting_table_logs_name_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][8]['visible'] == "true") { $('#setting_table_logs_qth_ena').prop( "checked", true ); } else { $('#setting_table_logs_qth_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][9]['visible'] == "true") { $('#setting_table_logs_loc_ena').prop( "checked", true ); } else { $('#setting_table_logs_loc_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][10]['visible'] == "true") { $('#setting_table_logs_dok_ena').prop( "checked", true ); } else { $('#setting_table_logs_dok_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][11]['visible'] == "true") { $('#setting_table_logs_manager_ena').prop( "checked", true ); } else { $('#setting_table_logs_manager_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][12]['visible'] == "true") { $('#setting_table_logs_qso_ena').prop( "checked", true ); } else { $('#setting_table_logs_qso_ena').prop( "checked", false ); }
      if(settings_op_table_logs['columns'][13]['visible'] == "true") { $('#setting_table_logs_notes_ena').prop( "checked", true ); } else { $('#setting_table_logs_notes_ena').prop( "checked", false ); }
    }
    else
    {
      $('#setting_table_logs_date_ena').prop( "checked", true );
      $('#setting_table_logs_time_ena').prop( "checked", true );
      $('#setting_table_logs_call_ena').prop( "checked", true );
      $('#setting_table_logs_freq_ena').prop( "checked", true );
      $('#setting_table_logs_mode_ena').prop( "checked", true );
      $('#setting_table_logs_rst_tx_ena').prop( "checked", true );
      $('#setting_table_logs_rst_rx_ena').prop( "checked", true );
      $('#setting_table_logs_name_ena').prop( "checked", true );
      $('#setting_table_logs_qth_ena').prop( "checked", true );
      $('#setting_table_logs_loc_ena').prop( "checked", true );
      $('#setting_table_logs_dok_ena').prop( "checked", true );
      $('#setting_table_logs_manager_ena').prop( "checked", true );
      $('#setting_table_logs_qso_ena').prop( "checked", true );
      $('#setting_table_logs_notes_ena').prop( "checked", true );
    }
  }
}

function set_table_logs()
{
  if($('#setting_table_logs_date_ena').prop('checked') == "0" ) {table_logs.column(0).visible(false); } else { table_logs.column(0).visible(true); };
  if($('#setting_table_logs_time_ena').prop('checked') == "0" ) {table_logs.column(1).visible(false); } else { table_logs.column(1).visible(true); }
  if($('#setting_table_logs_call_ena').prop('checked') == "0" ) {table_logs.column(2).visible(false); } else { table_logs.column(2).visible(true);}
  if($('#setting_table_logs_freq_ena').prop('checked') == "0" ) {table_logs.column(3).visible(false); } else { table_logs.column(3).visible(true);} 
  if($('#setting_table_logs_mode_ena').prop('checked') == "0" ) {table_logs.column(4).visible(false); } else { table_logs.column(4).visible(true);} 
  if($('#setting_table_logs_rst_tx_ena').prop('checked') == "0" ) {table_logs.column(5).visible(false); } else { table_logs.column(5).visible(true);} 
  if($('#setting_table_logs_rst_rx_ena').prop('checked') == "0" ) {table_logs.column(6).visible(false); } else { table_logs.column(6).visible(true);} 
  if($('#setting_table_logs_name_ena').prop('checked') == "0" ) {table_logs.column(7).visible(false); } else { table_logs.column(7).visible(true);} 
  if($('#setting_table_logs_qth_ena').prop('checked') == "0" ) {table_logs.column(8).visible(false); } else { table_logs.column(8).visible(true);} 
  if($('#setting_table_logs_loc_ena').prop('checked') == "0" ) {table_logs.column(9).visible(false); } else { table_logs.column(9).visible(true);} 
  if($('#setting_table_logs_dok_ena').prop('checked') == "0" ) {table_logs.column(10).visible(false); } else { table_logs.column(10).visible(true);} 
  if($('#setting_table_logs_manager_ena').prop('checked') == "0" ) {table_logs.column(11).visible(false); } else { table_logs.column(11).visible(true);} 
  if($('#setting_table_logs_qso_ena').prop('checked') == "0" ) {table_logs.column(12).visible(false); } else { table_logs.column(12).visible(true);} 
  if($('#setting_table_logs_notes_ena').prop('checked') == "0" ) {table_logs.column(13).visible(false); } else { table_logs.column(13).visible(true);} 
  // Bug?
  table_logs.column(14).visible(false);
  $('#table_logs').css( 'display', 'block' );
  table_logs.columns.adjust().draw();
}

function save_map2_pos()
{
  loc=get_deginfo(newPos['lon'],newPos['lat']);
  $('#log_loc').val(loc['loc']);
  document.getElementById('div_map2').style.visibility='hidden';
}

function set_project()
{
  project_id=$('#projects').val();
  save_project_session(project_id);
  set_map_settings();
  load();
  loadXML();
  change_settings_dxcluster_setting();
  set_title();
  table_monitor_logs.draw();
  table_monitor_modes.draw();
  table_monitor_bands.draw();
  table_monitor_qsos.draw();
  table_monitor_total.draw();
  fill_form_settings_op_table_logs();
  fill_form_settings_op();
  set_table_logs();
}

//
// fill the option values for the map
function set_map_settings()
{
  session=get_data('session','');
  modes=get_data('rel_project_mode','');
  bands=get_data('rel_project_band','');
  operators=get_data('rel_project_operator','');

  $('#map_settings_modes').find('option').remove().end();
  $('#map_settings_bands').find('option').remove().end();
  $('#map_settings_operators').find('option').remove().end();

  $('#map_settings_modes').append($('<option></option>').val('').html('ALLE'));
  $('#map_settings_bands').append($('<option></option>').val('').html('ALLE'));
  $('#map_settings_operators').append($('<option></option>').val('').html('ALLE'));

  $.each(modes, function(key, val) 
  {
    $('#map_settings_modes').append(
        $('<option></option>').val(val['mode_id']).html(val['mode_name'])
    );
  });
  $.each(bands, function(key, val) 
  {
    $('#map_settings_bands').append(
        $('<option></option>').val(val['band_id']).html(val['band_name'])
    );
  });
  $.each(operators, function(key, val) 
  {
    $('#map_settings_operators').append(
        $('<option></option>').val(val['operator_id']).html(val['operator_call'])
    );
  });
  $.isNumeric(session['map_settings']['band_id'])
  {
    $("#map_settings_bands").val(session['map_settings']['band_id']); 
  }
  $.isNumeric(session['map_settings']['mode_id'])
  {
    $("#map_settings_modes").val(session['map_settings']['mode_id']); 
  }
  $.isNumeric(session['map_settings']['operator_id'])
  {
    $("#map_settings_operators").val(session['map_settings']['operator_id']); 
  }
  $.isNumeric(session['map_settings']['filter'])
  {
    if(session['map_settings']['filter'] == "0")
    {
      $('#map_settings_filter').prop('checked',true);
    }
    else
    {
      $('#map_settings_filter').prop('checked',false);
    }
  }
}
function set_reload_map(stat)
{
  if(stat == "0") 
  {
    if(typeof(interval_map) != 'undefined')
    {
      clearInterval(interval_map);
    }
  }
  else
  {
    interval_map=setInterval("reload_map()",30000);
  }
}

function set_reload_monitor(stat)
{
  if(stat == "0") 
  {
    if(typeof(interval_monitor) != 'undefined')
    {
      clearInterval(interval_monitor);
    }
  }
  else
  {
    interval_monitor=setInterval("reload_monitor()",30000);
  }
}
function set_reload_dxcluster(stat)
{
  if(stat == "0") 
  {
    if(typeof(interval_dxcluster) != 'undefined')
    {
      clearInterval(interval_dxcluster);
    }
  }
  else
  {
    interval_dxcluster=setInterval("reload_dxcluster()",30000);
  }
}

function reload_map()
{
  loadXML();
}

function reload_monitor()
{
  table_monitor_logs.draw();
  table_monitor_modes.draw();
  table_monitor_bands.draw();
  table_monitor_qsos.draw();
  table_monitor_total.draw();
}

function show_picture(image)
{
  $('.class_complete').remove();
  document.getElementById('div_complete').style.visibility='visible';
  $('#div_complete').append('<a class="class_complete" href="#" onclick="document.getElementById(\'div_complete\').style.visibility=\'hidden\';"><img class="class_complete" src="'+image+'"</img></a>')
}
function dxcluster_autoreload()
{
  var dxcluster_autoreload=$('#dxcluster_autoreload').prop('checked');
  if(dxcluster_autoreload == true)
  {
    interval_dxcluster=setInterval("reload_dxcluster()",30000);
  }
  else
  {
    clearInterval(interval_dxcluster);
  }
}

function logs_autoreload()
{
  var logs_autoreload=$('#logs_autoreload').prop('checked');
  if(logs_autoreload == true)
  {
    interval_log=setInterval("reload_tables_log()",30000);
  }
  else
  {
    clearInterval(interval_log);
  }
}
function logs_onlyoperator()
{
  var logs_onlyoperator=$('#logs_onlyoperator').prop('checked');
  if(logs_onlyoperator == true)
  {
    save_settings('1');
    table_logs.draw();
  }
  else
  {
    save_settings('0');
    table_logs.draw();
  }
}

function reload_tables_log_change()
{
  table_logsfromall.draw();
}
function reload_tables_log()
{
  table_logs.draw();
}
function reload_dxcluster()
{
  table_dxcluster.draw();
}
function project_change_clublog()
{
  clublog_ena=$('#project_clublog_ena').prop('checked');
  if(clublog_ena == false)
  {
    $('#project_smtp_emailfrom').prop('disabled',true);
    $('#project_smtp_port').prop('disabled',true);
    $('#project_smtp_username').prop('disabled',true);
    $('#project_smtp_server').prop('disabled',true);
    $('#project_smtp_pass1').prop('disabled',true);
    $('#project_smtp_pass2').prop('disabled',true);
    $('#project_clublog_auto').prop('disabled',true);
    $('#project_button_export_clublog').prop('disabled',true);
  }
  else
  {
    $('#project_smtp_emailfrom').prop('disabled',false);
    $('#project_smtp_port').prop('disabled',false);
    $('#project_smtp_username').prop('disabled',false);
    $('#project_smtp_server').prop('disabled',false);
    $('#project_smtp_pass1').prop('disabled',false);
    $('#project_smtp_pass2').prop('disabled',false);
    $('#project_clublog_auto').prop('disabled',false);
    $('#project_button_export_clublog').prop('disabled',false);
  }
}

function project_change_modus()
{
  project_modus=$('#project_mode').val();
  if(project_modus == 0)
  {
    $('#project_call').prop('disabled',true);
  }
  else
  {
    $('#project_call').prop('disabled',false);
  }
}

//
// aendert beim aendern der checkbox fuer die Zeiteingabe die input Box fuer die Zeit
function log_change_time()
{
  var log_time_auto=$('#log_time_auto').prop('checked');
  if(log_time_auto == true)
  {
    $('#log_time_hr_date').prop('disabled',true);
    $('#log_time_hr_time').prop('disabled',true);
  }
  else
  {
    $('#log_time_hr_date').prop('disabled',false);
    $('#log_time_hr_time').prop('disabled',false);
  }
}

function log_change_loc()
{
  loc=$('#log_loc').val();
  locinfo=get_locinfo(loc);
  $('.class_log_change_locinfo').remove();
  if(typeof(locinfo['distance']) == "number")
  {
    $('#div_log_change_callinfo2').append("<a class='class_log_change_locinfo'>QRB: "+locinfo['distance']+"km</a><br class='class_log_change_locinfo'>");
    $('#div_log_change_callinfo2').append("<a class='class_log_change_locinfo'>Bearing: "+locinfo['bearing']+"</a><br class='class_log_change_locinfo'>");
  }
  else
  {
    $('#div_log_change_callinfo2').append("<a class='class_log_change_locinfo'>QRB: N/A</a><br class='class_log_change_locinfo'>");
    $('#div_log_change_callinfo2').append("<a class='class_log_change_locinfo'>Bearing: N/A</a><br class='class_log_change_locinfo'>");
  }
}

// aa -> AA
function upper(id)
{
  temp=$('#'+id).val();
  temp=temp.toUpperCase();
  $('#'+id).val(temp); 
}

function log_change_mod()
{
  modes=get_data('modes','');
  var mode_id=$('#mode_id').val();
  $.each(modes,function(index,value)
  {
    if(value['mode_id'] == mode_id)
    {
      if(value['mode_digital'] == 0)
      {
	$('#log_rst_rx_2').val('');
	$('#log_rst_tx_2').val('');
	$('#log_rst_rx_2').prop('disabled',true);
	$('#log_rst_tx_2').prop('disabled',true);
      }
      else
      {
	$('#log_rst_rx_2').prop('disabled',false);
	$('#log_rst_tx_2').prop('disabled',false);
      }
    }
  }); 
}

function operator_change_pwm()
{
  change_pwm=$('#operator_pwm').prop('checked'); 
  if(change_pwm == true)
  {
    $('#operator_pass1').val('');
    $('#operator_pass2').val('');
    $('#operator_pass1').prop('disabled',true);
    $('#operator_pass2').prop('disabled',true);
  }
  else
  {
    $('#operator_pass1').prop('disabled',false);
    $('#operator_pass2').prop('disabled',false);
  }
}

function display_callinfo(call,formchange)
{
  call=$('#log_call').val();
  if(settings_op['qrz_ena'] == "true")
  {
    callinfo_qrz=get_callinfo_qrz(call);
  }
  if(settings_op['aprs_ena'] == "true")
  {
    callinfo_aprs=get_callinfo_aprs(call);
  }
  callinfo_logs=get_callinfo_logs(call);

  $('.class_log_change_callinfo').remove();
  $('.error_text').remove();
  //$('#div_log_change_error').append("<a class='class_log_change_callinfo'>"+callinfo_qrz['info']+"</a>");

  if(settings_op['qrz_ena'] == "true")
  {
    if((typeof(callinfo_qrz['error']) == 'string') && (callinfo_qrz['error'] != ""))
    {
      $('#div_log_change_error').append("<a style=color:red; class='class_log_change_callinfo'>Error QRZ.COM:"+callinfo_qrz['error']+"</a>");

      callinfo_qrz['fname']="";
      callinfo_qrz['name']="";
      callinfo_qrz['addr1']="";
      callinfo_qrz['addr2']="";
      callinfo_qrz['url']="";
      callinfo_qrz['grid']="";
      callinfo_qrz['qslmgr']="";
    }
    else
    {
      var div_width=$('#div_log_change_callinfo1_picture').width();
      var div_height=$('#div_log_change_callinfo1_picture').height();

      if(callinfo_qrz['imagestatus'] == "0")
      {
	var op_picture="/cache/qrzcom/"+callinfo_qrz['image'];

	if(div_width/callinfo_qrz['imagewidth'] > div_height/callinfo_qrz['imageheight'])
	{
	  size='height';
	}
	else
	{
	  size='width';
	}
	$('#div_log_change_callinfo1_picture').append('<a class="class_log_change_callinfo" href="#" onclick="show_picture(\''+op_picture+'\');"><img class="class_log_change_callinfo" '+size+'="100%" src="'+op_picture+'"</img></a>');
      }
      else if(callinfo_qrz['imagestatus'] == "1")
      {
	if(div_width/180 > div_height/180)
	{
	  size='height';
	}
	else
	{
	  size='width';
	}
	$('#div_log_change_callinfo1_picture').append('<a class="class_log_change_callinfo"><img class="class_log_change_callinfo" '+size+'="100%" src="images/qrzcom_error.png"</img></a>');
      }
      else
      {
	if(div_width/180 > div_height/180)
	{
	  size='height';
	}
	else
	{
	  size='width';
	}
	$('#div_log_change_callinfo1_picture').append('<a class="class_log_change_callinfo"><img class="class_log_change_callinfo" '+size+'="100%" src="images/qrzcom_dummy.png"</img></a>');
      }
    }
    if(formchange == '1')
    {
      $('#log_loc').val(callinfo_qrz['grid']);
      $('#log_qth').val(callinfo_qrz['addr2']);
      $('#log_name').val(callinfo_qrz['fname']);
      $('#log_manager').val(callinfo_qrz['qslmgr']);
    }
    log_change_loc();
    $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo_qrz['fname']+" "+callinfo_qrz['name']+"</a><br class='class_log_change_callinfo'>");
    $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo_qrz['addr1']+"</a><br class='class_log_change_callinfo'>");
    $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo_qrz['addr2']+"</a><br class='class_log_change_callinfo'><br class='class_log_change_callinfo'>");
    if(typeof(callinfo_qrz['url']) == "string")
    {
      $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo' href="+callinfo_qrz['url']+" target=_blank>"+callinfo_qrz['url']+"</a>");
    }
  }
  if(settings_op['aprs_ena'] == "true")
  {
    if(callinfo_aprs['loc'] != null)
    {
      $('#log_loc').val(callinfo_aprs['loc']);
      log_change_loc();
    }
  }
  if(callinfo_logs['total_project'] == null)
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>Anzahl Total/ich:<b class='class_log_change_callinfo'><br class='class_log_change_callinfo'>NA / NA</a><br class='class_log_change_callinfo'></b>"); 
  }
  else
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>Anzahl Total/ich:<b class='class_log_change_callinfo'><br class='class_log_change_callinfo'>"+callinfo_logs['total_project']['0']['COUNT(*)']+" / "+callinfo_logs['total_operator']['0']['COUNT(*)']+"</a><br class='class_log_change_callinfo'></b>"); 
  }
  if(callinfo_logs['project'] == null)
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO Projekt:<br class='class_log_change_callinfo'><b class='class_log_change_callinfo'>N/A</a></b><br class='class_log_change_callinfo'>"); 
  } 
  else
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO Projekt:<b class='class_log_change_callinfo'><br class='class_log_change_callinfo'>"+callinfo_logs['project']['0']['log_freq']+"kHz / "+callinfo_logs['project']['0']['log_time']+" / "+callinfo_logs['project']['0'].mode_name+" (gesamt: "+callinfo_logs['project'].length+")</a><br class='class_log_change_callinfo'></b>");
  }
  if(callinfo_logs['operator'] == null)
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO mit mir:<br class='class_log_change_callinfo'><b class='class_log_change_callinfo'>N/A</a></b><br class='class_log_change_callinfo'>");
  }
  else
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO mit mir:<br class='class_log_change_callinfo'><b class='class_log_change_callinfo'>"+callinfo_logs['operator']['0']['log_freq']+"kHz / "+callinfo_logs['operator']['0']['log_time']+" / "+callinfo_logs['operator']['0'].mode_name+" (gesamt: "+callinfo_logs['operator'].length+")</a><br class='class_log_change_callinfo'></b>");
  }
}
