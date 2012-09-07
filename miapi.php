<?php
/**
* API de Miapi
* @auteur : XAttax
* @version : 0.5
* @documentation : http://miapi.dzv.me/api/documentation
**/
	
	if(!function_exists('json_decode')) {
		throw new Exception('Miapi à besoin de l\'extension JSON.');
	}
	
	class Miapi {
		protected $uid;
		protected $secret;
		
		const MIAPI_GESTION_URL = 'http://miapi.dzv.me/api/gestion.php';
		
		/**
		* Envoie les données à l'hydrateur
		* @param array $donnees : Les données à envoyer
		**/
		public function __construct(array $donnees) {
			$this->hydrate($donnees);
			
			if(!$this->verifierIdentifiants()) {
				throw new Exception('Le couple uid/secret est incorrect.');
			}
		}
		
		/**
		* Actualise les attributs de l'objet.
		* @param array $donnees : Le tableau contenant les attribuits à modifier.
		*/
		public function hydrate(array $donnees) {
            foreach($donnees as $cle => $valeur) {
                $methode = 'set'.ucfirst($cle);
                
                if(method_exists($this, $methode)) {
                    $this->$methode($valeur);
                }
            }
        }
		
		public function setUid($uid) {
			$this->uid = (int) $uid;
		}
		
		public function setSecret($secret) {
			$this->secret = (string) $secret;
		}
		
		public function getUid() {
			return $this->uid;
		}
		
		public function getSecret() {
			return $this->secret;
		}
		
		/**
		* Permet d'envoyer une requête sur le site de Miapi
		* @param string $ parametres : les parametres à envoyer
		* @return string : Le contenu JSON ou un message d'erreur
		**/
		protected function requete($parametres) {
			$parametres = str_replace(' ', '%20', $parametres);
			$miapi = self::MIAPI_GESTION_URL.'?uid='.$this->uid.'&secret='.$this->secret;
			
			if($gestion = fopen($miapi.$parametres, 'rb')) {
				if($contenu = stream_get_contents($gestion)) {
					if($contenu == 'false')
						throw new Exception('Impossible de récupérer les informations du serveur');
					else
						return $contenu;
				}
				else
					throw new Exception('Impossible de récupérer les informations du serveur');
			}
			else
				throw new Exception('Impossible de récupérer les informations du serveur');
			
			fclose($gestion);
		}
		
		/**
		* Permet de vérifier si l'uid et la clé secrete est correct
		* @return bool : Renvoie true si c'est OK, sinon false
		**/
		protected function verifierIdentifiants() {
			$json = $this->requete('&verifier');
			$json_decode = json_decode($json, true);
			
			if(!isset($json_decode['valide']))
				return false;
				
			if($json_decode['valide'] == true)
				return true;
			else
				return false;
		}
		
		/**
		* Permet de récupérer les informations du membre
		* @return array : Un tableau contenant les informations du membre
		**/
		public function getMembre() {
			$json = $this->requete('&getMembre');
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet de récupérer les informations des images du membre
		* @param $param = array() : les parametres (ordre, nom, description, tags)
		* @return array : Un tableau contenant les informations des images
		**/
		public function getImages($param = array()) {
			$json = $this->requete('&getImages='.json_encode($param));
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet de récupérer les informations d'une image du membre
		* @param int $image_id : l'id de l'image
		* @return array : Un tableau contenant les informations d'une image
		**/
		public function getImage($image_id) {
			$json = $this->requete('&getImage='.$image_id);
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet de récupérer les informations des publications du membre
		* @param $param = array() : les parametres (ordre, texte, tags)
		* @return array : Un tableau contenant les informations des publications
		**/
		public function getPublications($param = array()) {
			$json = $this->requete('&getPublications='.json_encode($param));
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet de récupérer les informations d'une publication du membre
		* @param int $publication_id : l'id de la publication
		* @return array : Un tableau contenant les informations d'une publication
		**/
		public function getPublication($publication_id) {
			$json = $this->requete('&getPublication='.$publication_id);
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet de récupérer les informations des vidéos favorites du membre
		* @param $param = array() : les parametres (ordre, titre, description, tags)
		* @return array : Un tableau contenant les informations des vidéos
		**/
		public function getVideos($param = array()) {
			$json = $this->requete('&getVideos='.json_encode($param));
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet de récupérer les informations d'une vidéo favorite du membre
		* @param int $video_id : l'id de la vidéo
		* @return array : Un tableau contenant les informations d'une vidéo
		**/
		public function getVideo($video_id) {
			$json = $this->requete('&getVideo='.$video_id);
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet d'ajouter une image
		* @param array $donnees : Les données à envoyer (url, nom, description, tags, partage)
		* @return array : Un tableau contenant les informations de la nouvelle image
		**/
		public function ajouterImage(array $donnees) {
			$json = $this->requete('&ajouterImage='.json_encode($donnees));
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet d'ajouter une publication
		* @param array $donnees : Les données à envoyer (texte, visibilite, tags, partage)
		* @return array : Un tableau contenant les informations de la nouvelle publication
		**/
		public function ajouterPublication(array $donnees) {
			$json = $this->requete('&ajouterPublication='.json_encode($donnees));
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
		
		/**
		* Permet d'ajouter une publication
		* @param string $url : L'url de la vidéo YouTube
		* @return array : Un tableau contenant les informations de la nouvelle vidéo
		**/
		public function ajouterVideo($url) {
			$json = $this->requete('&ajouterVideo='.$url);
			$json_decode = json_decode($json, true);
			
			return $json_decode;
		}
	}
?>