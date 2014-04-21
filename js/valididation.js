function save_settings(onlyoperator)
{
  var datastring="action=savesettings&onlyoperator="+onlyoperator;
  $.ajax
  (
    {
      type: "GET",
      url: "save.php",
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
					  "log_call="+escape(log_call)+"&"+
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
					  "log_dok="+escape(log_dok)+"&"+
					  "log_name="+escape(log_name)+"&"+
					  "log_time_auto="+log_time_auto+"&"+
					  "log_notes="+escape(log_notes)+"&"+
					  "log_qth="+escape(log_qth)+"&"+
					  "log_loc="+escape(log_loc)+"&"+
					  "log_manager="+escape(log_manager);

  }

  if(typ == 'operator')
  {
    var operator_call=$('#operator_call').val();
    var operator_id=$('#operator_id').val();
    var operator_mail=$('#operator_mail').val();
    var operator_pass1=$('#operator_pass1').val();
    var operator_pass2=$('#operator_pass2').val();
    var operator_role=$('#operator_role').val();
    var operator_pwm=$('#operator_pwm').prop('checked')  
    var datastring="action=mod&typ=operator&"+
					  "operator_id="+operator_id+"&"+
					  "operator_call="+operator_call+"&"+
					  "operator_pass1="+operator_pass1+"&"+
					  "operator_pass2="+operator_pass2+"&"+
					  "operator_role="+operator_role+"&"+
					  "operator_pwm="+operator_pwm+"&"+
					  "operator_mail="+operator_mail;
  }
  if(typ == 'project')
  {
    var project_short_name=$('#project_short_name').val();
    var project_id=$('#project_id').val();
    var project_members=$('#project_members').val();
    var project_modes=$('#project_modes').val();
    var project_bands=$('#project_bands').val();
    var project_qrz_user=$('#project_qrz_user').val();
    var project_qrz_pass1=$('#project_qrz_pass1').val();
    var project_qrz_pass2=$('#project_qrz_pass2').val();
    var project_locator=$('#project_locator').val();

    var datastring="action=mod&typ=project&project_id="+project_id+"&project_short_name="+project_short_name+"&project_members="+project_members+"&project_qrz_user="+project_qrz_user+"&project_qrz_pass1="+project_qrz_pass1+"&project_qrz_pass2="+project_qrz_pass2+"&project_modes="+project_modes+"&project_bands="+project_bands+"&project_locator="+project_locator;
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
        data: datastring,
        success: function(html)
        {
          $("div#div_error").html(html);
        }
      }
    );
}

function delete_data(typ,id)
{
  var typ=$('#delete_data_ask_typ').val();
  var id=$('#delete_data_ask_id').val();
  $.ajax
    (
      {
        type: "GET",
        url: "save.php",
        data: "action=del&typ="+typ+"&id="+id,
        success: function(html)
        {
          $("div#div_error").html(html);
        }
      }
    );

}
