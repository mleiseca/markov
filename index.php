<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

function process_post() {
    $order  = $_POST['order'];
    $length = $_POST['length'];

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

if (isset($_POST['submit'])) {
    try {
        $markov = process_post();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>PHP Markov chain text generator by Hay Kranen</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrapper">
    <h1>Republican Convention 2016 Speech Generator </h1>
    <?php if ($error): ?>
        <p class="error"><strong><?= $error; ?></strong></p>
    <?php endif; ?>

    <?php if ($markov): ?>
        <h2>Output text</h2>
	    <?= $markov; ?>
    <?php endif; ?>

    <h2>Input text</h2>
    <form method="post" action="" name="markov">
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
