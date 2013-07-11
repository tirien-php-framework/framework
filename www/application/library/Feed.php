<?php 
Class Feed{

	public function rssItems( $url, $count = null ){
		$return = array();
	    $content = file_get_contents($url);  
	    $xml = new SimpleXmlElement($content);  

	 	foreach($xml->channel->item as $item) {  
	        $return[] = (array) $item;
	    } 

	    return empty($count) ? $return : array_slice($return, 0, $count);
	}

}

?>