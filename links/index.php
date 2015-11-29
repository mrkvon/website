<?php
require_once($_SERVER['DOCUMENT_ROOT']."/functions/page.php");
//$pagename='projects';
$content=<<<_END
<p>
Links worth checking:
<ul>
<li><a href="http://franticware.com/" target="_blank">franticware</a> <br /> my brother's website</li>
<li><a href="http://libgen.org/" target="_blank">library genesis</a> <br /> greatest internet library i know</li>
</ul>
</p>
_END;
page('links',$content);
