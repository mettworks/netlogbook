function get_callinfo(call)
{
  url="/getdata.php?table=callinfo&call="+call;
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
