PHP Markov chain text generator for the Republican and Democratic Conventions 2016
===============================
This is a Markov chain text generator that uses a two word context as the state for generating text. It started as a fork from Hay Kranen's project (see links below) and then I made a few tweaks:
* My interests was on words instead of letters, so I changed the focus of parsing to words. This also required some text cleanup for things like punctuation. There was also a fun bit trying to make a good closing sentence by building a backward model.
* The transcripts or planned remarks for the convention are in the republican_transcripts and democratic_transcripts directories.
* Prettied up the site. On one side that was html/css. On the other side, there was tweaking text generation so that it would make something a little more web-friendly. 

More info
---------
* Check out the site at http://conventionator2016.appspot.com/
* Original project by Hay Kranen: http://www.haykranen.nl/projects/markov, source at http://github.com/hay/markov
* deploy with app engine so: appcfg.py -A conventionator2016 -V v1 update ./ 

