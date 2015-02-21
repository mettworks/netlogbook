<?php
  include('functions.php');
  //$mysql = mysql_connect("localhost","netlogbook","blafaselpeng","netlogbook") or die("Error " . mysql_error($mysql));   
  //mysql_select_db('netlogbook',$mysql); 
  $mysql=mysql_c();

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

	//$sql="SELECT project_id FROM rel_operators_projects WHERE project_operator='0' AND operator_id='".$data['operator_id']."';";
	$sql="SELECT projects.project_id FROM projects INNER JOIN rel_operators_projects ON rel_operators_projects.project_id=projects.project_id WHERE projects.project_operator='0' AND operator_id='".$data['operator_id']."';";
	$result = mysql_query($sql);

	  $operator_projects=mysql_fetch_assoc($result);
	  $_SESSION['map_settings']=array();
	  $_SESSION['operator_projects']=$operator_projects;
	  $_SESSION['operator_role']=$data['operator_role'];
	  $_SESSION['operator_id']=$data['operator_id'];
	  $_SESSION['loggedin']=true;

	  $sql="SELECT projects.project_id FROM projects INNER JOIN rel_operators_projects ON rel_operators_projects.project_id=projects.project_id WHERE projects.project_operator='1' AND operator_id='".$_SESSION['operator_id']."';";
	  $private_project=mysql_fragen($sql);

	  $_SESSION['private_project_id']=$private_project[0]['project_id'];	  

	  if(is_numeric($data['last_project']))
	  {
	    $_SESSION['project_id']=$data['last_project'];
	  }
	  else
	  {
	    $_SESSION['project_id']=$_SESSION['private_project_id'];
	  }
	  save_session_locator();
	  header('Location: /index.php');
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>NetLogBook</title>
  </head>
  <body>
<div id="div_login_logo">
  <img src="/images/netlogbook_logo.jpg" style="display: block; margin-left: auto; margin-right: auto" >
</div>
<div id="div_login_form">
  <form name="login" method="POST">
    <input style="display: block; margin-left: auto; margin-right: auto" name="uname" value="<?php echo $uname?>">
    <input style="display: block; margin-left: auto; margin-right: auto" type="password" name="pass" value="">
    <input style="display: block; margin-left: auto; margin-right: auto" type="submit" value="Login">
  </form>
</div>
</body>
</html>
