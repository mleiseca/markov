<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

try {
  $contents = file_get_contents($model);
  $models = json_decode($contents, true);
  $text = generate_markov_text(200, $models[0]);
  $last_sentence = generate_markov_text(10, $models[1], true);
  $markov = $text . "<p>" . $last_sentence;
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>$<?php echo $party ?> Convention 2016 Speech Generator</title>
    <link rel="stylesheet" href="stylesheets/style.css" />
</head>
<body>
<div id="wrapper">
    <h1><?php echo $party ?> Convention 2016 Speech Generator </h1>
    <?php if ($error): ?>
        <p class="error"><strong><?= $error; ?></strong></p>
    <?php endif; ?>
    <div id="header">
      <?php echo $header_text; ?>
    </div>
       <div id="speech"> 
    <?php 
      echo($markov);
     ?>
    <div class="bottom-tools">
      <div class="tool-item">
        <a id="generate-new-speech" href="" class="generate-button">Generate New Speech</a>
      </div>
    </div>
    </div>
    <div id="footer">
      <a href="/">Switch Parties</a> &nbsp; | &nbsp; 
      <a href="/about">About The Convention 2016 Speech Generator</a>
    </div>
</div> <!-- /wrapper -->
</body>
</html>
