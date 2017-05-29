<?php

include_once "XMLAccount.php";
include_once "usr.php";
/*Clean class explores the catalog and folders to reveals errors and process them
*/
class Clean{
	
	const USER_DATABASE_NOT_AVAILABLE = 0;
	
	private $_missingParentFolder;
	private $_missingUserAccount;
	//TODO ouvrir le fichier accounts.xml , le parcourir et voir si les structures utilisateurs sont correctes(type "usr/vp")
	//logger toutes les erreurs rencontrées dans un fichier spécifique daté
	//s'il n'existe plus de dossier correspondant, supprimer le compte utilisateur
	//s'il existe des dossiers non répertoriés, ajouter ce compte utilisateur
	//si le répertoire utilisateur est vide, le supprimer et le supprimer de la base de données
	//Vérifier ensuite que pour chaque compte utilisateur, il existe bien un fichier de config et que ce fichier de config contient bien toutes les infos attendues
	//Si le fichier de config est corrompu, demander à l'administrateur de rensigner les informations manquantes
	//vérifier que les répertoires du fichier de config existent, sinon les créer
	//vérifier que le catalogue d'images existe, sinon demander à l'administrateur s'il faut en créer un nouveau ou s'il peut en fournir un
	//vérifier que les 3 répertoires d'images sont identiques. Si des images apparaissent dans thumbs ou large et pas dans fullsize, en avertir l'utilisateur 
	//et lui demander s'il veut conserver cette image au catalogue, auquel cas, la copier dans les répertoires où elle est absente
	//ouvrir le catalogue d'images et vérifier que les url des images sont accessibles dans chaque répertoire (fullsize, upload, large)
	//et que les images des dossiers sont toutes présentes dans le catalogue, sinon les ajouter
	
	public function __construct(){
		
		//get the list of acccounts
		$xml = XMLAccount::xmlAccountFactory();
		if($xml==null){
			return USER_DATABASE_NOT_AVAILABLE;
		}		
		$users = $xml->getUsers();
		
		$this->checkUserAccounts($users);	
		$this->checkConfigFiles($users);

	}
	
	//checks the presence of the user folder and checks if all user folders are in the database
	private function checkUserAccounts($users){
		$this->_missingParentFolder = array();
		$this->_missingUserAccount = array();
		
		$dirs = ManageFilesAndDirectories::listDir(XMLAccount::MAIN_USER_FOLDER):
		$userDirs = array();
		
		foreach($users as $user){
			$userDirs[] = $user.getDirectory();
		}
		
		foreach($userDirs as $userDir){
			$found=false;
			foreach($dirs as $key=>$dir){
				if (strcmp($dir, $userDir)==0){
					$found=true;
					unset($dirs[$key]);
					break;
				}
				if(!$found){
					$this->_missingParentFolder[]=$userDir;
				}
			}
		}
		
		foreach($dirs as $dir){
			$this->_missingUserAccount[] = $dir;
		}

		$this->processMissingParentFolder();
		$this->processMissingUserAccount();
	
	}
	
	private function checkConfigFiles($users){
		foreach($users as $user){
			$config = $user->config();
			$json = file_get_contents("../".$this->_folder."/".$config);
			if(!$json){
				$this->_missingConfigFile[] = $user; 
			}
			$config = json_decode($json, true);
			if($config==null){
				$this->_missingConfigFile[] = $user; 
			}
			$large = 
		}
}

?>