<h1 id=""><?php echo ucwords($this -> stripName($this -> name)); ?></h1>
<div class="left">
<?php if (file_exists($this -> prevf) && !is_dir($this -> prevf)) : ?> <a href='<?php echo $this -> prev; ?>/'>&lArr; <?php echo ucwords($this -> stripName(str_replace(".".ext($this -> prevn), "", $this -> prevn))) ?></a> 
<?php else: ?>	<a href='<?php echo $this -> dir ?>/'>Aceasta este Prima Pagina</a>
<?php endif ?>
</div>
<div class="right">
<?php if (file_exists($this -> nextf) && !is_dir($this -> nextf)) : ?> <a href='<?php echo $this -> next; ?>/'><?php echo ucwords($this -> stripName(str_replace(".".ext($this -> nextn), "", $this -> nextn))) ?> &rArr;</a> 
<?php else: ?>	<a href='<?php echo $this -> dir ?>/'>Aceasta este Ultima Pagina</a>
<?php endif ?>
</div>
<img src='/<?php echo $this -> file ?>' alt = "File" class='full'/>
<h6><a href='<?php echo $this -> dir ?>/'>Inapoi</a></h6>