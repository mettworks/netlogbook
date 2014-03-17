<?php
  include('functions.php');
  checklogin();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
  <html>
    <head>
      <meta charset="utf-8">
      <title>Netlogbook v0.1</title>
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css">
    </head>
    <body>
      <p>
        <script src="js/jquery-2.1.0.js"></script>
        <script src="js/DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
        <script src="js/formulare.js"></script>
        <script src="js/getdata.js"></script> 
        <script src="js/valididation.js"></script>
        <script src="js/functions.js"></script>
        <script type="text/javascript" src="js/shortcut.js"></script>
        <script type="text/javascript" src="js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="js/jquery.iframe-transport.js"></script>
        <script type="text/javascript" src="js/jquery.fileupload.js"></script>
        <script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
        <script>
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
	    //maxTime:0,
	  });
	});
	$(document).ready(
	  function() 
	  {
	    table_logs=$('#table_logs').dataTable
	    (
	      {
		"bProcessing": true,
		"bServerSide": true,
		"bUseRendered": false,
		"sAjaxSource": "/getdata.php?typ=datatable&table=logs",
		//"bStateSave": true,
		//"bPaginate": false,

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
		  {
		    "bVisible": false
		  },
		  {
		    "fnRender": function(oObj)
		    {
		      return '<input onclick="change_log(\''+oObj.aData[14]+'\');" type="button" value="bearbeiten" name="bearbeiten" >';
		    }
		  },
		  {
		    "fnRender": function(oObj)
		    {
		      return '<input onclick="delete_data_ask(\'log\',\''+oObj.aData[14]+'\');" type="button" value="loeschen" name="loeschen" >';
		    }
		  }

		]

	    });
	    table_logsfromme=$('#table_logsfromme').dataTable
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
		    null,
		]

	    });
	    table_logsfromall=$('#table_logsfromall').dataTable
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


	    table_operators=$('#table_operators').dataTable
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
		    "fnRender": function(oObj)
		    {
		      return '<input onclick="change_operator(\''+oObj.aData[1]+'\');" type="button" value="bearbeiten" name="bearbeiten" >';
		    }
		  },
		  {
		    "fnRender": function(oObj)
		    {
		      return '<input onclick="delete_data_ask(\'operator\',\''+oObj.aData[1]+'\');" type="button" value="loeschen" name="loeschen" >';
		    }
		  }

		]
	    });
	    table_projects=$('#table_projects').dataTable
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
                    // bearbeiten
                    "fnRender": function(oObj)
                    {
                      return '<input onclick="change_project(\''+oObj.aData[1]+'\');" type="button" value="bearbeiten" name="bearbeiten" >';
                    }
                  },
                  {
                    // loeschen
                    "fnRender": function(oObj)
                    {
                      return '<input onclick="delete_project(\''+oObj.aData[1]+'\');" type="button" value="loeschen" name="loeschen" >';
                    }
                  }

                ]
            });
	  });
      //interval_log=setInterval("reload_tables_log()",5000);
      </script></p>
      <div id="div_navi_top">
	<?php
	if($_SESSION['operator_role']==0)
	{
	  ?>
	  <input type="button" onclick="document.getElementById('div_logs').style.visibility='visible'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='hidden';" value="Log">
	  <input type="button" onclick="document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='visible'; document.getElementById('div_operators').style.visibility='hidden';" value="Projekte">
	  <input type="button" onclick="document.getElementById('div_logs').style.visibility='hidden'; document.getElementById('div_projects').style.visibility='hidden'; document.getElementById('div_operators').style.visibility='visible';" value="OP's">
	<?php
	}
	?>
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
		  <option value="project">projekt</option>
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
	  <table id="table_logsfromme">
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
		<th>Manager</th>
	      </tr>
	    </thead>
	  </table>
	</div>
	<div id="div_log_change_logsfromall">
	  <a>alle Logs des aktuellen Projektes (automatischer Reload)</a>
	  <table id="table_logsfromall">
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
		<input tabindex="13" class="class_log_change" type="button" onclick="completed='0';write_data('log')"; value="Speichern & Weiter" name="Speichern & Weiter">
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
	  <form method="POST" action="" class="form" id="form_log_change">
	  <table>
	    <tr>
	      <td>
		<span class='help'>Datum<div>DD-MM-YYYY</div></span>
	      </td>
	      <td width="20">
		<span class='help'>Zeit<div>HHMM</div></span>
	      </td>
	      <td>
		<span class='help'>Rufzeichen<div>Callsign</div></span>
	      </td>
	      <td width="8">
		<span class='help'>Frequenz<div>QRG (kHz)</div></span>
	      </td>
	      <td width="6">
		<span class='help'>Modulation<div>Mode</div></span>
	      </td>
	      <td width="3">
		<span class='help'>RST TX<div>RST TX</div></span>
	      </td>
	      <td width="3">
		<span class='help'>RST RX<div>RST RX</div></span>
	      </td>
	      <td width="43">
		<span class='help'>Name<div>Name</div></span>
	      </td>
	      <td width="37">
		<span class='help'>QTH<div>QTH</div></span>
	      </td>
	      <td width="6">
		<span class='help'>Locator<div>Locator</div></span>
	      </td>
	      <td width="10">
		<span class='help'>Automatikzeit<div>Automatikzeit</div></span>
	      </td>
	    </tr>
	    <tr>
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
		<input tabindex="2" class='class_log_change' type='text' maxlength='1' size='1' name='log_rst_tx_0' id='log_rst_tx_0' value=''>
		<input tabindex="3" class='class_log_change' type='text' maxlength='1' size='1' name='log_rst_tx_1' id='log_rst_tx_1' value=''>
		<input tabindex="4" class='class_log_change' type='text' maxlength='1' size='1' name='log_rst_tx_2' id='log_rst_tx_2' value=''>
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
		<input tabindex="9" class='class_log_change' type='text' name='log_qth' id='log_qth' value=''>
	      </td>
	      <td>
		<input onchange='log_change_loc();' class='class_log_change' type='text' name='log_loc' id='log_loc' value=''>
	      </td>
	      <td>
                <input onchange='log_change_time();' class='class_log_change' type='checkbox' id='log_time_auto' name='log_time_auto'>
	      </td>
	    </tr>
	    <tr>
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
	    <tr>
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
	  <input class="class_project_change" type="button" onclick="write_data('project_kill_qrz_sess')"; value="QRZ Session loeschen" name="QRZ Session loeschen">
	  <input class='class_project_change' type='button' onclick=document.getElementById('div_project_change').style.visibility='hidden';document.getElementById('div_error').style.visibility='hidden' value='abbruch' name='abbruch'>
	</form>
      </div>
      <div id="div_error">
      </div>
    <div id="div_logs">
    <input onchange='logs_autoreload();' type="checkbox" name="logs_autoreload" id="logs_autoreload" value="logs_autoreload">Auto Reload/30s</>
    <input onchange='logs_onlyoperator();' type="checkbox" name="logs_onlyoperator" id="logs_onlyoperator" value="logs_onlyoperator">nur meine zeigen</>
    <table id="table_logs">
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
    <input onclick="change_log();" type="button" value="neues Log erfassen" name="">
    <input onclick="import_log();" type="button" value="Log importieren" name="">
    <input onclick="export_log();" type="button" value="Log exportieren" name="">
    </div>
    <div id="div_operators">
    <table id="table_operators">
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
   <div id="div_projects">
    <table id="table_projects">
      <thead>
        <tr>
          <th>Name</th>
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
