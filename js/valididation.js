function send_dxcluster_spot()
{
  dxcluster_send_qrg=$('#dxcluster_send_qrg').val();
  dxcluster_send_comment=$('#dxcluster_send_comment').val();
  dxcluster_send_spotter=$('#dxcluster_send_spotter').val();
  dxcluster_send_call=$('#dxcluster_send_call').val();
  var datastring="action=send_dxcluster_spot&dxcluster_send_qrg="+dxcluster_send_qrg+"&dxcluster_send_comment="+dxcluster_send_comment+"&dxcluster_send_spotter="+dxcluster_send_spotter+"&dxcluster_send_call="+dxcluster_send_call;
  check=confirm("Wirklich Spot absenden:\nAbsender:"+dxcluster_send_spotter+"\nRufzeichen:"+dxcluster_send_call+"\nFrequenz:"+dxcluster_send_qrg+"\nKommentar:"+dxcluster_send_comment);
  if(check == true)
  {
    $.ajax
    (
      {
	type: "GET",
	url: "save.php",
	contentType: "application/x-www-form-urlencoded; charset=UTF-8",
	data: datastring,
	success: function(html)
	{
	  $("div#div_error").html(html);
	}
      }
    );
    reload_dxcluster();
  }
}

function export_clublog()
{
  project_id=$('#project_id').val();
  var datastring="action=export_clublog&project_id="+project_id;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
}

