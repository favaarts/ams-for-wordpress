<?php

$assetsResult = get_assetscalendar($prodictid[1]);

if(!empty($assetsResult['assets']))
{
		function mergeRanges($array)
		{
		    usort($array,function($a,$b){ return $a['start_date']<=>$b['start_date']; });  // order by start_date ASC

		    foreach($array as $i=>$row){
		         if($i && $row['start_date']<=date('Y-m-d',strtotime("{$result[$x]['end_date']} +1 day"))){  // not the first iteration and dates are within current group's range
		            if($row['end_date']>$result[$x]['end_date']){  // only if current end_date is greater than existing end_date
		                $result[$x]['end_date']=$row['end_date'];  // overwrite end_date with new end_date in group
		            }
		            $result[$x]['merged_ids'][]=$row['id'];  // append id to merged_ids subarray
		        }else{  // first iteration or out of range; start new group
		            if($i){  // if not first iteration
		                $result[$x]['merged_ids']=implode(', ',$result[$x]['merged_ids']);  
		            }else{  // first iteration
		                $x=-1;  
		            }
		            $result[++$x]=['merged_ids'=>[$row['id']],'start_date'=>$row['start_date'],'end_date'=>$row['end_date']]; 
		        }
		    }
		    $result[$x]['merged_ids']=implode(', ',$result[$x]['merged_ids']);  // convert final merged_ids subarray to csv string
		    return $result;
		}



		foreach ($assetsResult['assets'] as $value) {
		    $dataformat[] = array(
		      'start_date'   => date('Y-m-d h:i:s', strtotime($value['details']['start_time'])),
		      'end_date'   => date('Y-m-d h:i:s', strtotime($value['details']['end_time']." +1 days")),
		    );
		}



		$newmearg = mergeRanges($dataformat);
}	
		

if($assetsResult['assets'][0]['asset']['object']['is_bulk_asset'] == 1)
{
	foreach($newmearg as $x_value) 
	{

			$data[] = array(
			  'id'   => $x_value["id"],
			  'title'   => "No Items Available",
			  'start'   => date('Y-m-d h:i:s', strtotime($x_value['start_date'])),
			  'end'   => date('Y-m-d h:i:s', strtotime($x_value['end_date']." +1 days")),
			);

	}
}
else
{
	foreach($assetsResult['assets'] as $x_value) 
	{
		
		$data[] = array(
		  'id'   => $row["id"],
		  'title'   => "Not Available",
		  'start'   => date('Y-m-d h:i:s', strtotime($x_value['details']['start_time'])),
		  'end'   => date('Y-m-d h:i:s', strtotime($x_value['details']['end_time']." +1 days"))
		);

	}

}

echo json_encode($data);

?>
