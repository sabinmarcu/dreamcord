<div style="text-align:center">
<?php foreach($this -> elem as $id => $video) : ?>
<a class="video" style="display:inline-block; width: 400px; height:300px; margin: 5px" id='player<?php echo $id ?>' href='http://<?php echo $_SERVER['HTTP_HOST']."/".ULDIR.Oski::app() -> instance."/".$this -> data['plugin_id']."/".$this -> data['instance_id']."/".$video ?>'></a>
<?php endforeach; ?></div>
<script type="text/javascript" charset="utf-8">
$("a.video").each(function()	{
	flowplayer("a.video", "http://<?php echo $_SERVER['HTTP_HOST'] ?>/oski_includes/resources/flash/flowplayer-3.2.6.swf", {
		clip: {
			autoPlay: false, 
			autoBuffering: true
		}
	});
})
</script>