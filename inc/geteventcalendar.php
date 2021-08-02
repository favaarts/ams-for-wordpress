<?php 

$assetsResult = get_eventlisting(NULL);


foreach($assetsResult['programs'] as $x_value) 
{
	$eventtime = get_eventscheduletime($x_value['id']);

	$eventStatus = $x_value['status'];
    if($eventStatus = 1)
    {
        $eventStatusbg = "eventactive";
    }
    else if($eventStatus = 2)
    {
        $eventStatusbg = "eventcancelled";
    }
    else if($eventStatus = 3)
    {
        $eventStatusbg = "eventfinished";
    }

	foreach ($eventtime['json']['scheduled_program_dates'] as $daytime) 
	{ 
	   $starttime = date('Y-m-d h:i:s', strtotime($daytime['start']));

		 $data[] = array(
	      'title'   => $x_value['name'],
	      'start'   => $starttime,
	      'url' => site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id']),
	      'className' => $eventStatusbg
	    );
	}
 	
}

echo json_encode($data);

?> 
