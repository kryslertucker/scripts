<?php
	// number of different combinations 9505 5000 1517 6141 0000 90 
	// //95055000 04 106167 0000 90 

	$url = "http://production.shippingapis.com/shippingAPI.dll";
	$service = "TrackV2";
	$track_base = '9505500004';
	$track_last = '000090';
	$track_request_init = '<TrackRequest USERID="354LEARN2622">';
	$track_request_end = '</TrackRequest>';
	$count_track= 0;
	$xml = '';
	for ($i=6167; $i < 1000000 ; $i++) { 
		if ($i<10) $i = '00000'.$i;
		if ($i>=10 && $i<100) $i = '0000'.$i;
		if ($i>=100 && $i<1000) $i = '000'.$i;
		if ($i>=1000 && $i<10000) $i = '00'.$i;
		if ($i>=10000 && $i<100000) $i = '0'.$i;

		$track_id = $track_base.$i.$track_last;

		if ($count_track==10) {
			$xml .= $track_request_end;
			$xml = rawurlencode($xml);
			
			$request = $url . "?API=" . $service . "&XML=" . $xml;
			$resp = file_get_contents($request);
			$xml_resp = simplexml_load_string($resp);
				foreach ($xml_resp->children() as $child) {
					if (strrpos($child->TrackSummary,'The Postal Service could not locate')===false){
						echo $child['ID'] . ": " . $child->TrackSummary . "\n";
						// if (strrpos($xml_resp->TrackInfo->TrackSummary,'FL')!==false){
						// 	printf("ID-YES: %s - %s \n",$track_id,$xml_resp->TrackInfo->TrackSummary);
						// }
					}else{
						//printf("ID-NO : %s \n",$child['ID']);
					}
				
				}
		
			
			
			$count_track = 0;
			$xml = '';
		}else{
			if ($count_track==0) $xml .= $track_request_init;
			$xml .='<TrackID ID="'.$track_id.'"></TrackID>';
			$count_track++;
		}
	}
?>