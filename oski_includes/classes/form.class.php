<?php
class formBuilder	{
	public $inputs = array();
	public $labels = array();
    public $submit = array();
	
	public function __construct($address = "", $method = "POST", $name = NULL, $file = NULL, $fieldset = "", $legend = "")	{
		$this -> resetData($address, $method, $name, $file, $fieldset, $legend);
	}
	public function resetData($address = "", $method = "POST", $name = NULL, $file = NULL, $fieldset = "", $legend = "")	{
        $this -> submit = array("value" => __("Continue")." &rarr;");
        $this -> inputs = array();
        $this -> labels = array();
		$this -> form['address'] = $address;	
		$this -> form['method'] = $method;
		$this -> form['name'] = $name;
		$this -> file = $file;
		$this -> fieldset = $fieldset;
		$this -> legend = $legend;
		$this -> submit['id'] = $this -> submit['class'] = "";
	}
	public function addInput($name, $placeholder = "", $id = "", $class = "", $type = "text", $input = "input", $args = "")	{
		$inputno = count($this -> inputs);
		$this -> inputs[$inputno]['name'] = $name;
		$this -> inputs[$inputno]['placeholder'] = $placeholder;
		$this -> inputs[$inputno]['id'] = $id;
		$this -> inputs[$inputno]['class'] = $class;
		$this -> inputs[$inputno]['type'] = $type;
		$this -> inputs[$inputno]['input'] = $input;
		$this -> inputs[$inputno]['args'] = $args;
	}
	public function addLabel($for, $text, $class = "")	{
		$inputno = count($this -> labels);
		$this -> labels[$inputno]['for'] = $for;
		$this -> labels[$inputno]['text'] = $text;		
		$this -> labels[$inputno]['class'] = $class;
	}
	public function addSubDetail($key, $value){
		if (in_array($key, array("class", "id", "value")))  $this -> submit[$key] = $value;
	}
	public function printForm()	{
		?><form action="<?php echo linkTo($this -> form['address']) ?>" method="<?php echo $this -> form['method'] ?>" name="<?php echo $this -> form['name'] ?>" id="<?php echo $this -> form['name'] ?>" accept-charset="utf-8" <?php if (isset($this -> file)) : ?>enctype="multipart/form-data"<?php endif; ?>>		
			<?php if ($this -> fieldset){ ?><fieldset><?php } if ($this -> legend ){ ?><legend><?php echo $this -> legend ?></legend><?php } ?>
			<?php foreach ($this -> inputs as $no => $data) :
			if (!in_array($data['type'], array("hidden"))) echo "<div class=\"inputwrapper\">";
				foreach($this -> labels as $nol => $datal)	
					if ($datal['for'] == $data['name'] || $datal['for'] == $data['id'])	
						echo "<label for='", $datal['for'], "' class='".$datal['class']."'>", $datal['text'], "</label>";
				if (!in_array($data['type'], array("hidden", "checkbox", "radio"))) echo "<div class='input'>";
				echo "<", $data['input'], " name=\"", $data['name'], "\" class=\"", $data['class'], "\" type=\"", $data['type'] ,"\" id=\"", ($data['id'] ? $data['id'] : $data['name']) ,"\" placeholder=\"", $data['placeholder'] ,"\" value = \"", (($data['args'] && !is_array($data['args'])) ? $data['args'] : $data['placeholder']), "\"";
				echo ">";
				if ($data['input'] == "select") :
					foreach($data['args'] as $name => $value) if ($name !== "_selected") { ?>
						<option name="<?php echo $name ?>" value="<?php echo $value ?>" <?php if (isset($data['args']['_selected']) && $value == $data['args']['_selected']) echo "selected" ?>><?php echo dewrap($value) ?></option>
					<?php } ?></select><?php
				elseif ($data['input'] == "textarea") : echo $data['args'];
                                endif;
				if ($data['input'] !== "input") : echo "</", $data['input'], ">"; endif;
				if ($data['type'] == "radio")	echo "<br>";
			if (!in_array($data['type'], array("hidden", "checkbox", "radio"))) echo "</div>";
			if ($data['type'] != "hidden") echo "</div>";
			endforeach;  if ($this -> fieldset) { ?></fieldset> <?php } ?>
				<input type="submit" value="<?php echo $this -> submit['value'] ?>" id="<?php echo $this -> submit['id'] ?>" class="<?php echo $this -> submit['class'] ?>">
		</form><?php
	}
}
?>