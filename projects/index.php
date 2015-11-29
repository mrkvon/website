<?php
require_once($_SERVER['DOCUMENT_ROOT']."/functions/page.php");
//$pagename='projects';
$content=<<<_END
<div>
<h2>active projects</h2>
<ul>
    <li><a href="http://ditup.org" target="_blank">ditup</a></li>
    <li><a href="http://nomadwiki.org" target="_blank">nomadic</a> lifestyle</li>
</ul>

<h2>paused projects</h2>
<ul>
    <li><a href="http://livegraph.org" target="_blank">livegraph</a></li>
    <li><a href="http://imgur.com/a/q8dpT/embed#22" target="_blank">comix diary (in Czech).</a> Warning for you, visitor: Might be offensive. Contains strong language, nudity and personal stuff.</li>
</ul>

<h2>incubated projects</h2>
<ul>
    <li><a target="_blank" href="/blog">blog</a> (music, collaboration, developing nomadic life)</li>
</ul>

<h2>past</h2>
<ul>
<li><a target="_blan" href="http://travel-makers.org">travel makers</a> participant</li>
<li>Created some graphics for <a href="http://www.franticware.com/multiracer" target="_blank">MultiRacer</a>. The author of the game is my brother.</li>
</ul>
_END;
page('projects',$content);
