<?php if (!isset($this -> t)) $this -> t = 0; ?>
<?php if (!isset($this -> h)) $this -> h = 0; ?>
<?php if (strpos($class, "tt") != NULL || strpos($class, "ot") != NULL && $this -> h == 0) { $this -> h = 0; $this -> t++; echo "<div class='slab'></div>"; }
else if (strpos($class, "half") != NULL) { $this -> t = 0; $this -> h++; echo "<div class='clear'></div>"; } ?>
<?php if (!$this -> $parts) :  ?><div class="article<?php echo $class ?>"><?php else : ?></div><?php endif; ?>