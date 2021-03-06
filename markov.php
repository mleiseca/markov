<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >

    License (MIT / X11 license)

    Permission is hereby granted, free of charge, to any person
    obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without
    restriction, including without limitation the rights to use,
    copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following
    conditions:

    The above copyright notice and this permission notice shall be
    included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
    OTHER DEALINGS IN THE SOFTWARE.
*/

$punctuation = array('<br>', '.', ',', ':', ';', '"', "!", "?");

function generate_markov_table($texts) {
  global $punctuation;
  $table_forward = array();
  $table_reverse = array();

  foreach($texts as $text) {
    $text = nl2br($text, false);
    $text = str_replace('”','"' , $text);
    $text = str_replace('“','"' , $text);
    $text = str_replace('"','' , $text);
    $text = str_replace('’','\'' , $text);
    $text = str_replace('Mr.','Mr' , $text);
    $text = str_replace('U.S.','US' , $text);
    $text = str_replace('…',' … ' , $text);
    #$text = str_replace('—',' — ' , $text);
    foreach($punctuation as $p) {
      $text = str_replace($p, " $p ", $text);
    }
    $brokenup_text = preg_split('/\s+/', $text);
    
    add_text_to_table($brokenup_text, $table_forward);
    add_text_to_table(array_reverse($brokenup_text), $table_reverse);
  }
  return array($table_forward, $table_reverse);
}

function add_text_to_table($text_array, &$table) {
  global $punctuation;
  $last_word = "START";
  $previous_words = array('START', '');
  foreach($text_array as $word) {
    if ($word == '') {
      continue;
    }
    $previous_group = trim(join(' ', $previous_words)); 
    if ($previous_group == "<br>" && $word == "<br>") {

    } elseif (isset($table[$previous_group][$word])) {
      #syslog(LOG_INFO, "Adding '$previous_group' -> '$word'");
      $table[$previous_group][$word]++;
    } else {
      #syslog(LOG_INFO, "Adding '$previous_group' -> '$word'");
      $table[$previous_group][$word] = 1;
    }
 
    if(in_array($word, $punctuation)) {
      $previous_words = array($word, '');
    } else {
      array_push($previous_words, $word);
      array_shift($previous_words);
    }
    $last_word = $word;
  }
  $table[$last_word]["END"] = 1;
}

function append(&$text, $additional, $reverse) {
  if ($reverse) {
    $text = $additional . $text;
  } else {
    $text = $text . $additional;
  }
}

function generate_markov_text($length, $table, $reverse = false) {
  global $punctuation;
  $word = "START";
  $o = '';

  $previous_words = array('START', '');
  $wrap_it_up = false;
  for ($i = 0; $i < $length * 10; $i++) {
    $previous_group = trim(join(' ', $previous_words));
    $new_word = return_weighted_char($table[$previous_group]);
    #syslog(LOG_INFO, $previous_group . "--> " .print_r($new_word,true) . " --> " . print_r($table[$previous_group], true));
    #$new_word = return_weighted_char($table[$word]);

    if ($new_word) {
      if ($new_word == "END") {
        return $o;
      }
      if (!in_array($word, $punctuation) && $reverse) {
        append($o, ' ', $reverse);
      }
      $word = $new_word;
      if (!in_array($new_word, $punctuation) && !$reverse) {
        append($o, ' ', $reverse);
      }
      if ($new_word == "<br>") {
        if ($wrap_it_up) {
          return $o;
        }
        append($o, '<p>', $reverse);
      } else {
        append($o, $new_word, $reverse);
      }
      if ($i > $length) {
        $wrap_it_up = true;
      }
    } else {
      $word = array_rand($table);
    }
    if(in_array($word, $punctuation)) {
      $previous_words = array($word, '');
    } else {
      array_push($previous_words, $word);
      array_shift($previous_words);
    } 
  }
  return $o;
}


function return_weighted_char($array) {
    if (!$array) return false;

    $total = array_sum($array);
    $rand  = mt_rand(1, $total);
    foreach ($array as $item => $weight) {
        if ($rand <= $weight) return $item;
        $rand -= $weight;
    }
}
?>
