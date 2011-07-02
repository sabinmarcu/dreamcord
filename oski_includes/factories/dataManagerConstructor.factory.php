<?php
class dataManagerConstructor extends Factory{
	public function getDataManager($manager = NULL)	{
		if (!$manager)	trigger_error("Manager name cannot be NULL");
		else if (!file_exists(OEDIR."/oski_content/dataManagers/".$manager.".datamanager.php"))	trigger_error("Manager is not installed");
		else {
			include OEDIR."/oski_content/dataManagers/".$manager.".datamanager.php";
			$manager .= "DataManager";
			return new $manager($manager);
		}
	}
}
?>