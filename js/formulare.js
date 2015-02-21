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
    log_dok=logs[log_id]['log_dok'];
    log_loc=logs[log_id]['log_loc'];
    log_manager=logs[log_id]['log_manager'];
    log_notes=logs[log_id]['log_notes'];
    log_time=logs[log_id]['log_time'];
    log_qth=logs[log_id]['log_qth'];
    log_name=logs[log_id]['log_name'];
    log_time_hr_date=logs[log_id]['log_time_hr_date'];
    log_time_hr_time=logs[log_id]['log_time_hr_time'];
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
    var now = new Date();
    var monat = 1 + parseInt(now.getUTCMonth());
    $('#log_time_hr_date').datetimepicker({value: ('0'+now.getUTCDate()).slice(-2)+'.'+('0'+monat).slice(-2)+'.'+now.getUTCFullYear()});
    $('#log_time_hr_time').datetimepicker({value: ('0'+now.getUTCHours()).slice(-2)+':'+('0'+now.getUTCMinutes()).slice(-2)});
  }
  if(log_freq > 1000)
  {
    log_freq=log_freq/1000+"m";
  }
  else if(log_freq > 1000000)
  {
    log_freq=log_freq/1000000+"G";
  }
  else
  {
    log_freq=log_freq+"k";
  }

  $('.error_text').remove();
  $('.class_mode_id').remove();

  document.getElementById('div_log_change').style.visibility='visible';

  $('#log_call').val(log_call);
  $('#log_id').val(log_id);
  $('#log_freq').val(log_freq);
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
  $('#log_manager').val(log_manager);


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

  if(!log_id)
  {
    if(typeof(settings[0]) != 'undefined')
    {
      if(settings[0]['setting_log_time_auto'])
      {
	$('#log_time_auto').attr("checked",true); 
      }
      else
      {
	$('#log_time_auto').removeAttr("checked");
      }
    }
    else
    {
      $('#log_time_auto').attr("checked",true);
    }
  }
  else
  {
    $('#log_time_auto').removeAttr("checked");
  }
  log_change_time();
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
    operator_mod=get_data('operators',operator_id);
    operator_call=operator_mod[operator_id]['operator_call'];
    operator_mail=operator_mod[operator_id]['operator_mail'];
    operator_role=operator_mod[operator_id]['operator_role'];
  }
  else
  {
    operator_call="";
    operator_mail="";
  }

  operator_pass1="";
  operator_pass2="";

  //
  // the roles are hardcoded in index.php 
  $('#operator_role').val(operator_role);

  document.getElementById('div_error').style.visibility='visible';
  document.getElementById('div_operator_change').style.visibility='visible';

  $('#operator_call').val(operator_call);
  $('#operator_id').val(operator_id);
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

    project_short_name=project_mod[project_id]['project_short_name'];
    project_mode=project_mod[project_id]['project_mode'];
    project_call=project_mod[project_id]['project_call'];
    project_long_name=project_mod[project_id]['project_long_name'];
    project_qrz_user=project_mod[project_id]['project_qrz_user'];
    project_qrz_pass=project_mod[project_id]['project_qrz_pass'];
    project_locator=project_mod[project_id]['project_locator'];
    project_members=get_data('rel_project_operator',project_id);
    project_modes=get_data('rel_project_mode',project_id);
    project_bands=get_data('rel_project_band',project_id);
    project_smtp_emailfrom=project_mod[project_id]['project_smtp_emailfrom'];
    project_smtp_server=project_mod[project_id]['project_smtp_server'];
    project_smtp_port=project_mod[project_id]['project_smtp_port'];
    project_smtp_username=project_mod[project_id]['project_smtp_username'];
    if(project_mod[project_id]['project_clublog_ena'] == "1") { $('#project_clublog_ena').prop( "checked", true ); } else { $('#project_clublog_ena').prop( "checked", false ); }
    $('#project_clublog_auto').val(project_mod[project_id]['project_clublog_auto']);
  }
  else
  {
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
  project_change_modus();
  project_change_clublog();
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
