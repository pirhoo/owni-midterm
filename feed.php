<?php
$data = new stdClass();

$spreadsheet = "https://spreadsheets.google.com/feeds/list/0Aj910EQuus3bdEFTZTl6aWY0ZV9wbF9qTF8wd2drZWc/od6/public/basic";
$csv = "sum.csv";

$structure = array ("Democrat", "Republican", "Total", "sDemocrat", "sRepublican", "sIndependant");
$races = array("H" => "Représentants","S"=>"Sénateurs","G"=>"Gouverneur");

$googlefeed = simplexml_load_file($spreadsheet);

//parse feed
$videos = array();
for ($i=0; $i<count ($googlefeed->entry); $i++) {
    $entry = $googlefeed->entry[$i];
    $videos[trim(str_replace("state: ", "", $entry->content))][] = str_replace("http://www.youtube.com/watch?v=", "", (string)$entry->title);
}

$data->status = "200 OK";
$data->error = "false";
$data->message = "";
$data->response = array();

//$content = trim(str_replace("\r","",@file_get_contents($csv)));
//$rows = explode("\n",$content);
$handle = fopen($csv, "r");
$rows = fgetcsv($handle);

$states = array();
//for ($i=0;$i<count($rows);$i++) {
while ($row = fgetcsv($handle)) {
    //$row = explode (";", $rows[$i]);
    $state = new stdClass();
    if (!empty($row[0])) {
        $state->code = trim ($row[1], "\" ");
        $state->name = trim ($row[0], "\" ");
	$state->race = array();
	$race = trim(strtoupper($row[2]));
	for ($n=0;$n<strlen($race);$n++)
		if (!empty($races[substr($race, $n, 1)])) $state->race[] = $races[substr($race, $n, 1)];
	
        for ($n=3; $n<8; $n++)
            $state->{$structure[$n-3]} = (float)str_replace(",","", trim ($row[$n], "\$\" "));

        $state->winner =  trim ($row[9], "\" ");
        $state->videos = ($videos[$state->code]?$videos[$state->code]:array());
        $states[$state->name] = $state;
    }
}
ksort ($states);
$data->response = array_values($states);
echo json_encode($data);
?>
