<?php
require_once($_SERVER['DOCUMENT_ROOT']."/functions/page.php");
//$pagename='projects';
$content=<<<_END
<div>
This page hasn't been updated for quite a while.
Currently, I read and recommend to you:
<ul>
<li>Stephen Wolfram: A New Kind of Science (finished 15%)</li>
</ul>
Finished:
<ul>
<li>Nassim Nicholas Taleb: Antifragile: Things That Gain from Disorder</li>
</ul>
I also recommend for reading:
<ul>
<li>Howard Bloom: Global Brain: The Evolution of Mass Mind from the Big Bang to the 21st Century</li>
<li>James Surowiecki: The Wisdom of Crowds</li>
<li>Antoine de Saint-Exupéry: Citadelle</li>
<li>Robert B. Cialdini: Influence: Science and Practice</li>
<li>Andrew M. Lobaczewski: Political Ponerology (interesting book on psychopathy and politics)</li>
<li>Eckhart Tolle: The Power of Now: A Guide to Spiritual Enlightenment</li>
</ul>
The list is not complete. I find these books "eye opening" (showing the world through different/new eyes). Read carefully and with grain of skepticism.
</div>
_END;
page('ideas',$content);
