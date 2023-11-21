#!/usr/bin/env php
<?php
require("./Service/NoteTranscriptionService.php");
require("./Service/JsonService.php");

$noteTranscriptionService = new NoteTranscriptionService();
$jsonService = new JsonService();

echo "Please input your JSON array to transpose:";
$handle = fopen ("php://stdin","r");
$jsonStringLine = fgets($handle);
fclose($handle);

try {
    $jsonService->checkJson($jsonStringLine);

    $jsonArray = $jsonService->returnDecodedJson($jsonStringLine);

    echo "Please input your transpose value:";

    $handle = fopen ("php://stdin","r");
    $transposeValue = fgets($handle);
    fclose($handle);

    $transposedArray = $noteTranscriptionService->transposeTranscriptedNoteArray($jsonArray, intval($transposeValue));
    echo $jsonService->returnEncodedJson($transposedArray);
} catch (Exception $e) {
    echo $e;
}