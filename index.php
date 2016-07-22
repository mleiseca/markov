<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

function process() {
    $order  = $_GET['order'] ?: 8;
    $length = $_GET['length'] ?: 2000;

    $dh = opendir('./republican_transcripts');
    while($file = readdir($dh)) {
        $text .= file_get_contents('./republican_transcripts/' . $file);
    }
    closedir($dh);

    if (empty($text)) {
        throw new Exception("No text given");
    }
	
    #return htmlentities($text);
    $markov_table = generate_markov_table($text, $order);
    $markov = generate_markov_text($length, $markov_table, $order);
    return htmlentities($markov);
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
    <title>PHP Markov chain text generator by Hay Kranen</title>
    <link rel="stylesheet" href="stylesheets/style.css" />
</head>
<body>
<div id="wrapper">
    <h1>Republican Convention 2016 Speech Generator </h1>
    <?php if ($error): ?>
        <p class="error"><strong><?= $error; ?></strong></p>
    <?php endif; ?>

    <?php if ($markov): ?>
       <div id="speech"> 
         <?= $markov; ?>
       </div>
    <?php endif; ?>

    <h2>Tweak your speech</h2>
    <form method="get" action="" name="markov">
       <br />
        <label for="order">Order</label>
        <input type="text" name="order" value="10" />
        <label for="length">Text size of output</label>
        <input type="text" name="length" value="2500" />
        <br />
        <input type="submit" name="submit" value="GO" />
    </form>
</div> <!-- /wrapper -->
</body>
</html>
