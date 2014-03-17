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

  $('#div_log_change_callinfo2').append("<a class='class_log_change_locinfo'>QRB: "+locinfo['distance']+"km</a><br class='class_log_change_locinfo'>");
  $('#div_log_change_callinfo2').append("<a class='class_log_change_locinfo'>Bearing: "+locinfo['bearing']+"</a><br class='class_log_change_locinfo'>");
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

  if(typeof(callinfo) != undefined)
  {
    if(typeof(callinfo['Session']) != "undefined")
    {
      if(typeof(callinfo['Session']['Error']) == 'string')
      {
	$('#div_log_change_error').append("<a class='class_log_change_callinfo'>Error QRZ.COM:"+callinfo['Session']['Error']+"</a>");
      }
      else
      {
	$('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['Callsign']['fname']+" "+callinfo['Callsign']['name']+"</a><br class='class_log_change_callinfo'>");
	$('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['Callsign']['addr1']+"</a><br class='class_log_change_callinfo'>");
	$('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['Callsign']['addr2']+"</a><br class='class_log_change_callinfo'><br class='class_log_change_callinfo'>");
	$('#div_log_change_callinfo1').append("<a class='class_log_change_callinfo'>"+callinfo['Callsign']['url']+"</a>");
	$('#div_log_change_callinfo1_picture').append("<img class='class_log_change_callinfo' height='100%' src='"+callinfo['Callsign']['image']+"' alt="+callinfo['Callsign']['call']+">");
	if(formchange == '1')
	{
	  $('#log_loc').val(callinfo['Callsign']['grid']);
	  $('#log_qth').val(callinfo['Callsign']['addr2']);
	  $('#log_name').val(callinfo['Callsign']['fname']);
	  $('#log_manager').val(callinfo['Callsign']['qslmgr']);
	}
	log_change_loc();
      }
    }
    $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>Anzahl Total/ich: "+callinfo.callinfo_total_project['0']['COUNT(*)']+" / "+callinfo.callinfo_total_operator['0']['COUNT(*)']+")</a><br class='class_log_change_callinfo'>"); 
    if(callinfo.callinfo_project == null)
    {
      $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO Projekt: N/A</a><br class='class_log_change_callinfo'>"); 
    } 
    else
    {
      $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO Projekt: "+callinfo.callinfo_project['0']['log_freq']+" / "+callinfo.callinfo_project['0']['log_time']+" / "+callinfo.callinfo_project['0'].mode_name+"(gesamt: "+callinfo.callinfo_project.length+")</a><br class='class_log_change_callinfo'>");
    }
    if(callinfo.callinfo_operator == null)
    {
      $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO mit mir: N/A</a><br class='class_log_change_callinfo'>");
    }
    else
    { 
      $('#div_log_change_callinfo4').append("<a class='class_log_change_callinfo'>letztes QSO mit mir: "+callinfo.callinfo_operator['0']['log_freq']+" / "+callinfo.callinfo_operator['0']['log_time']+" / "+callinfo.callinfo_operator['0'].mode_name+"(gesamt: "+callinfo.callinfo_operator.length+")</a><br class='class_log_change_callinfo'>");
    }
  }
}
