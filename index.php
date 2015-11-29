<?php

require_once($_SERVER['DOCUMENT_ROOT']."/functions/page.php");
$content=<<<_END
<div id="rss"><a href="/rss"><img src="img/rss-icon.png" alt="rss icon" width="32" height="32" /></a></div>
<h1>Hello, World!</h1>
<p>
<img src="img/face.jpg" alt="face" class="left" width="128" height="128" />
This is web portfolio of Michal Salajka, male human, nomad traveler, web developer, street musician (violin).</p>
<p>
<ul>
<!--li><a href="http://travel-makers.org" target="_blank">travel-makers project</a> participant</li-->
<li><a href="http://ditup.org" target="_blank">ditup (real-world collaboration)</a> (work in progress)</li>
<li><a href="http://livegraph.org" target="_blank">livegraph (connecting mind-maps)</a> (work in progress, paused)</li>
<!--li><a href="/tutoring">math tutoring</a> (in czech)</li-->
<!--li><a href="http://www.duolingo.com/vcxy" target="_blank">learning languages on Duolingo</a></li-->
<!--li><a href="http://www.ted.com/profiles/971343/translations" target="_blank">translating for TED</a> (en to cs)</li-->
<!--li><a href="http://www.titulky.com/index.php?FindUser=435191" target="_blank">translating movie subtitles</a>  (en to cs)</li-->
</ul>
</p>
<hr />
<p>
<h2>contact, internet presence</h2>
<ul>
<li>email: michal[dot]salajka[at]email[dot]cz</li>
<li><a target="_blank" href="http://bewelcome.org/members/mrkvon">bewelcome profile</a></li>
<li><a target="_blank" href="http://seen.is/profile/38413" >seen.is profile</a></li>
<li><a target="_blank" href="http://couchsurfing.org/people/mrkvon">couchsurfing profile</a></li>
<li><a target="_blank" href="http://mrkvon.org">this</a> <a target="_blank" href="http://mrkvon.com">web</a> <a target="_blank" href="http://michalsalajka.com">page</a>
</ul>
if you want to meet in reality, <a target="_blank" href="/openlife/spacetime.activity">check my approximate position</a> and write me a message (or offer me a place to stay for a couple of days or weeks).
</p>
_END;
page('main',$content);
/*
<div id="menu">
<ul>
<li><span><a href="/" class="active">main</a></span></li>
<li><span><a href="/projects">projects</a></span></li>
<!--li><span><a href="/tutoring">tutoring</a></span></li-->
<li><span><a href="/openlife">open.life</a></span></li>
<li><span><a href="/ideas">ideas</a></span></li>
<li><span><a href="/links">links</a></span></li>
</ul>
</div>*/



