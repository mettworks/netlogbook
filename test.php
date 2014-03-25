<?php
  $content=file_get_contents('bla.txt');
  $content=rawurldecode($content);

  $kenner=preg_split('/<td/',$content);

  //print_r($kenner);

  foreach($kenner as $kennung)
  {
    if(preg_match('/height="18"/',$kennung))
    {
      if(!preg_match('/bgcolor/',$kennung))
      {
	$temp=preg_replace('/\s{2,}/','',$kennung);
	//$temp=split("/\n/",$temp);
	//print_r($temp);
	$kenner=preg_split('/>/',$temp);
	$kenner=preg_split('/</',$kenner[1]);

	$kenner=$kenner[0];
	//print $kenner."\n";
	$ergebniss[$land][$$land]=$kenner;
	$$land++;
      }
    } 
    else
    {
      //$temp=preg_replace('/\s+/','',$kennung);
      $temp=preg_replace('/\s{2,}/','',$kennung);
      if(preg_match('/<\/tr><tr>/',$temp))
      {
	if(!preg_match('/&nbsp;/',$temp))
	{
	  $land=preg_split('/>/',$temp);
	  $land=preg_split('/</',$land[1]);

	  $land=$land[0];
	  if(!isset($$land))
	  {
	    $$land=0;
	  } 
	  //print $land."\n";
	}
      }
    }
  }
  print_r($ergebniss);
?>
