<h1><?php echo ucwords($this -> stripName($this -> name)) ?></h1>
<ul>
<?php 	
foreach($this -> files as $file) :
$name = ucwords($this -> stripName($file));
if ($name)
echo "<li><a href='".$file."/'>".$name."</a></li>"; 
endforeach;
?>
</ul>
<?php if ($this -> name != actp('module')) : ?>
<h6><a href='<?php echo $this -> prevname ?>'>Inapoi</a></h6>
<?php endif; ?>