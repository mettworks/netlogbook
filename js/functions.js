function settings_op_save()
{
  setting_op={};
  setting_op['action']="save_settings_op";
  setting_op['frequency_prefix']=$('#setting_frequency_prefix').val();
  setting_op['aprs_ena']=$('#setting_interface_aprs_ena').prop('checked');
  setting_op['qrz_ena']=$('#setting_interface_qrz_ena').prop('checked');
  setting_op['gm_ena']=$('#setting_interface_gm_ena').prop('checked');  
  save_settings_op(setting_op);
  set_table_logs();
}

function fill_form_settings_op_table_logs()
{
  settings_op_table_logs=get_data('settings_table_logs','');
  if(settings_op_table_logs['columns'][0]['visible'] == "true") { $('#setting_table_logs_date_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][1]['visible'] == "true") { $('#setting_table_logs_time_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][2]['visible'] == "true") { $('#setting_table_logs_call_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][3]['visible'] == "true") { $('#setting_table_logs_freq_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][4]['visible'] == "true") { $('#setting_table_logs_mode_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][5]['visible'] == "true") { $('#setting_table_logs_rst_tx_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][6]['visible'] == "true") { $('#setting_table_logs_rst_rx_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][7]['visible'] == "true") { $('#setting_table_logs_name_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][8]['visible'] == "true") { $('#setting_table_logs_qth_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][9]['visible'] == "true") { $('#setting_table_logs_loc_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][10]['visible'] == "true") { $('#setting_table_logs_dok_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][11]['visible'] == "true") { $('#setting_table_logs_manager_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][12]['visible'] == "true") { $('#setting_table_logs_qso_ena').prop( "checked", true ); } 
  if(settings_op_table_logs['columns'][13]['visible'] == "true") { $('#setting_table_logs_notes_ena').prop( "checked", true ); } 

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
    interval_monitor=setInterval("reload_monitor()",5000);
  }
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

function logs_autoreload()
{
  var logs_autoreload=$('#logs_autoreload').prop('checked');
  if(logs_autoreload == true)
  {
    interval_log=setInterval("reload_tables_log()",5000);
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
  callinfo=get_callinfo(call);
  $('.class_log_change_callinfo').remove();
  $('.error_text').remove();
  console.log(callinfo);
  $('#div_log_change_error').append("<a class='class_log_change_callinfo'>"+callinfo['qrzcom']['info']+"</a>");

  if((typeof(callinfo['qrzcom']['error']) == 'string') && (callinfo['qrzcom']['error'] != ""))
  {
    $('#div_log_change_error').append("<a class='class_log_change_callinfo'>Error QRZ.COM:"+callinfo['qrzcom']['error']+"</a>");

    callinfo['qrzcom']['fname']="";
    callinfo['qrzcom']['name']="";
    callinfo['qrzcom']['addr1']="";
    callinfo['qrzcom']['addr2']="";
    callinfo['qrzcom']['url']="";
    callinfo['qrzcom']['grid']="";
    callinfo['qrzcom']['qslmgr']="";
  }
  else
  {
    var div_width=$('#div_log_change_callinfo1_picture').width();
    var div_height=$('#div_log_change_callinfo1_picture').height();

    if(callinfo['qrzcom']['imagestatus'] == "0")
    {
      var op_picture="/cache/qrzcom/"+callinfo['qrzcom']['image'];

      if(div_width/callinfo['qrzcom']['imagewidth'] > div_height/callinfo['qrzcom']['imageheight'])
      {
	size='height';
      }
      else
      {
	size='width';
      }
      $('#div_log_change_callinfo1_picture').append('<a class="class_log_change_callinfo" href="#" onclick="show_picture(\''+op_picture+'\');"><img class="class_log_change_callinfo" '+size+'="100%" src="'+op_picture+'"</img></a>');
    }
    else if(callinfo['qrzcom']['imagestatus'] == "1")
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
    $('#log_loc').val(callinfo['qrzcom']['grid']);
    $('#log_qth').val(callinfo['qrzcom']['addr2']);
    $('#log_name').val(callinfo['qrzcom']['fname']);
    $('#log_manager').val(callinfo['qrzcom']['qslmgr']);
  }
  log_change_loc();
  if(callinfo.callinfo_total_project == null)
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>Anzahl Total/ich:<b class='class_log_change_callinfo'><br class='class_log_change_callinfo'>NA / NA</a><br class='class_log_change_callinfo'></b>"); 
  }
  else
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>Anzahl Total/ich:<b class='class_log_change_callinfo'><br class='class_log_change_callinfo'>"+callinfo.callinfo_total_project['0']['COUNT(*)']+" / "+callinfo.callinfo_total_operator['0']['COUNT(*)']+"</a><br class='class_log_change_callinfo'></b>"); 
  }
  if(callinfo.callinfo_project == null)
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO Projekt:<br class='class_log_change_callinfo'><b class='class_log_change_callinfo'>N/A</a></b><br class='class_log_change_callinfo'>"); 
  } 
  else
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO Projekt:<b class='class_log_change_callinfo'><br class='class_log_change_callinfo'>"+callinfo.callinfo_project['0']['log_freq']+"kHz / "+callinfo.callinfo_project['0']['log_time']+" / "+callinfo.callinfo_project['0'].mode_name+" (gesamt: "+callinfo.callinfo_project.length+")</a><br class='class_log_change_callinfo'></b>");
  }
  if(callinfo.callinfo_operator == null)
  {
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO mit mir:<br class='class_log_change_callinfo'><b class='class_log_change_callinfo'>N/A</a></b><br class='class_log_change_callinfo'>");
  }
  else
  { 
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO mit mir:<br class='class_log_change_callinfo'><b class='class_log_change_callinfo'>"+callinfo.callinfo_operator['0']['log_freq']+"kHz / "+callinfo.callinfo_operator['0']['log_time']+" / "+callinfo.callinfo_operator['0'].mode_name+" (gesamt: "+callinfo.callinfo_operator.length+")</a><br class='class_log_change_callinfo'></b>");
  }
  $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['qrzcom']['fname']+" "+callinfo['qrzcom']['name']+"</a><br class='class_log_change_callinfo'>");
  $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['qrzcom']['addr1']+"</a><br class='class_log_change_callinfo'>");
  $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['qrzcom']['addr2']+"</a><br class='class_log_change_callinfo'><br class='class_log_change_callinfo'>");
  if(typeof(callinfo['qrzcom']['url']) == "string")
  {
    $('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo' href="+callinfo['qrzcom']['url']+" target=_blank>"+callinfo['qrzcom']['url']+"</a>");
  }
}
