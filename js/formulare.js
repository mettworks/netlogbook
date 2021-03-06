function change_settings_dxcluster_setting()
{
  bands=get_data('rel_project_band','');
  $('#setting_table_dxcluster_bands').find('option').remove().end();
  $('#setting_table_dxcluster_bands').append($('<option></option>').val('').html('ALLE'));
  $.each(bands, function(val,text) 
  {
    $('#setting_table_dxcluster_bands').append(
      $('<option></option>').val(text['band_id']).html(text['band_name'])
    );
  }); 
}

function fill_dxcluster_setting()
{
  last_log=get_data('callinfo_last_log','');
  if(typeof(last_log[0]) != 'undefined')
  {
    $('#dxcluster_send_qrg').val(last_log[0]['log_freq']);
    $('#dxcluster_send_call').val(last_log[0]['log_project_call']);
  }
}
function import_log()
{
  document.getElementById('div_error').style.visibility='visible';
  document.getElementById('div_log_import').style.visibility='visible';
  //  interval_log_change=setInterval("reload_tables_log_change()",5000);
  interval_log_change='NULL';
 
}
function export_log()
{
  document.getElementById('div_error').style.visibility='visible';
  document.getElementById('div_log_export').style.visibility='visible';
}
function change_log(log_id)
{
  $('#div_log_change_callinfo1').css('background-color','');
  $('#div_log_change_callinfo2').css('background-color','');

  settings_op=get_data('settings_op','');
  session=get_data('session','');
  
  $("#interface_voice_int").val(session['setting_interface_voice_int']);

  if(settings_op['netbook_ena'] == 'false')
  {
    if(session['project_operator'] == 0)
    {
      $('#div_log_change_logsfromall').show();
    }
    else
    {
      $('#div_log_change_logsfromall').hide();
    }
  }
  
  if(settings_op['gm_ena'] == 'true')
  {
    $('#log_change_map').show();
    $('#log_change_map_help').show();
  }
  else
  {
    $('#log_change_map').hide();
    $('#log_change_map_help').hide();
  }

  $('.class_log_change_callinfo').remove();
  $('.class_log_change_locinfo').remove();
  $('.error_text').remove();

  modes=get_data('rel_project_mode','');
  if(log_id)
  {
    // get Data from actual log entry
    logs=get_data('logs',log_id);
    // get data from mode entry (relation)
    log_modes=get_data('rel_log_mode',log_id);
    log_call=logs[log_id]['log_call'];
    log_freq=logs[log_id]['log_freq'];
    log_rst_rx_0=logs[log_id]['log_rst_rx_0'];
    log_rst_rx_1=logs[log_id]['log_rst_rx_1'];
    log_rst_rx_2=logs[log_id]['log_rst_rx_2'];
    log_rst_tx_0=logs[log_id]['log_rst_tx_0'];
    log_rst_tx_1=logs[log_id]['log_rst_tx_1'];
    log_rst_tx_2=logs[log_id]['log_rst_tx_2'];
    log_signal_rx=logs[log_id]['log_signal_rx'];
    log_signal_tx=logs[log_id]['log_signal_tx'];
    log_dok=logs[log_id]['log_dok'];
    log_loc=logs[log_id]['log_loc'];
    log_manager=logs[log_id]['log_manager'];
    log_notes=logs[log_id]['log_notes'];
    log_time=logs[log_id]['log_time'];
    log_qth=logs[log_id]['log_qth'];
    log_name=logs[log_id]['log_name'];
    log_time_hr_date=logs[log_id]['log_time_hr_date'];
    log_time_hr_time=logs[log_id]['log_time_hr_time'];
    log_qsl_rx=logs[log_id]['log_qsl_rx'];
    log_qsl_tx=logs[log_id]['log_qsl_tx'];
    $('#log_time_hr_date').val(log_time_hr_date);
    $('#log_time_hr_time').val(log_time_hr_time);
  }
  else
  {
    // last logged data
    log_new=get_data('log_last');
    // settings, for timesetting (auto or time)
    settings=get_data('settings');
    log_call="";
    if(typeof(log_new[0]) != 'undefined')
    {
      log_freq=log_new['0']['log_freq'];
    }
    else
    {
      log_freq="144000";
    }
    log_rst_rx_0="5";
    log_rst_rx_1="9";
    log_rst_rx_2="9";
    log_rst_tx_0="5";
    log_rst_tx_1="9";
    log_rst_tx_2="9";
    log_dok="";
    log_notes="";
    log_name="";
    log_qth="";
    log_loc="";
    log_manager="";
    log_signal_rx="";
    log_signal_tx=""; 
    var now = new Date();
    var monat = 1 + parseInt(now.getUTCMonth());
    $('#log_time_hr_date').datetimepicker({value: ('0'+now.getUTCDate()).slice(-2)+'.'+('0'+monat).slice(-2)+'.'+now.getUTCFullYear()});
    $('#log_time_hr_time').datetimepicker({value: ('0'+now.getUTCHours()).slice(-2)+':'+('0'+now.getUTCMinutes()).slice(-2)});
  }

  settings_op=get_data('settings_op','');
  if(settings_op['frequency_prefix'] == '0')
  {
    log_freq=log_freq;
  }
  else
  { 
    log_freq=log_freq/1000+"m";
  }

  $('.error_text').remove();
  $('.class_mode_id').remove();

  document.getElementById('div_log_change').style.visibility='visible';

  $('#log_call').val(log_call);
  $('#log_id').val(log_id);
  $('#log_freq').val(log_freq);
  $('#log_signal_rx').val(log_signal_rx);
  $('#log_signal_tx').val(log_signal_tx);
  $('#log_rst_rx_0').val(log_rst_rx_0);
  $('#log_rst_rx_1').val(log_rst_rx_1);
  $('#log_rst_rx_2').val(log_rst_rx_2);
  $('#log_rst_tx_0').val(log_rst_tx_0);
  $('#log_rst_tx_1').val(log_rst_tx_1);
  $('#log_rst_tx_2').val(log_rst_tx_2);
  $('#log_dok').val(log_dok);
  $('#log_notes').val(log_notes);
  $('#log_name').val(log_name);
  $('#log_qth').val(log_qth);
  $('#log_loc').val(log_loc);
  $('#log_loc_qrz').val('');
  $('#log_loc_aprs').val('');
  $('#log_manager').val(log_manager);

  if(log_qsl_rx == '1')
  {
    $('#log_qsl_rx').attr("checked",true); 
  }
  else
  {
    $('#log_qsl_rx').attr("checked",false); 
  }
  if(log_qsl_tx == '1')
  {
    $('#log_qsl_tx').attr("checked",true); 
  }
  else
  {
    $('#log_qsl_tx').attr("checked",false); 
  }

  // modes
  temp=""; 
  $.each(modes,function(index,value)
  {
    selected="";
    //
    // change a log entry
    if(typeof(log_modes) != 'undefined')
    {
      $.each(log_modes,function(index2,value2)
      {
	if(value2['mode_id'] == value['mode_id'])
	{
	  selected="selected";
      }
      });
    }
    //
    // a new log entry, get the settings from the last written entry
    else if((!log_id) && (typeof(log_new[0]) != 'undefined'))
    {
      if(log_new['0']['mode_id'] == value['mode_id'])
      {
	selected="selected";
      }
    } 
    temp=temp+'<option class="class_mode_id" '+selected+' value='+value['mode_id']+'>'+value['mode_name']+'</option>';
  }
  );
  $('#mode_id').append(temp);

  if(session['setting_log_time_auto'] == 0)
  {
    $('#log_time_auto').removeAttr("checked");
  }
  else
  {
    $('#log_time_auto').attr("checked",true);
  }

  if(session['project_interface_ena'] == 1)
  {
    $('#log_qrg_auto').show();
    if(session['setting_log_qrg_auto'] == 0)
    {
      $('#log_qrg_auto').removeAttr("checked");
    }
    else
    {
      $('#log_qrg_auto').attr("checked",true);
    }
  }
  else
  {
    $('#log_qrg_auto').removeAttr("checked");
    $('#log_qrg_auto').hide();
  }
  
  log_change_time();
  log_change_qrg();
  log_change_mod();

  shortcut.add("Ctrl+Y",function() 
  {
    display_callinfo('','1');
    completed='0';
    write_data('log')
  });
  shortcut.add("Ctrl+S",function() 
  {
    display_callinfo('','1');
    completed='0';
    write_data('log')
  });

  if(log_id)
  {
    display_callinfo('1');
  }
  if(!log_id)
  {
    $('#log_call').focus(); //set focus
  }
  //interval_log_change=setInterval("reload_tables_log_change()",5000); 
  interval_log_change='NULL';
  table_logsfromme.draw();
  table_logsfromall.draw();

  // $(document).ready(function() { document.title = 'Netlogbook v0.1 - neues Log erfassen'; });
}

