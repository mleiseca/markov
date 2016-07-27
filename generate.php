<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

function process($dirs) {
  $order = 8;
  $length = 400;
  $texts = array();
  
  foreach($dirs as $dir) {
    $dh = opendir("./$dir");
    while($file = readdir($dh)) {
      array_push($texts, file_get_contents("./$dir/" . $file));
    }
    closedir($dh);
  }
  if (empty($texts)) {
    throw new Exception("No text given");
  }
	
  return generate_markov_table($texts);
}

try {
  header('Content-Type: application/json');

  $model_names = array("d" => array("democratic_transcripts"),
                       "r" => array("republican_transcripts"),
                       "b" => array("democratic_transcripts", "republican_transcripts"));
  $selected_model = $_GET['model'];
  if (array_key_exists($selected_model, $model_names)) {
    $model = json_encode(process($model_names[$selected_model]));
    echo $model;
  }
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

