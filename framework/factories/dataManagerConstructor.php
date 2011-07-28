<?php
class dataManagerConstructor extends Factory{
	public function getDataManager($manager = NULL)	{
		if (!$manager)	trigger_error("Manager name cannot be NULL");
		else if (!file_exists(BASEDIR."/framework_content/dataManagers/".$manager.".datamanager.php"))	trigger_error("Manager is not installed");
		else {
			include BASEDIR."/framework_content/dataManagers/".$manager.".datamanager.php";
			$manager .= "DataManager";
			return new $manager($manager);
		}
	}
}
?>