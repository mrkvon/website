<?php

//function to create menu. $menu is data array, $active is name of active item
function menu($menu,$active){
  echo<<<_END
<div id="menu">
<nav>
<ul>
_END;

  foreach($menu as $name=>$item){
    echo '<li><span><a href="'.$item['link'].'" '.($name==$active?'class="active"':'').'>'.htmlentities($name).'</a></span></li>';
  }
  echo<<<_END
</ul>
</nav>
</div>
_END;
}