function save_settings_operators_projects()
{
  $.ajax
  (
    {
      type: "POST",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: settings_operators_projects,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
 
}

function save_settings_op()
{
  $.ajax
  (
    {
      type: "POST",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: settings_op,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
} 

function save_settings_table_logs(setting_table_logs)
{
  $.ajax
  (
    {
      type: "POST",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: setting_table_logs,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );

}

function save_project_session(project_id)
{
  var datastring="action=save_project_session&project_id="+project_id;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: datastring,
      async: true,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
}

function save_dxcluster_settings()
{
  band_id=$('#setting_table_dxcluster_bands').val();
  var datastring="action=save_dxcluster_settings&band_id="+band_id;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      async: false,
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
  reload_dxcluster();
}

function save_map_settings()
{
  mode_id=$('#map_settings_modes').val();
  band_id=$('#map_settings_bands').val();
  operator_id=$('#map_settings_operators').val();
  if($('#map_settings_filter').is(':checked'))
  {
    filter='0';
  }
  else
  {
    filter='1';
  }
  var datastring="action=save_map_settings&mode_id="+mode_id+"&band_id="+band_id+"&operator_id="+operator_id+"&filter="+filter;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      async: false,
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
  loadXML();
}

function save_interface_voice_int()
{
  var interface_voice_int=$('#interface_voice_int').val();
  var datastring="action=save_interface_voice_int&interface_voice_int="+interface_voice_int;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
}

function save_settings(onlyoperator)
{
  var datastring="action=savesettings&onlyoperator="+onlyoperator;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
}
function save_settings_import(qrzcache)
{
  var qrzcache=$('#log_import_qrzcache').prop('checked');

  if(qrzcache)
  {
    var datastring="action=savesettingsimport&qrzcache=1";
  }
  else
  {
    var datastring="action=savesettingsimport&qrzcache=0";
  }
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
}

function import_log_file(filename)
{
  var datastring="action=import&typ=log&filename="+filename;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: datastring,
      success: function(html)
      {
	$("div#div_error").html(html);
      }
    }
  );
} 

function write_data(typ,id)
{
  if(typ == 'log')
  {
    var log_call=$('#log_call').val();
    var log_id=$('#log_id').val();
    var log_freq=$('#log_freq').val();
    var mode_id=$('#mode_id').val();
    var log_signal_rx=$('#log_signal_rx').val();
    var log_signal_tx=$('#log_signal_tx').val();
    var log_rst_rx_0=$('#log_rst_rx_0').val();
    var log_rst_rx_1=$('#log_rst_rx_1').val();
    var log_rst_rx_2=$('#log_rst_rx_2').val();
    var log_rst_tx_0=$('#log_rst_tx_0').val();
    var log_rst_tx_1=$('#log_rst_tx_1').val();
    var log_rst_tx_2=$('#log_rst_tx_2').val();
    var log_dok=$('#log_dok').val();
    var log_notes=$('#log_notes').val();
    var log_name=$('#log_name').val();
    var log_qth=$('#log_qth').val();
    var log_loc=$('#log_loc').val();
    var log_manager=$('#log_manager').val();
    var log_time_auto=$('#log_time_auto').prop('checked');
    var log_qsl_tx=$('#log_qsl_tx').prop('checked');
    var log_qsl_rx=$('#log_qsl_rx').prop('checked');

    if(log_qsl_tx == true)
    {
      log_qsl_tx='1';
    }
    else
    {
      log_qsl_tx='0';
    }
    if(log_qsl_rx == true)
    {
      log_qsl_rx='1';
    }
    else
    {
      log_qsl_rx='0';
    }

    //
    // If the checkbox is checked, we get the time from the client
    // f the time differs between client and server it should be better to use always the same time
    if(log_time_auto)
    {
      var now = new Date();
      var monat = 1 + parseInt(now.getUTCMonth());
      $('#log_time_hr_date').datetimepicker({value: ('0'+now.getUTCDate()).slice(-2)+'.'+('0'+monat).slice(-2)+'.'+now.getUTCFullYear()});
      $('#log_time_hr_time').datetimepicker({value: ('0'+now.getUTCHours()).slice(-2)+':'+('0'+now.getUTCMinutes()).slice(-2)});
    }
    var log_time_hr_date=$('#log_time_hr_date').val();
    var log_time_hr_time=$('#log_time_hr_time').val();

    // durch den Zeilenumbruchkram wird es leserlicher...
    var datastring="action=mod&typ=log&"+
					  "log_id="+log_id+"&"+
					  "log_call="+log_call+"&"+
					  "log_time_hr_date="+log_time_hr_date+"&"+
					  "log_time_hr_time="+log_time_hr_time+"&"+
					  "log_freq="+log_freq+"&"+
					  "mode_id="+mode_id+"&"+
					  "log_rst_rx_0="+log_rst_rx_0+"&"+
					  "log_rst_rx_1="+log_rst_rx_1+"&"+
					  "log_rst_rx_2="+log_rst_rx_2+"&"+
					  "log_rst_tx_0="+log_rst_tx_0+"&"+
					  "log_rst_tx_1="+log_rst_tx_1+"&"+
					  "log_rst_tx_2="+log_rst_tx_2+"&"+
					  "log_signal_tx="+log_signal_tx+"&"+
					  "log_signal_rx="+log_signal_rx+"&"+
					  "log_dok="+log_dok+"&"+
					  "log_name="+log_name+"&"+
					  "log_time_auto="+log_time_auto+"&"+
					  "log_notes="+log_notes+"&"+
					  "log_qth="+log_qth+"&"+
					  "log_loc="+log_loc+"&"+
					  "log_manager="+log_manager+"&"+
					  "log_qsl_tx="+log_qsl_tx+"&"+
					  "log_qsl_rx="+log_qsl_rx;


  }

  if(typ == 'operator')
  {
    var operator_call=$('#operator_call').val();
    var operator_id=$('#operator_id').val();
    var operator_mail=$('#operator_mail').val();
    var operator_name=$('#operator_name').val();
    var operator_pass1=$('#operator_pass1').val();
    var operator_pass2=$('#operator_pass2').val();
    var operator_role=$('#operator_role').val();
    var operator_pwm=$('#operator_pwm').prop('checked')  
    var datastring="action=mod&typ=operator&"+
					  "operator_id="+operator_id+"&"+
					  "operator_call="+operator_call+"&"+
					  "operator_name="+operator_name+"&"+
					  "operator_pass1="+operator_pass1+"&"+
					  "operator_pass2="+operator_pass2+"&"+
					  "operator_role="+operator_role+"&"+
					  "operator_pwm="+operator_pwm+"&"+
					  "operator_mail="+operator_mail;
  }
  if(typ == 'project')
  {
    var project_operator=$('#project_operator').val();
    var project_short_name=$('#project_short_name').val();
    var project_mode=$('#project_mode').val();
    var project_call=$('#project_call').val();
    var project_id=$('#project_id').val();
    var project_members=$('#project_members').val();
    var project_modes=$('#project_modes').val();
    var project_bands=$('#project_bands').val();
    var project_qrz_user=$('#project_qrz_user').val();
    var project_qrz_pass1=$('#project_qrz_pass1').val();
    var project_qrz_pass2=$('#project_qrz_pass2').val();
    var project_locator=$('#project_locator').val();
    var project_clublog_ena=$('#project_clublog_ena').prop('checked');
    var project_smtp_emailfrom=$('#project_smtp_emailfrom').val();
    var project_smtp_pass1=$('#project_smtp_pass1').val();
    var project_smtp_pass2=$('#project_smtp_pass2').val();
    var project_smtp_server=$('#project_smtp_server').val();
    var project_smtp_username=$('#project_smtp_username').val();
    var project_smtp_port=$('#project_smtp_port').val();
    var project_clublog_auto=$('#project_clublog_auto').val();
    var project_interface_ena=$('#project_interface_ena').prop('checked');
    var project_interface_address=$('#project_interface_address').val();
    var project_interface_port=$('#project_interface_port').val();
    var project_interface_voice=$('#project_interface_voice').prop('checked');

    var datastring="action=mod&typ=project&project_operator="+project_operator+"&project_smtp_username="+project_smtp_username+"&project_smtp_port="+project_smtp_port+"&project_id="+project_id+"&project_clublog_ena="+project_clublog_ena+"&project_smtp_emailfrom="+project_smtp_emailfrom+"&project_smtp_pass1="+project_smtp_pass1+"&project_smtp_pass2="+project_smtp_pass2+"&project_smtp_server="+project_smtp_server+"&project_clublog_auto="+project_clublog_auto+"&project_short_name="+project_short_name+"&project_mode="+project_mode+"&project_call="+project_call+"&project_members="+project_members+"&project_qrz_user="+project_qrz_user+"&project_qrz_pass1="+project_qrz_pass1+"&project_qrz_pass2="+project_qrz_pass2+"&project_modes="+project_modes+"&project_bands="+project_bands+"&project_locator="+project_locator+"&project_interface_ena="+project_interface_ena+"&project_interface_address="+project_interface_address+"&project_interface_port="+project_interface_port+"&project_interface_voice="+project_interface_voice;
  }
  if(typ == 'project_kill_qrz_sess')
  {
    var project_id=$('#project_id').val();
    var datastring="action=mod&typ=project_kill_qrz_sess&project_id="+project_id;
  }

  if(typeof(completed) != 'undefined')
  {
    datastring=datastring+"&completed="+completed;
  }
  $.ajax
    (
      {
        type: "GET",
        url: "save.php",
	contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        data: datastring,
        success: function(html)
        {
          $("div#div_error").html(html);
        }
      }
    );
  session=get_data('session','');
}

function delete_log(id)
{
  log=get_data('logs',id);
  check=confirm('Call: '+log[id]['log_call']+'\nwirklich loeschen?');
  if(check == true)
  {
    $.ajax
    (
      {
        type: "GET",
	url: "save.php",
	contentType: "application/x-www-form-urlencoded; charset=UTF-8",
	data: "action=del&typ=log&id="+id,
	success: function(html)
	{
	  $("div#div_error").html(html);
	}
      }
    );
  }
}
function delete_operator(id)
{
  operator=get_data('operators',id);
  check=confirm('Operator: '+operator[id]['operator_call']+'\nwirklich loeschen?');
  if(check == true)
  {
    $.ajax
    (
      {
        type: "GET",
	url: "save.php",
	contentType: "application/x-www-form-urlencoded; charset=UTF-8",
	data: "action=del&typ=operator&id="+id,
	success: function(html)
	{
	  $("div#div_error").html(html);
	}
      }
    );
  }
}
function delete_project(id)
{
  project=get_data('projects',id);
  check=confirm('Project: '+project[id]['project_short_name']+'\nwirklich loeschen?');
  if(check == true)
  {
    $.ajax
    (
      {
        type: "GET",
	url: "save.php",
	contentType: "application/x-www-form-urlencoded; charset=UTF-8",
	data: "action=del&typ=project&id="+id,
	success: function(html)
	{
	  $("div#div_error").html(html);
	}
      }
    );
  }
}
