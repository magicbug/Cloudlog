<?php

// Create the DOMDocument for the XML output
$xmlDoc = new DOMDocument("1.0");

if($data['format'] == "xml") {
    // Add reference to the XSLT
    $xsl = $xmlDoc->createProcessingInstruction("xml-stylesheet", "type=\"text/xsl\" href=\"".base_url()."css/api.xsl\"");
    $xmlDoc->appendChild($xsl);
}

// Get the method called, and build the root node
$call = $data['queryInfo']['call'];
$rootNode = $xmlDoc->createElement("Cloudlog-API");
$parentNode = $xmlDoc->appendChild($rootNode);

// Get the results output
$output = $data[$call."_Result"];

// Add the queryInfo node
$node = $xmlDoc->createElement("queryInfo");
$queryElement = $parentNode->appendChild($node);
$queryElement->setAttribute("timeStamp", date("r", time()));
$queryElement->setAttribute("calledMethod", $data['queryInfo']['call']);
//$queryElement->setAttribute("queryArgs", $queryArgsString);
$queryElement->setAttribute("resultsCount", count($data['queryInfo']['numResults']));
if(ENVIRONMENT == "development") {
	$debugInfo = $xmlDoc->createElement("debugInfo");
	$debugElement = $queryElement->appendChild($debugInfo);
	$debugElement->setAttribute("dbQuery", $data['queryInfo']['dbQuery']);
	$debugElement->setAttribute("clientVersion", $_SERVER['HTTP_USER_AGENT']);
	$debugElement->setAttribute("requestURI", $_SERVER['REQUEST_URI']);
#	$debugElement->setAttribute("benchMark", $this->benchmark->marker['total_execution_time_start'].", ".$this->benchmark->marker['loading_time:_base_classes_start'].", ".$this->benchmark->marker['loading_time:_base_classes_end'].", ".$this->benchmark->marker['controller_execution_time_( api / add )_start']);
}
$queryElement->setAttribute("executionTime", $data['queryInfo']['executionTime']);
$queryElement->setAttribute("logbookURL", $this->config->item('base_url'));

// Add the main results node
$node = $xmlDoc->createElement("results");
$elementsNode = $parentNode->appendChild($node);

// Cycle through the results and add to the results node
if($output['results'])
{
	foreach($output['results'] as $e) {
		$node = $xmlDoc->createElement("result");
		$element = $elementsNode->appendChild($node);

		foreach($e as $attr) {
		#while($attr = current($e)) {
		    if(is_array($attr))
			{
			  foreach($attr as $subattr)
			  {
				$node = $xmlDoc->createElement(key($e));
				foreach($subattr as $subsubattr)
				{
				  $node->setAttribute(key($subattr), $subsubattr);
				  next($subattr);
				}
				$element->appendChild($node);
			  }
			}
			else
			{
			  $element->setAttribute(key($e), $attr);
			}
			next($e);
		}
	}
}

if(isset($data['error']))
{
  $node = $xmlDoc->createElement("error");
  $errorNode = $parentNode->appendChild($node);

  $errorNode->setAttribute("id", $data['error']);
}

// Output

// Check whether we want XML or JSON output
if(($data['format'] == "xml") || ($data['format'] == "xmlp") || ($data['format'] == "xmlt")) {
  if(($data['format'] == "xml") || ($data['format'] == "xmlp")) {
    // Set the content-type for browsers
    header("Content-type: text/xml");
  }
  echo formatXmlString($xmlDoc->saveXML());
} else if($data['format'] == "json") {
  // Set the content-type for browsers
  header("Content-type: application/json");
  // For now, our JSON output is simply the XML re-parsed with SimpleXML and
  // then re-encoded with json_encode
  $x = simplexml_load_string($xmlDoc->saveXML());
  $j = json_encode($x);
  echo $j;
} else {
  echo "Error: Unknown format type '".$data['format']."'.";
}

// This function tidies up the outputted XML
function formatXmlString($xml) {

  // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
  $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);

  // now indent the tags
  $token      = strtok($xml, "\n");
  $result     = ''; // holds formatted version as it is built
  $pad        = 0; // initial indent
  $matches    = array(); // returns from preg_matches()

  // scan each line and adjust indent based on opening/closing tags
  while ($token !== false) :

    // test for the various tag states

    // 1. open and closing tags on same line - no change
    if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
      $indent=0;
    // 2. closing tag - outdent now
    elseif (preg_match('/^<\/\w/', $token, $matches)) :
      $pad--;
    // 3. opening tag - don't pad this one, only subsequent tags
    elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
      $indent=1;
    // 4. no indentation needed
    else :
      $indent = 0;
    endif;

    // pad the line with the required number of leading spaces
    $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
    $result .= $line . "\n"; // add to the cumulative result, with linefeed
    $token   = strtok("\n"); // get the next token
    $pad    += $indent; // update the pad size for subsequent lines
  endwhile;

  return $result;
}

?>
