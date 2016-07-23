<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

function process() {
  $order  = $_GET['order'] ?: 8;
  $length = $_GET['length'] ?: 600;
  $texts = array();
  $dh = opendir('./republican_transcripts');
  while($file = readdir($dh)) {
    array_push($texts, file_get_contents('./republican_transcripts/' . $file));
  }
  closedir($dh);

  if (empty($texts)) {
    throw new Exception("No text given");
  }
	
  $markov_table = generate_markov_table($texts);
  return generate_markov_text($length, $markov_table);
}

try {
    $markov = process();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Republican Convention 2016 Speech Generator</title>
    <link rel="stylesheet" href="stylesheets/style.css" />
</head>
<body>
<div id="wrapper">
    <h1>Republican Convention 2016 Speech Generator </h1>
    <?php if ($error): ?>
        <p class="error"><strong><?= $error; ?></strong></p>
    <?php endif; ?>

       <div id="speech"> 
    <?php 
      echo($markov);
     ?>
    </div>
</div> <!-- /wrapper -->
</body>
</html>
