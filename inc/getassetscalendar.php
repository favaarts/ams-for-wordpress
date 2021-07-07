<?php

$assetsResult = get_assetscalendar($prodictid[1]);

foreach($assetsResult['assets'] as $x_value) 
{
	$data[] = array(
	  'id'   => $row["id"],
	  'title'   => "Not Available",
	  'start'   => date('Y-m-d h:i:s', strtotime($x_value['details']['start_time'])),
	  'end'   => date('Y-m-d h:i:s', strtotime($x_value['details']['end_time']." +1 days"))
	);
}
echo json_encode($data);

?>
