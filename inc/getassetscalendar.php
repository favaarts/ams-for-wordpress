<?php

$assetsResult = get_assetscalendar($prodictid[1]);

$getbulkassets = get_bulkassetscalendar($prodictid[1]);
		

if($assetsResult['assets'][0]['asset']['object']['is_bulk_asset'] == 1)
{
	sort($getbulkassets['json']['bulk_count_total']);

	foreach($getbulkassets['json']['bulk_count_total'] as $x_value) 
	{
		$currentmonth = date('m');
		$currentyear = date('Y');
		$todaydateset = $currentyear."-".$currentmonth."-".$x_value['date']." +1 days";
		

		if($x_value['bulk_count_left'] == 0)
		{
			$checkbulkcount = "Not Available";
		}
		else
		{
			$checkbulkcount = $x_value['bulk_count_left'] . " Left";
		}

		$data[] = array(
		  'title'   => $checkbulkcount,
		  'start'   => date('Y-m-d h:i:s', strtotime($todaydateset.' UTC')),
		);

	}
}
else
{
	foreach($assetsResult['assets'] as $x_value) 
	{
		$starttime = localtimezone('Y-m-d h:i:s',$x_value['details']['start_time']);
		$endtime = localtimezone('Y-m-d h:i:s',$x_value['details']['end_time']);

		$data[] = array(
		  'id'   => $row["id"],
		  'title'   => "Not Available",
		  'start'   => $starttime,
		  'end'   => $endtime
		);

	}

}

echo json_encode($data);

?>
