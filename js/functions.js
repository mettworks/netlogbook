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
    table_logs.fnDraw();
  }
  else
  {
    save_settings('0');
    table_logs.fnDraw();
  }
}

function reload_tables_log_change()
{
  table_logsfromall.fnDraw();
}
function reload_tables_log()
{
  table_logs.fnDraw();
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
  if(typeof(callinfo['qrzcom']['error']) == 'string')
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
    if(callinfo['qrzcom']['imagestatus'] == "0")
    {
      var op_picture="/cache/qrzcom/"+callinfo['qrzcom']['image'];
      var div_width=$('#div_log_change_callinfo1_picture').width();
      var div_height=$('#div_log_change_callinfo1_picture').height();

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
      $('#div_log_change_callinfo1_picture').append("<a class='class_log_change_callinfo'>TEMP FEHLER</a>"); 
    }
    else
    {
      $('#div_log_change_callinfo1_picture').append("<a class='class_log_change_callinfo'>KEIN BILD</a>"); 
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
