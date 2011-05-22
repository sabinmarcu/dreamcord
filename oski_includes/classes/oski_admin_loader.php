<?php
	class oski_admin	{
		public function __construct($module, $prefix = "")	{
			if ($module)	: 
				$this -> getInstance($module, $prefix);
				$this -> getData();
		endif;
	}		
	
		public function getInstance($module, $prefix = "")	{
			$this -> module['main'] = $module;
			$this -> prefix = $prefix;
			if ($this -> prefix === "module")	$this -> getmoduleInstance();
		}
		
		private function getmoduleInstance()	{
			if (file_exists('oski_application/oski_instances/'.$this -> module['main'].".ini")) :
				$this -> instance = new ini_reader('oski_application/oski_instances/'.$this -> module['main'].".ini");
				$this -> instance = $this -> instance -> toDict();	
			else : server_error('404', "No Instance Config");
			endif;				
		}
		
		public function getData()	{	
			if ($this -> prefix === "module")		$this -> getmoduleData();
		}
		
		private function getmoduleData()	{
			if (isset($this -> instance))	{
				if (file_exists('oski_content/modules/'.$this -> instance["ESENTIALS"]['plugin_id'].'/config.ini'))	:		
				$this -> cfg = new ini_reader('oski_content/modules/'.$this -> instance["ESENTIALS"]['plugin_id'].'/config.ini');
				$this -> cfg = $this -> cfg -> toDict();			
				else : server_error('404', "No Plugin Config");
				endif;		
			}
		}
		
		public function getPanelSections()	{
			
			if ($this -> prefix === "module")	$data = $this -> getmoduleSections();
			Oski::app() -> theme -> getsidebox($data);
		}
		
		private function getmoduleSections() {
			$data = "";
			foreach($this -> cfg['ADMIN_SECTIONS'] as $id => $name)	
				$data .= "<li id='".$id."'><a href='/admin/moduleconfig/".$this -> module['main']."/".$id."'>".$name."</a>";
			return $data;
		}
		
		public function getPanel()	{	
			if ($this -> prefix === "module")	$this -> getmodulePanel();		
		}
		
		private function getmodulePanel()	{
				if (isset($this -> cfg['admin']) && file_exists('oski_content/modules/'.$this -> instance["ESENTIALS"]['plugin_id'].'/'.$this -> cfg['admin']))	
					include 'oski_content/modules/'.$this -> instance["ESENTIALS"]['plugin_id'].'/'.$this -> cfg['admin'];
				else server_error('404', "No Admin");			
		}
}
?>