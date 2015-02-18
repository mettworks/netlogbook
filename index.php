<?php
  include('functions.php');
  checklogin();
  include('settings.php');
  $mysql=mysql_c();
  checkcronjob();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
  <html>
    <head>
      <meta charset="utf-8">
      <title></title>
      <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css">
      <link rel="stylesheet" type="text/css" href="/js/DataTables-1.10.4/media/css/jquery.dataTables.css">
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <script type="text/javascript" src="/js/DataTables-1.10.4/media/js/jquery.js"></script>
      <script type="text/javascript" src="/js/DataTables-1.10.4/media/js/jquery.dataTables.js"></script>
      <script type="text/javascript" src="js/formulare.js"></script>
      <script type="text/javascript" src="js/getdata.js"></script> 
      <script type="text/javascript" src="js/valididation.js"></script>
      <script type="text/javascript" src="js/functions.js"></script>
      <script type="text/javascript" src="js/gmaps.js"></script> 
      <script type="text/javascript" src="js/shortcut.js"></script>
      <script type="text/javascript" src="js/jquery.ui.widget.js"></script>
      <script type="text/javascript" src="js/jquery.iframe-transport.js"></script>
      <script type="text/javascript" src="js/jquery.fileupload.js"></script>
      <script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
      <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    </head>
    <body>
