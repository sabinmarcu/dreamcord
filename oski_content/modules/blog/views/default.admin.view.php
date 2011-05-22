<?php getTPart("article","","admin");getTPart("content"); ?>
<table>
	<tr>
	<th>
	Title
	</th>
	<th>
	Excerpt
	</th>
	<th>
	Body
	</th>
	<th>
	Date added
	</th>
	<th>
	Edit
	</th>
	</tr>
	<?php if (count($this -> posts)) foreach ($this -> posts as $post ) { ?>
		<tr> 
		<td>
		<?php echo $post["title"]; ?>
		</td>
		<td>
		<?php echo $post["excerpt"]?>
		</td>
		<td>
		<?php echo $post["body"] ?>
		</td>
		<td>
		<?php echo $post["date_added_t"]?>
		</td>
		<td>
		<a href='<?php echo actp("complete")?>edit/<?php echo $post["permalink"]?>'>Edit</a>
		<a href='<?php echo actp("complete")?>remove/?permalink=<?php echo $post["permalink"]; ?>'>Remove</a>
		</td>
				</tr>
	<?php 
	}  
	?>    
	<tr colspan=5><a href ='<?php echo actp("complete")?>add/'>Add</a>
	</table>
<?php getTPart("content");getTPart("article","","admin"); ?>