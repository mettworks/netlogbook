function get_callinfo_logs(call)
{
  url="/getdata.php?table=callinfo_logs&call="+call;
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      dataType: "json",
      type: "get",
      contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	$.each(temp,function(index,value)
	{
	  data[index]=value;
	});	
      }
    }
  );
  return(data);
}
function interface_vox_stop()
{
  url="http://"+session['project_interface_address']+":"+session['project_interface_port']+"/vox_stop";
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      type: "get",
      async: false,
      success: function(temp)
      {
	data=temp;
      }
    }
  );
  return(data);
}

function interface_vox_start()
{
  url="http://"+session['project_interface_address']+":"+session['project_interface_port']+"/vox_start";
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      type: "get",
      async: false,
      success: function(temp)
      {
	data=temp;
      }
    }
  );
  return(data);
}
function interface_voice_play()
{
  url="http://"+session['project_interface_address']+":"+session['project_interface_port']+"/voice_play";
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      type: "get",
      async: false,
      success: function(temp)
      {
	data=temp;
      }
    }
  );
  return(data);
} 
function interface_voice_stop()
{
  url="http://"+session['project_interface_address']+":"+session['project_interface_port']+"/voice_stop";
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      type: "get",
      async: false,
      success: function(temp)
      {
	data=temp;
      }
    }
  );
  return(data);
} 

function get_interface_qrg()
{
  url="http://"+session['project_interface_address']+":"+session['project_interface_port']+"/get_freq";
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      //dataType: "json",
      type: "get",
      //contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	data=temp;
      }
    }
  );
  return(data);
}

function get_callinfo_qrz(call)
{
  url="/getdata.php?table=callinfo_qrz&call="+call;
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      dataType: "json",
      type: "get",
      contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	$.each(temp,function(index,value)
	{
	  data[index]=value;
	});	
      }
    }
  );
  return(data);
}
function get_callinfo_aprs(call)
{
  url="/getdata.php?table=callinfo_aprs&call="+call;
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      dataType: "json",
      type: "get",
      contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	$.each(temp,function(index,value)
	{
	  data[index]=value;
	});	
      }
    }
  );
  return(data);
}

function get_locinfo(loc)
{
  url="/getdata.php?table=locinfo&loc="+loc;
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      dataType: "json",
      type: "get",
      contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	$.each(temp,function(index,value)
	{
	  data[index]=value;
	});	
      }
    }
  );
  return(data);
}

function get_deginfo(lon,lat)
{
  url="/getdata.php?table=deginfo&lon="+lon+"&lat="+lat;
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      dataType: "json",
      type: "get",
      contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	$.each(temp,function(index,value)
	{
	  data[index]=value;
	});	
      }
    }
  );
  return(data);
}


function get_data(table,id)
{
  url="/getdata.php?table="+table+"&id="+id;
  var data=new Array();
  $.ajax
  (
    {
      url: url,
      dataType: "json",
      type: "get",
      contentType : "application/json; charset=utf-8",
      async: false,
      success: function(temp)
      {
	data=temp;
      }
    }
  );
  return data;
}