<!--     <body onload="change_settings_dxcluster_setting();fill_form_settings_op_table_logs();fill_form_settings_op();set_table_logs();set_title();"> -->
      <p>
        <script>
	var table_logs;
	var table_projects;
	var table_operators;
	var table_monitor_logs;
	var table_monitor_total;
	var table_monitor_modes;
	var table_monitor_bands;
	var table_monitor_qsos;

	settings_op_table_logs={};
	settings_op_table_logs=get_data('settings_table_logs','');
	settings_op={};
	settings_op=get_data('settings_op','');

	change_settings_dxcluster_setting();

	modes=get_data('mode','');
	operators=get_data('operator','');
	$(function() {
	    $('#fileupload').fileupload(
	      {
		dataType: 'json',
		done: function (e, data) 
		{
		  $.each(data.result.files, function (index, file) 
		  {
		    import_log_file(file.name);
		  });
		}
	      }
	    );
	  $('#log_time_hr_date').datetimepicker({
	    timepicker: false,
	    format:'d.m.Y',
	    maxDate:0,
	  });
	  $('#log_time_hr_time').datetimepicker({
	    datepicker: false,
	    format:'H:i',
	    step:15,
	  });
	});
	$(document).ready(
	  function() 
	  {
	    table_dxcluster=$('#table_dxcluster').DataTable
	    (
	      {
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=dxcluster",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
                "aoColumns":
		[
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		]
	    });
	    table_monitor_logs=$('#table_monitor_logs').DataTable
	    (
	      {
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=monitor_logs",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
                "aoColumns":
		[
		    null,
		    null,
		    null,
		    null,
		    null,
		]
	    });
	    table_monitor_modes=$('#table_monitor_modes').DataTable
	    (
	      {
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=monitor_modes",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
                "aoColumns":
		[
		    null,
		    null,
		]
	    });
	    table_monitor_bands=$('#table_monitor_bands').DataTable
	    (
	      {
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=monitor_bands",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
                "aoColumns":
		[
		    null,
		    null,
		]
	    });
	    table_monitor_qsos=$('#table_monitor_qsos').DataTable
	    (
	      {
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=monitor_qsos",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
		"columnClasses": "column",
                "aoColumns":
		[
		    null,
		    null,
		    { 
		      "sClass": "counter",
		    } 
		]
	    });
	    table_monitor_total=$('#table_monitor_total').DataTable
	    (
	      {
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=monitor_total",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
                "aoColumns":
		[
		    null,
		]
	    });
	    table_logs=$('#table_logs').DataTable
	    (
	      {
		"autoWidth": true,
		"bProcessing": true,
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=logs",
		"bStateSave": true,
		"pagingType": "simple",
		"stateLoadCallback": function (settings) {
		  var o;
		  $.ajax( {
		    "url": "/getdata.php?table=settings_table_logs",
		    "async": false,
		    "dataType": "json",
		    "contentType" : "application/json; charset=utf-8",
		    "type": "GET",
		    "success": function (json) {
		        o = json;
		    }
		  });
		  return o;
		},
		"oLanguage": 
		{
		  "sLengthMenu": 'Display <select>'+
		    '<option value="10">10</option>'+
		    '<option value="50">50</option>'+
		    '<option value="100">100</option>'+
		    '<option value="500">500</option>'+
		    '<option value="1000">1000</option>'+
		    '<option value="-1">All</option>'+
		  '</select> records'
		},
                "aoColumns":
		[
		  null,
		  null,
		  null,
		  {
		    "mRender": function ( data, type, full ) 
		    {
		      if(settings_op['frequency_prefix'] == 0)
		      {
			return "<style=text-align:right;>"+(Math.round((full[3]/1000) * 1000 )/1000).toFixed(3)+"Mhz";
		      }
		      else
		      {	
			return "<style=text-align:right;>"+full[3]+"khz";
		      }
		    },
		    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) 
		    {
		      $(nTd).css('text-align', 'right');
		    },
		  },
		  null,
		  null,
		  null,
		  null,
		  null,
		  null,
		  null,
		  null,
		  null,
		  null,
		  { 
		    "bVisible": false, 
		    "bSortable":false
		  },
		  {
		    "mRender": function ( data, type, full ) 
		    {
		      return '<img src="images/edit.png" alt="bearbeiten" onclick="change_log(\''+full[14]+'\');">';

		    },
		    'bSortable': false,
		  },
		  {
		    "mRender": function ( data, type, full )
		    {
		      return '<img src="images/delete.png" alt="loeschen" onclick="delete_data_ask(\'log\',\''+full[14]+'\');">';
		    },
		    'bSortable': false,
		  }
		],
		"stateSaveCallback": function(setting,data) 
		{
		  data['action']="save_settings_table_logs";
		  $.ajax( {
		    "url": "/save.php",
		    "data": data,
		    "dataType": "json",
		    "type": "POST",
		    "success": function () {}
		  } );
		},

	    });
	    table_logsfromme=$('#table_logsfromme').DataTable
	    (
	      {
		"bProcessing": true,
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=logsfromme",
		"bInfo": false,
		"bPaginate": false,
		"bFilter": false,
		"bSort": false,
                "aoColumns":
		[
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		]
	    });
	    table_logsfromall=$('#table_logsfromall').DataTable
	    (
	      {
		"bProcessing": true,
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=logsfromall",
		"bInfo": false,
		"bSort": false,
		"bPaginate": false,
		"bFilter": false,
                "aoColumns":
		[
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,

		]
	    });
	    table_operators=$('#table_operators').DataTable
	    (
	      {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "/getdata.php?typ=datatable&table=operators",
		"aoColumns":
		[
		  null,
		  { 
		    "bVisible": false
		  },
		  {
                    "mRender": function ( data, type, full ) 
                    {
                      return '<img src="images/edit.png" alt="bearbeiten" onclick="change_operator(\''+full[1]+'\');">';

                    },
                    'bSortable': false,
		  },
		  {
                    "mRender": function ( data, type, full ) 
                    {
                      return '<img src="images/delete.png" alt="loeschen" onclick="delete_data_ask(\'operator\',\''+full[1]+'\');">';

                    },
                    'bSortable': false,
		  }
		]
	    });
	    table_projects=$('#table_projects').DataTable
            (
              {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "/getdata.php?typ=datatable&table=projects",
                "aoColumns":
                [
                  null,
 		  {
		    "bVisible": false
		  },
		  {
		    "bVisible": false
		  },
		  {
		    "bVisible": false
		  },
                  {
                    "mRender": function ( data, type, full ) 
                    {
                      return '<img src="images/edit.png" alt="bearbeiten" onclick="change_project(\''+full[3]+'\');">';

                    },
                    'bSortable': false,
                  },
                  {
 
                    "mRender": function ( data, type, full ) 
                    {
                      return '<img src="images/delete.png" alt="loeschen" onclick="delete_project(\''+full[3]+'\');">';

                    },
                    'bSortable': false,
                  }

                ]
            });
	    $('#table_monitor_logs').css( 'display', 'block' );
	    table_monitor_logs.columns.adjust().draw();
	    $('#table_operators').css( 'display', 'block' );
	    table_operators.columns.adjust().draw();
	    $('#table_projects').css( 'display', 'block' );
	    table_projects.columns.adjust().draw();
	    $('#table_monitor_total').css( 'display', 'block' );
	    table_monitor_total.columns.adjust().draw();
  	    $('#table_monitor_modes').css( 'display', 'block' );
	    table_monitor_modes.columns.adjust().draw();
	    $('#table_monitor_bands').css( 'display', 'block' );
	    table_monitor_bands.columns.adjust().draw();
	    $('#table_monitor_qsos').css( 'display', 'block' );
	    table_monitor_qsos.columns.adjust().draw();
	    $('#table_dxcluster').css( 'display', 'block' );
	    table_dxcluster.columns.adjust().draw();

	    fill_form_settings_op_table_logs();
	    fill_form_settings_op();
	    set_title();
	    set_table_logs();
	    load();
	  });
      </script></p>
      <div id="div_navi_top">
	<input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('0');document.getElementById('div_settings').style.visibility='hidden';document.getElementById('div_map').style.visibility='hidden';document.getElementById('div_monitor').style.visibility='hidden';document.getElementById('div_logs').style.visibility='visible'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='hidden';document.getElementById('div_dxcluster').style.visibility='hidden';" value="Log">
	<input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('1');document.getElementById('div_settings').style.visibility='hidden';document.getElementById('div_map').style.visibility='hidden'; document.getElementById('div_monitor').style.visibility='visible';document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='hidden';document.getElementById('div_dxcluster').style.visibility='hidden';" value="Monitor">
	<input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('0');document.getElementById('div_settings').style.visibility='hidden';document.getElementById('div_map').style.visibility='visible';document.getElementById('div_monitor').style.visibility='hidden';document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='hidden';document.getElementById('div_dxcluster').style.visibility='hidden';" value="Karte">
	<input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('0');document.getElementById('div_settings').style.visibility='visible';document.getElementById('div_map').style.visibility='hidden';document.getElementById('div_monitor').style.visibility='hidden';document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='hidden';document.getElementById('div_dxcluster').style.visibility='hidden';" value="Einstellungen">
	<input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('0');document.getElementById('div_settings').style.visibility='hidden';document.getElementById('div_map').style.visibility='hidden';document.getElementById('div_monitor').style.visibility='hidden';document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='hidden';document.getElementById('div_dxcluster').style.visibility='visible';" value="DXCluster">

	<?php
	if($_SESSION['operator_role']==0)
	{
	  ?>
	  <input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('0');document.getElementById('div_settings').style.visibility='hidden';document.getElementById('div_map').style.visibility='hidden';document.getElementById('div_monitor').style.visibility='hidden';document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='visible'; document.getElementById('div_operators').style.visibility='hidden';document.getElementById('div_dxcluster').style.visibility='hidden';" value="Projekte">
	  <input type="button" onclick="set_reload_dxcluster('0');set_reload_monitor('0');document.getElementById('div_settings').style.visibility='hidden';document.getElementById('div_map').style.visibility='hidden';document.getElementById('div_monitor').style.visibility='hidden';document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='visible';document.getElementById('div_dxcluster').style.visibility='hidden';" value="OP's">
	<?php
	}
	?>
	<select name="projects" id="projects" onchange="set_project()">
	  <?php
	  $sql="SELECT project_id,project_short_name FROM projects";
	  $projects=mysql_fragen($sql,'project_id');

	  if($_SESSION['operator_role'] == 0)
	  {
	    foreach($projects as $project_id => $project)
	    {
	      if($_SESSION['project_id'] == $project_id)
	      {
		?>
		<option selected value=<?=$project_id?>><?=$project['project_short_name']?></option>
		<?php
	      }
	      else
	      {
		?>
		<option value=<?=$project_id?>><?=$project['project_short_name']?></option>
		<?php
	      }
	    }
	  }
	  else
	  {
	    foreach($_SESSION['operator_projects'] as $operator_projects)
	    {
	      if($_SESSION['project_id'] == $projects[$operator_projects]['project_id'])
	      {
		?>
		<option selected value=<?=$projects[$operator_projects]['project_id']?>><?=$projects[$operator_projects]['project_short_name']?></option>
		<?php
	      }
	      else
	      {
		?>
		<option value=<?=$projects[$operator_projects]['project_id']?>><?=$projects[$operator_projects]['project_short_name']?></option>
		<?php
	      }

	    }
	  }
	  ?>  
	</select>
      </div>
      <div id="div_dxcluster">
	<a>Auto Reload (30sec)</a>
	<input onchange='dxcluster_autoreload();' id="dxcluster_autoreload" type="checkbox" value="">
	<a>B&auml;nder</a>
        <select onchange='save_dxcluster_settings();' name='setting_table_dxcluster_bands' id='setting_table_dxcluster_bands'>
        </select>
	<a>QRG(kHz)</a>
	<input name='dxcluster_send_qrg' id='dxcluster_send_qrg'>
	<a>Bemerkung</a>
	<input name='dxcluster_send_comment' id='dxcluster_send_comment'>
	<a>Spotter</a>
	<input name='dxcluster_send_spotter' id='dxcluster_send_spotter'>
	<a>Rufzeichen</a>
	<input name='dxcluster_send_call' id='dxcluster_send_call'>
	<input type='button' onclick='send_dxcluster_spot();' value='Send'>
	<table id="table_dxcluster" class="compact" width="100%">
	  <thead>
	    <tr>
	      <th>Spotter</th>
              <th>QRG</th>
              <th>DX Call</th>
              <th>Comment</th>
              <th>Zeit</th>
              <th>Band</th>
              <th>QTH</th>
	    </tr>
	  </thead>
	</table>
      </div>
      <div id="div_complete">
      </div>
      <div id="div_navi_logout">
	<form method="POST" action="/login.php?aktion=kaputtmachen" class="form" id="form_navi_logout">
	  <input type="submit" name="submit" value="logout"> 
	</form>
      </div>
      <div id="div_log_import">
	  <input id="fileupload" type="file" name="files[]" data-url="/upload.php" multiple>
	  <input class='class_log_import' type='button' onclick=document.getElementById('div_log_import').style.visibility='hidden';document.getElementById('div_error').style.visibility='hidden'; value='Abbruch' name='Abbruch'>
	</form>
      </div>
      <div id="div_log_export">
	<form action="export.php" method="GET">
	  <table>
	    <tr>
	      <td><span class="help">Inkrementell<div>Inkrementell ja/nein</div></span></td>
	      <td><span class="help">Typ<div>Komplettes Projekt oder nur meine</div></span></td>
	    </tr>
	    <tr>
	      <td><input class='class_log_export' type='checkbox' id='log_export_incrementell' name='log_export_incrementell'></td>
	      <td>
		<select class='class_log_export' type='select' id='log_export_typ' name='log_export_typ'>
		  <option value="complete">komplett</option>
		  <option value="operator">nur meine</option>
		</input>
	      </td>
	    </tr>
	    </table>
	  <input class='class_log_export' type='button' onclick=document.getElementById('div_log_export').style.visibility='hidden';document.getElementById('div_error').style.visibility='hidden'; value='Abbruch' name='Abbruch'>
	  <input class='class_log_export' type='submit' onclick=document.getElementById('div_log_export').style.visibility='hidden';document.getElementById('div_error').style.visibility='hidden'; value='Los' name='Los'>
	</form>
      </div>
      <div id="div_log_change">
	<div id="div_log_change_logsfromme">
	  <a>Meine Logs</a>
	  <table id="table_logsfromme" class="compact" width="100%">
	    <thead>
	      <tr>
                <th>Datum</th>
	        <th>Zeit</th>
		<th>Call</th>
		<th>QRG</th>
		<th>Mode</th>
		<th>TX</th>
		<th>RX</th>
		<th>Name</th>
		<th>QTH</th>
		<th>Locator</th>
		<th>DOK</th>
		<th>Bemerkungen</th>
	      </tr>
	    </thead>
	  </table>
	</div>
	<div id="div_log_change_logsfromall">
	  <a>alle Logs des aktuellen Projektes (<b>KEIN</b> automatischer Reload)</a>
	  <table id="table_logsfromall" class="compact" width="100%">
	    <thead>
	      <tr>
                <th>Datum</th>
                <th>Zeit</th>
                <th>Call</th>
                <th>QRG</th>
                <th>Mode</th>
                <th>TX</th>
                <th>RX</th>
                <th>Name</th>
                <th>QTH</th>
                <th>Locator</th>
                <th>DOK</th>
                <th>Bemerkungen</th>
                <th>QSO</th>
	      </tr>
	    </thead>
	  </table>
	</div>

	<div id="div_log_change_callinfo1">
	  <div id="div_log_change_callinfo1_picture">
	  </div>
	</div>
	<div id="div_log_change_callinfo2">
	  <table id="table_display_callinfo2"></table>
	</div>
	<div id="div_log_change_error">
	</div>
	<div id="div_log_change_callinfo3">
	  <table>
	    <tr>
	      <td>
		<input tabindex="13" class="class_log_change" type="button" onclick="completed='0';write_data('log')"; value="Speichern&Neu" name="Speichern&Neu">
	      </td>
	    </tr>
	    <tr>
	      <td>
		<input class="class_log_change" type="button" onclick="completed='1';write_data('log')"; value="Speichern" name="Speichern">
	      </td>
	    </tr>
	    <tr>
	      <td>
		<input class='class_log_change' type='button' onclick=document.getElementById('div_log_change').style.visibility='hidden';shortcut.remove("Ctrl+S");clearInterval(interval_log_change); value='Abbruch' name='Abbruch'>
	      </td>
	    </tr>
	  </table>
	</form>
	<a>Datensatz speichern mit STRG+S</a>
	</div>
	<div id="div_log_change_callinfo4">
	</div>
	<div id="div_log_change_form">
	  <form method="POST" action="" class="class_form_log_change" id="form_log_change" accept-charset="UTF-8">
	  <table>
	    <tr class='class_log_change_desc_text'>
	      <td>
		<span class='help'>Datum<div>DD-MM-YYYY</div></span>
	      </td>
	      <td>
		<span class='help'>Zeit<div>HHMM</div></span>
	      </td>
	      <td>
		<span class='help'>Rufzeichen<div>Callsign</div></span>
	      </td>
	      <td>
		<span class='help'>Frequenz<div>QRG (kHz)</div></span>
	      </td>
	      <td>
		<span class='help'>Mode<div>Mode</div></span>
	      </td>
	      <td>
		<span class='help'>RST TX<div>RST TX</div></span>
	      </td>
	      <td>
		<span class='help'>RST RX<div>RST RX</div></span>
	      </td>
	      <td>
		<span class='help'>Name<div>Name</div></span>
	      </td>
	      <td>
		<span class='help'>LOC<div>Locator, z.B. JO53DM</div></span>
	      </td>
	      <td>
		<span class='help'>QTH<div>QTH</div></span>
	      </td>
	      <td>
		<span class='help'>Karte<div></div></span>
	      </td>
	      <td>
		<span class='help'>Zeit<div>Zeit wird automatisch beim abspeichern &uuml;bernommen</div></span>
	      </td>
	    </tr>
	    <tr class='class_log_change_inputs'>
	      <td>
		<input class='class_log_change' type='text' name='log_time_hr_date' id='log_time_hr_date' value=''>
	      </td>
	      <td>
		<input class='class_log_change' type='text' name='log_time_hr_time' id='log_time_hr_time' value=''>
	      </td>
	      <td>
		<input tabindex="1" class='class_log_change' type='text' name='log_call' id='log_call' onChange="display_callinfo(this.value,'1')" value=''>
	      </td>
	      <td>
		<input class='class_log_change' type='text' name='log_freq' id='log_freq' value=''>
	      </td>
	      <td>
		<select onchange='log_change_mod();' name='mode_id' id='mode_id'>
		</select">
	      </td>
	      <td>
		<input tabindex="2" class='class_log_change' type='text' maxlength="1" size="1" name='log_rst_tx_0' id='log_rst_tx_0' value=''>
		<input tabindex="3" class='class_log_change' type='text' maxlength="1" size="1" name='log_rst_tx_1' id='log_rst_tx_1' value=''>
		<input tabindex="4" class='class_log_change' type='text' maxlength="1" size="1" name='log_rst_tx_2' id='log_rst_tx_2' value=''>
	      </td>
	      <td>
		<input tabindex="5" class='class_log_change' type='text' maxlength='1' size='1' name='log_rst_rx_0' id='log_rst_rx_0' value=''>
		<input tabindex="6" class='class_log_change' type='text' maxlength='1' size='1' name='log_rst_rx_1' id='log_rst_rx_1' value=''>
		<input tabindex="7" class='class_log_change' type='text' maxlength='1' size='1' name='log_rst_rx_2' id='log_rst_rx_2' value=''>
	      </td>
	      <td>
		<input tabindex="8" class='class_log_change' type='text' name='log_name' id='log_name' value=''>
	      </td>
	      <td>
		<input onchange='log_change_loc();' class='class_log_change' type='text' name='log_loc' id='log_loc' value=''>
	      </td>
	      <td>
		<input tabindex="9" class='class_log_change' type='text' name='log_qth' id='log_qth' value=''>
	      </td>
	      <td>
		<input onclick='load_map2();' class='class_log_change' type="button" name="Karte" value="Karte">
	      </td>
	      <td>
                <input onchange='log_change_time();' class='class_log_change' type='checkbox' id='log_time_auto' name='log_time_auto'>
	      </td>
	    </tr>
	    <tr class='class_log_change_desc_text'>
	      <td colspan="8">
		<span class='help'>Bemerkungen<div>Bemerkungen</div></span>
	      </td>
	      <td>
		<span class='help'>DOK<div>DOK</div></span>
	      </td>
	      <td>
		<span class='help'>Manager<div>Manager</div></span>
	      </td>
	    </tr>
	    <tr class='class_log_change_inputs'>
	      <td colspan="8">
		<input tabindex="12" class='class_log_change' type='text' name='log_notes' id='log_notes' value=''>
	    </td>
	    <td>
	      <input tabindex="10" class='class_log_change' type='text' name='log_dok' id='log_dok' value=''>
	      </td>
	      <td>
		<input tabindex="11" class='class_log_change' type='text' name='log_manager' id='log_manager' value=''>
	      </td>
	    </tr>
	  </table>
	</div>
	<input class='class_log_change' type='hidden' name='log_id' id='log_id' value=''>
      </div>
	<div id="div_map2">
	  <div id="div_map2_map">
	  </div>
	  <div id="div_map2_settings">
	    <input class='class_log_change' type='button' onclick='save_map2_pos();' value='neue Position &uuml;bernehmen' name='neue Position &uuml;bernehmen'>
	    <input class='class_log_change' type='button' onclick=document.getElementById('div_map2').style.visibility='hidden'; value='Abbruch' name='Abbruch'>
	  </div>
	</div>
	<div id="div_map">
	  <div id="div_map_map">
	  </div>
	  <div id="div_map_settings">
	    <select name='map_settings_modes' id='map_settings_modes' onchange='save_map_settings();'>
	    </select>
	    <select name='map_settings_bands' id='map_settings_bands' onchange='save_map_settings();'>
	    </select>
	    <select name='map_settings_operators' id='map_settings_operators' onchange='save_map_settings();'>
	    </select>
	    <input onchange='save_map_settings();' type="checkbox" name="map_settings_filter" id="map_settings_filter" value="Filter">Filter aus (auch */CALL/*)</input>
	  </div>
	</div>
	<div id="div_monitor">
	  <div id="div_monitor_table_logs">
	    <a>Logs</a>
	    <table id="table_monitor_logs" class="compact">
	      <thead>
		<tr>
		  <th>Call</th>
		  <th>QRG</th>
	  	  <th>Mode</th>
		  <th>QTH</th>
	  	  <th>QSO</th>
		</tr>
	      </thead>
	    </table>
	  </div>
	  <div id="div_monitor_table_total">
	    <a>Summe</a>
	    <table id="table_monitor_total" class="compact">
	      <thead>
		<tr>
		  <th></th>
		</tr>
	      </thead>
	    </table>
	  </div>
	  <div id="div_monitor_table_modes">
	    <a>Modes</a>
	    <table id="table_monitor_modes" class="compact">
	      <thead>
		<tr>
		  <th>Betriebsart</th>
		  <th>%</th>
		</tr>
	      </thead>
	    </table>
	  </div>
	  <div id="div_monitor_table_bands">
	    <a>Band</a>
	    <table id="table_monitor_bands" class="compact">
	      <thead>
		<tr>
		  <th>B&auml;nder</th>
		  <th>%</th>
		</tr>
	      </thead>
	    </table>
	  </div>
	  <div id="div_monitor_table_qsos">
	    <a>QSO's</a>
	    <table id="table_monitor_qsos" class="compact">
	      <thead>
		<tr>
		  <th>QSO</th>
		  <th>%</th>
		  <th>Anzahl</th>
		</tr>
	      </thead>
	    </table>
	  </div>
	</div>
      <div id="div_operator_change">
	<form method="POST" action="" class="form" id="form_operator_change">
	  <table class='class_operator_change'>
	    <tr class='class_operator_change'>
	      <td><span class='help'>Rufzeichen<div>Rufzeichen</div></span></td>
	      <td><input class='class_operator_change' type='text' name='operator_call' id='operator_call' value=''></td>
	    </tr>
	    <tr class='class_operator_change'>
	      <td><span class='help'>Passwort<div>Passwort</div></span></td>
	      <td><input class='class_operator_change' type='password' name='operator_pass1' id='operator_pass1' value=''></td>
	    </tr>
	    <tr class='class_operator_change'>
	      <td><span class='help'>Passwort wiederholen<div>Passwort wiederholen</div></span></td>
	      <td><input class='class_operator_change' type='password' name='operator_pass2' id='operator_pass2' value=''></td>
	    </tr>
	    <tr class='class_operator_change'>
	      <td><span class='help'>e-mail<div>e-mail Adresse</div></span></td>
	      <td><input class='class_operator_change' type='text' name='operator_mail' id='operator_mail' value=''></td>
	    </tr>
            <tr>
              <td><span class='help'>Berechtigungen<div>Berechtigungen</div></span></td>
              <td>
                <select name='operator_role' id='operator_role'>
                  <option value='0'>Superuser</option>
                  <option value='1'>User</option>
                </select>
              </td>
            </tr>
	    <tr>
	      <td><span class='help'>PW per Mail?<div>neues PW setzen und per Mail an die Mailadresse schicken?</div></span></td>
	      <td><input type="checkbox" onchange="operator_change_pwm()" name="operator_pwm" id="operator_pwm" value=""></td>
	    </tr>
	    </table>
	    <input class='class_operator_change' type='hidden' name='operator_id' id='operator_id' value=''>
	    <input class="class_operator_change" type="button" onclick="write_data('operator')"; value="speichern" name="speichern">
	    <input class='class_operator_change' type='button' onclick=document.getElementById('div_operator_change').style.visibility='hidden';document.getElementById('div_error').style.visibility='hidden' value='abbruch' name='abbruch'>
	</form>
      </div>
    <div id="div_delete_data_ask">
      <form method="POST" action="" class="form" id="form_delete_data_ask">
	<input class="" type="button" onclick="delete_data()"; value="Ja" name="Ja">
	<input class="" type='button' onclick=document.getElementById('div_delete_data_ask').style.visibility='hidden'; value='Nein' name='Nein'>
      </form>
    </div>
    <div id="div_project_change">
      <form method="POST" action="" class="form" id="form_project_change">
        <table class='class_project_change'>
          <tr class='class_project_change'>
            <td>
	      <span class='help'>Bezeichnung<div>Bezeichnung</div></span>
	    </td>
	    <td>
	      <input type='text' name='project_short_name' id='project_short_name' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>Modus<div>Modus</div></span>
	    </td>
	    <td>
	      <select class='class_projekt_change' name='project_mode' id='project_mode' onchange='project_change_modus()'>
	        <option value="0">jeder sein Call</option>
	        <option value="1">Clubstation</option>
	      </select>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>Clubstationsrufzeichen<div>Clubstationsrufzeichen</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_call' id='project_call' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>qrz.com Username<div>Username qrz.com</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_qrz_user' id='project_qrz_user' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>qrz.com Passwort<div>Passwort qrz.com</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='password' name='project_qrz_pass1' id='project_qrz_pass1' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>qrz.com Passwort wiederholen<div>Passwort qrz.com Wiederholung</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='password' name='project_qrz_pass2' id='project_qrz_pass2' value=''>
	    </td>
	  </tr>

	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>Schnittstelle Clublog<div>Schnittstelle Clubblog</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='checkbox' name='project_clublog_ena' id='project_clublog_ena' value='' onchange='project_change_clublog()'>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>E-Mail Absenderadresse<div>E-Mail Absenderadresse</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_smtp_emailfrom' id='project_smtp_emailfrom' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>SMTP Server<div>SMTP Server</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_smtp_server' id='project_smtp_server' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>SMTP Server Port<div>SMTP Server Port</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_smtp_port' id='project_smtp_port' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>SMTP Server Username<div>SMTP Server Username</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_smtp_username' id='project_smtp_username' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>SMTP Passwort<div>Passwort SMTP</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='password' name='project_smtp_pass1' id='project_smtp_pass1' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>SMTP Passwort Wiederholung<div>Passwort SMTP Wiederholung</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='password' name='project_smtp_pass2' id='project_smtp_pass2' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>automatischer Upload<div>automatischer Upload</div></span>
	    </td>
	    <td>
	      <select class='class_projekt_change' name='project_clublog_auto' id='project_clublog_auto'>
	        <option value="0">nur manuell</option>
	        <option value="1">aller 10 Minuten</option>
	      </select>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>Locator<div>Locator</div></span>
	    </td>
	    <td>
	      <input class='class_project_change' type='text' name='project_locator' id='project_locator' value=''>
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>Mitglieder<div>Mitglieder des Projektes</div></span>
	    </td>
	    <td>
	      <select multiple class='class_projekt_change' name='project_members' id='project_members'>
	      </select">
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>Betriebsarten<div>Betriebsarten die verwendet werden.</div></span>
	    </td>
	    <td>
	      <select multiple class='class_projekt_change' name='project_modes' id='project_modes'>
	      </select">
	    </td>
	  </tr>
	  <tr class='class_project_change'>
	    <td>
	      <span class='help'>B&auml;nder die benutzt werden.<div>B&auml;nder</div></span>
	    </td>
	    <td>
	      <select multiple class='class_projekt_change' name='project_bands' id='project_bands'>
	      </select">
	    </td>
	  </tr>
	</table>
	<input class='class_project_change' type='hidden' name='project_id' id='project_id' value=''>
	<input class="class_project_change" type="button" onclick="write_data('project')"; value="Speichern" name="Speichern">
	<!--	<input class="class_project_change" type="button" onclick="write_data('project_kill_qrz_sess')"; value="QRZ Session loeschen" name="QRZ Session loeschen"> -->
	<input id="project_button_export_clublog"  class="class_project_change" type="button" onclick="export_clublog();" value="Export Clublog" name="Export Clublog">
	<input class='class_project_change' type='button' onclick=document.getElementById('div_project_change').style.visibility='hidden';document.getElementById('div_error').style.visibility='hidden' value='abbruch' name='abbruch'>
      </form>
    </div>
    <div id="div_error">
    </div>
    <div id="div_logs">
    <input onclick="change_log();" type="button" value="Log neu" name="">
    <input onclick="import_log();" type="button" value="Log importieren" name="">
    <input onclick="export_log();" type="button" value="Log exportieren" name="">
    <input onchange='logs_autoreload();' type="checkbox" name="logs_autoreload" id="logs_autoreload" value="logs_autoreload">Auto Reload/30s</>
    <input onchange='logs_onlyoperator();' type="checkbox" name="logs_onlyoperator" id="logs_onlyoperator" value="logs_onlyoperator">nur meine zeigen</>
    <table id="table_logs" class="compact" width="100%">
      <thead>
	<tr>
	  <th>Datum</th>
	  <th>Zeit</th>
	  <th>Call</th>
	  <th>QRG</th>
	  <th>Mode</th>
	  <th>TX</th>
	  <th>RX</th>
	  <th>Name</th>
	  <th>QTH</th>
	  <th>Locator</th>
	  <th>DOK</th>
	  <th>Manager</th>
	  <th>QSO</th>
	  <th>Bemerkungen</th>
	  <th></th>
	  <th></th>
	  <th></th>
	</tr>
      </thead>
    </table>
    </div>
    <div id="div_operators">
    <table id="table_operators" class="compact">
      <thead>
	<tr>
	  <th>Call</th>
	  <th></th>
	  <th></th>
	  <th></th>
	</tr>
      </thead>
    </table>
    <input onclick="change_operator();" type="button" value="neuen Operator anlegen" name="">
    </div>
    <div id="div_settings">
      <form id="form_settings" class="form" action="" method="POST">
	<table>
	  <tr>
	    <td><span class="help">Frequenzanzeige in<div>Frequenzanzeige in kHz oder MHz</div></span></td>
	    <td>
	      <select id="setting_frequency_prefix" name="setting_frequency_prefix" >
		<option value="0">MHz</option>
		<option value="1">kHz</option>
	      </select>
	    </td>
	  </tr>
	  <tr>
	    <td><span class="help">Schnittstelle aprs.fi<div>Schnittstelle zu aprs.fi aktiv oder inaktiv</div></span></td>
	    <td><input id="setting_interface_aprs_ena" type="checkbox" value=""></td>
	  </tr>
	  <tr>
	    <td><span class="help">Schnittstelle qrz.com<div>Schnittstelle zu qrz.com aktiv oder inaktiv</div></span></td>
	    <td><input id="setting_interface_qrz_ena" type="checkbox" value=""></td>
	  </tr>
	  <tr>
	    <td><span class="help">Schnittstelle Google-Maps<div>Schnittstelle zu Google-Maps aktiv oder inaktiv</div></span></td>
	    <td><input id="setting_interface_gm_ena" type="checkbox" value=""></td>
	  </tr>
	</table>
	<br>
	<p>sichtbare Felder f&uuml;r Logeintr&auml;ge</p>
	<br>
	<table border="1">
	  <tr>
	    <td>Datum</td>
	    <td>Zeit</td>
	    <td>Call</td>
	    <td>QRG</td>
	    <td>Mode</td>
	    <td>TX</td>
	    <td>RX</td>
	    <td>Name</td>
	    <td>QTH</td>
	    <td>Locator</td>
	    <td>DOK</td>
	    <td>Manager</td>
	    <td>QSO</td>
	    <td>Bemerkungen</td>
	  </tr>
	  <tr align="center">
	    <td><input id="setting_table_logs_date_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_time_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_call_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_freq_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_mode_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_rst_tx_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_rst_rx_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_name_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_qth_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_loc_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_dok_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_manager_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_qso_ena" type="checkbox" value=""></td>
	    <td><input id="setting_table_logs_notes_ena" type="checkbox" value=""></td>
	  </tr>
	</table>
	<input type="button" value="Speichern" onclick="settings_op_save();"> 
      </form>
    </div>
   <div id="div_projects">
    <table id="table_projects" class="compact">
      <thead>
        <tr>
          <th>Name</th>
	  <th></th>
	  <th></th>
	  <th></th>
          <th></th>
	  <th></th>
        </tr>
          </thead>
    </table>
    <input onclick="change_project();" type="button" value="neues Projekt anlegen" name="">
    </div>
    </body>
  </html>
