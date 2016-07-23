PHP Markov chain text generator for the Republican Convention 2016
===============================
This is a Markov chain text generator that uses a two word context as the state for generating text. It started as a fork from Hay Kranen's project (see links below) and then I made a few tweaks:
* My interests was on words instead of letters, so I changed the focus of parsing to words. This also required for things like punctuation.
* The transcripts or planned remarks for the convention are in the republican_transcripts directory.
* Prettied up the site, including html/css and tweaking text generation so that it would make something a little more web-friendly. 

More info
---------
* Check out the site at http://republicanconventionator.appspot.com
* Original project by Hay Kranen: http://www.haykranen.nl/projects/markov, source at http://github.com/hay/markov
* deploy with app engine so: appcfg.py -A republicanconventionator -V v1 update ./

