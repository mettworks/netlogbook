<?php
  include('functions.php');
  $mysql = mysql_connect("localhost","root","pAnibu27!","dxpad") or die("Error " . mysql_error($mysql));   
  mysql_select_db('dxpad',$mysql); 
  if($_GET['aktion'] == "kaputtmachen")
  {
    session_start();
    session_destroy();
    header('Location: /index.php');
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    session_start();
    $uname=mysql_real_escape_string($_POST['uname']);
    $pass=md5(mysql_real_escape_string($_POST['pass']));

    $result = mysql_query("SELECT operator_pass from operators WHERE operator_call='".$uname."';") or die("Error " . mysql_error($mysql));

    if(mysql_num_rows($result) == 1)
    {
      $sql="SELECT operator_role,operator_id from operators WHERE operator_call='".$uname."' AND operator_pass='".$pass."';";
      $result = mysql_query($sql) or die("Error " . mysql_error($mysql));
      if(mysql_num_rows($result) == 1)
      {
        $data=mysql_fetch_assoc($result);

	// Wenn operator_role == 0 ist ueberall Zugriff erlaubt
	if($data['operator_role'] != 0)
	{
	  $sql="SELECT id FROM rel_operators_projects WHERE project_id='".$_POST['project']."' AND operator_id='".$data['operator_id']."';";
	  $result = mysql_query($sql);
	  if(mysql_num_rows($result) != 1)
	  {
	    session_destroy();
	    header('Location: /index.php');
	    die();
	  }
	}

	$_SESSION['operator_role']=$data['operator_role'];
	$_SESSION['operator_id']=$data['operator_id'];
	$_SESSION['project_id']=$_POST['project'];
	$_SESSION['loggedin']=true;
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
  else
  {
    $projects=mysql_fragen("SELECT project_id,project_short_name FROM projects;","project_id");
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
  <select name='project' id='project'>
    <?
    foreach($projects as $project)
    {
    ?>
      <option value=<?=$project['project_id']?>><?=$project['project_short_name']?></option>
    <?
    }
    ?>
  </select>
  <input type="submit" value="LOS">
</form>
</body>
</html>
