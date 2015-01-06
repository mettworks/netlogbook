<?php
  include('functions.php');
  $mysql = mysql_connect("localhost","netlogbook","blafaselpeng","netlogbook") or die("Error " . mysql_error($mysql));   
  mysql_select_db('netlogbook',$mysql); 
  if($_GET['aktion'] == "kaputtmachen")
  {
    session_start();
    session_destroy();
    header('Location: /index.php');
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    ini_set('session.gc_maxlifetime', 168*60*60); // 1 woche
    ini_set('session.cookie_lifetime', 168*60*60); // 1 woche
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    ini_set('session.cookie_secure', FALSE);
    ini_set('session.use_only_cookies', TRUE);
    session_start();
    $uname=mysql_real_escape_string($_POST['uname']);
    $pass=md5(mysql_real_escape_string($_POST['pass']));

    $result = mysql_query("SELECT operator_pass from operators WHERE operator_call='".$uname."';") or die("Error " . mysql_error($mysql));

    if(mysql_num_rows($result) == 1)
    {
      $sql="SELECT last_project,operator_role,operator_id from operators WHERE operator_call='".$uname."' AND operator_pass='".$pass."';";
      $result = mysql_query($sql) or die("Error " . mysql_error($mysql));
      if(mysql_num_rows($result) == 1)
      {
        $data=mysql_fetch_assoc($result);

	$sql="SELECT project_id FROM rel_operators_projects WHERE operator_id='".$data['operator_id']."';";
	$result = mysql_query($sql);

	if(mysql_num_rows($result) != 0)
	{
	  $operator_projects=mysql_fetch_assoc($result);
	  $_SESSION['map_settings']=array();
	  $_SESSION['operator_projects']=$operator_projects;
	  $_SESSION['operator_role']=$data['operator_role'];
	  $_SESSION['operator_id']=$data['operator_id'];
	  $_SESSION['loggedin']=true;

	  if(is_numeric($data['last_project']))
	  {
	    $_SESSION['project_id']=$data['last_project'];
	  }
	  else
	  {
	    $_SESSION['project_id']=end($operator_projects);
	  }

	  $sql="SELECT project_locator FROM projects WHERE project_id='".$_SESSION['project_id']."';";
	  $result = mysql_query($sql);
	  $data=mysql_fetch_assoc($result);
	  $_SESSION['project_locator']=$data['project_locator'];
	  $temp=locator2degree($_SESSION['project_locator']);
	  $_SESSION['project_lon']=$temp['lon'];
	  $_SESSION['project_lat']=$temp['lat'];

	  header('Location: /index.php');
	}
	else
	{
	  ?>
	  <script language="javascript">
	  alert("Benutzername und Passwort ok, du bist aber keinem Projekt zugeordnet und kein Admin.");
	  </script>
	  <?php
	}
      }
      else
      {
	session_destroy();
        header('Location: /index.php');
        die();
      }
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
  <head>
    <meta charset="utf-8">
    <title>Zeug</title>
  </head>
  <body>
<form name="login" method="POST">
  <input name="uname" value="<?php echo $uname?>"><br>
  <input type="password" name="pass" value=""><br>
  <input type="submit" value="LOS">
</form>
</body>
</html>