function change_operator(operator_id)
{
  if(operator_id)
  {
    operator_mod=get_data('operators_all',operator_id);
    operator_call=operator_mod[operator_id]['operator_call'];
    operator_name=operator_mod[operator_id]['operator_name'];
    operator_mail=operator_mod[operator_id]['operator_mail'];
    operator_role=operator_mod[operator_id]['operator_role'];
    //
    // the roles are hardcoded in index.php 
    $('#operator_role').val(operator_role);
  }
  else
  {
    operator_call="";
    operator_mail="";
    operator_name="";
    $('#operator_role').val('1');
  }

  operator_pass1="";
  operator_pass2="";

  document.getElementById('div_error').style.visibility='visible';
  document.getElementById('div_operator_change').style.visibility='visible';

  $('#operator_call').val(operator_call);
  $('#operator_id').val(operator_id);
  $('#operator_name').val(operator_name);
  $('#operator_mail').val(operator_mail);
  $('#operator_pass1').val(operator_pass1);
  $('#operator_pass2').val(operator_pass2);
}

function change_project(project_id)
{
  operators=get_data('operators','');
  bands=get_data('bands','');
  modes=get_data('modes','');

  if(project_id)
  {
    project_mod=get_data('projects_all',project_id);
    project_members=get_data('rel_project_operator',project_id);
    project_modes=get_data('rel_project_mode',project_id);
    project_bands=get_data('rel_project_band',project_id);

    if(project_mod[project_id]['project_operator'] == '1')
    {
      $('#tr_project_short_name').hide();
      $('#tr_project_modus').hide();
      $('#tr_project_members').hide();	
    }
    else
    {
      $('#tr_project_short_name').show();
      $('#tr_project_modus').show();
      $('#tr_project_members').show();
    }

    project_operator=project_mod[project_id]['project_operator'];
    project_short_name=project_mod[project_id]['project_short_name'];
    project_mode=project_mod[project_id]['project_mode'];
    project_call=project_mod[project_id]['project_call'];
    project_long_name=project_mod[project_id]['project_long_name'];
    project_qrz_user=project_mod[project_id]['project_qrz_user'];
    project_qrz_pass=project_mod[project_id]['project_qrz_pass'];
    project_locator=project_mod[project_id]['project_locator'];
    project_smtp_emailfrom=project_mod[project_id]['project_smtp_emailfrom'];
    project_smtp_server=project_mod[project_id]['project_smtp_server'];
    project_smtp_port=project_mod[project_id]['project_smtp_port'];
    project_smtp_username=project_mod[project_id]['project_smtp_username'];
    project_interface_address=project_mod[project_id]['project_interface_address'];
    project_interface_port=project_mod[project_id]['project_interface_port'];
    if(project_mod[project_id]['project_clublog_ena'] == "1") { $('#project_clublog_ena').prop( "checked", true ); } else { $('#project_clublog_ena').prop( "checked", false ); }
    if(project_mod[project_id]['project_interface_ena'] == "1") { $('#project_interface_ena').prop( "checked", true ); } else { $('#project_interface_ena').prop( "checked", false ); }
    if(project_mod[project_id]['project_interface_voice'] == "1") { $('#project_interface_voice').prop( "checked", true ); } else { $('#project_interface_voice').prop( "checked", false ); }
    $('#project_clublog_auto').val(project_mod[project_id]['project_clublog_auto']);
  }
  else
  {
    project_operator="0";
    project_short_name="";
    project_mode="0";
    project_call="";
    project_long_name="";
    project_qrz_user="";
    project_qrz_pass="";
    project_locator="";
    project_smtp_emailfrom="";
    project_smtp_server="";
    project_smtp_username="";
    project_smtp_port="";
    project_interface_address="";
    project_interface_port=""; 
  }

  project_qrz_pass1="";
  project_qrz_pass2="";
  project_smtp_pass1="";
  project_smtp_pass2="";

  $('.class_project_members').remove();
  $('.class_project_modes').remove();
  $('.class_project_bands').remove();

  document.getElementById('div_error').style.visibility='visible';
  document.getElementById('div_project_change').style.visibility='visible';

  $('#project_id').val(project_id);
  $('#project_operator').val(project_operator);
  $('#project_short_name').val(project_short_name);
  $('#project_mode').val(project_mode);
  $('#project_call').val(project_call);
  $('#project_qrz_user').val(project_qrz_user);
  $('#project_qrz_pass1').val(project_qrz_pass1);
  $('#project_qrz_pass2').val(project_qrz_pass2);
  $('#project_locator').val(project_locator);
  $('#project_smtp_emailfrom').val(project_smtp_emailfrom);
  $('#project_smtp_server').val(project_smtp_server);
  $('#project_smtp_username').val(project_smtp_username);
  $('#project_smtp_port').val(project_smtp_port);
  $('#project_interface_address').val(project_interface_address);
  $('#project_interface_port').val(project_interface_port);

  // operators
  temp="";
  $.each(operators,function(index,value)
  {
    selected="";
    $.each(project_members,function(index2,value2)
    {
      if(value2['operator_id'] == value['operator_id'])
      {
	selected="selected";
      }
    });
    temp=temp+'<option class="class_project_members" '+selected+' value='+value['operator_id']+'>'+value['operator_call']+'</option>';
  }
  );
  $('#project_members').append(temp);
  
  // modes
  temp=""; 
  $.each(modes,function(index,value)
  {
    selected="";
    $.each(project_modes,function(index2,value2)
    {
      if(value2['mode_id'] == value['mode_id'])
      {
	selected="selected";
      }
    });
    temp=temp+'<option class="class_project_modes" '+selected+' value='+value['mode_id']+'>'+value['mode_name']+'</option>';
  }
  );
  $('#project_modes').append(temp);
 
  // bands
  temp=""; 
  $.each(bands,function(index,value)
  {
    selected="";
    $.each(project_bands,function(index2,value2)
    {
      if(value2['band_id'] == value['band_id'])
      {
	selected="selected";
      }
    });
    temp=temp+'<option class="class_project_bands" '+selected+' value='+value['band_id']+'>'+value['band_name']+'</option>';
  }
  );
  $('#project_bands').append(temp);
  if(typeof(project_mod) != 'undefined')
  {
    project_change_modus(project_mod[project_id]['project_operator']);
  }
  project_change_clublog();
  project_change_interface();
}
/*
function delete_data_ask(typ,id)
{
  interval_log_change='NULL';
  $('.class_delete_data_ask').remove();
  document.getElementById('div_delete_data_ask').style.visibility='visible';
  $('#form_delete_data_ask').append("<input class='class_delete_data_ask' type='hidden' name='delete_data_ask_typ' id='delete_data_ask_typ' value='"+typ+"'>");
  if(typ == 'log')
  {
    $('#form_delete_data_ask').append("<p class='class_delete_data_ask'>Willste das wirklich?</p>");
    $('#form_delete_data_ask').append("<input class='class_delete_data_ask' type='hidden' name='delete_data_ask_id' id='delete_data_ask_id' value='"+id+"'>");
  }
  else if(typ == 'operator')
  {
    $('#form_delete_data_ask').append("<p class='class_delete_data_ask'>Willste das wirklich?</p>");
    $('#form_delete_data_ask').append("<input class='class_delete_data_ask' type='hidden' name='delete_data_ask_id' id='delete_data_ask_id' value='"+id+"'>");
  }
}
*/
/*
function delete_operator(operator_id)
{
  operator_mod=get_operator(operator_id);
  operator_call=operator_mod['0']['operator_call'];

  //
  // Entfernen der alten Elemente
  $('.class_operator_change').remove();
  //  
  // holen der DIV's und INPUT Elemente einbauen
  document.getElementById('div_error').style.visibility='visible';
  document.getElementById('div_operator_change').style.visibility='visible';
  $('#form_operator_change').append("<input class='class_operator_change' type='hidden' name='operator_id' id='operator_id' value='"+operator_id+"'>");
  $('#form_operator_change').append("<p class='class_operator_change'>Willste das wirklich?</p>");
  $('#form_operator_change').append("<br class='class_operator_change'>");
  $('#form_operator_change').append("<input class='class_operator_change' type='button' onclick=remove_operator(); value='speichern' name='speichern'>");
  $('#form_operator_change').append("<input class='class_operator_change' type='button' onclick=document.getElementById('div_operator_change').style.visibility='hidden'; value='abbruch' name='abbruch'>");
}
*/
