<?php
namespace Melody\RedirectMobileBundle\Detector;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class Detector
{
	private $mdetect;
	private $router;
	private $request;
	private $response;

	private $mobRoute;
	private $comRoute;

	// Injection des services Mdetect, Router, Request
	// Et des paramètres remplis par l'utilisateur
	public function __construct($mdetect, $router, $request, $mobRoute, $comRoute){
		$this->mdetect = $mdetect;
		$this->router = $router;
		$this->request = $request;

		$this->mobRoute = $mobRoute;
		$this->comRoute = $comRoute;
	}

	// Méthode appelée à l'execution de toutes les requêtes du projet
	public function checkRoad(GetResponseEvent $response){
		// On stock notre objet GetResponseEvent qui nous servira 
		// pour modifier la réponse en redirection
		$this->response = $response;
		// On récupère la route appelée via le service Request
		$routeName = $this->request->get('_route');
		// On récupère l'objet matérialisant la route via le service Router
		$route = $this->router->getRouteCollection()->get($routeName);
		// Si la route est trouvée
		if($route){
			// On récupère les options saisie dans le fichier routing
			$options = $route->getOptions();
			// Et s'il ne s'agit pas d'un route pour mobile
			// On fait appel à la méthode pour tester s'il s'agit d'un mobile
			// Sinon on redige l'utilisateur sur la page pour ordinateur
			if(!isset($options['mobile']) || !$options['mobile'])
				return $this->isMobile();
			else
				return $this->isComputer();
		}
	}

	// Méthode pour tester si l'utilisateur utilise un mobile
	public function isMobile(){
		// On test via le service Mdetect s'il s'agit d'un smartphone ou d'un tablette
		if($this->mdetect->DetectMobileQuick() || $this->mdetect->DetectSmartphone() || $this->mdetect->DetectTierTablet()){
			// Si c'est le cas on regarde si le propriétaire à bien indiqué une
			// route ou une url de redirection pour les mobiles
			// Si oui, on procède à la redirection
			if($this->mobRoute != '')
				$this->getRedirectResponse($this->mobRoute);
		}
	}

	// Méthode pour tester si l'utilisateur utilise un ordinateur
	public function isComputer()
	{
		// On test via le service Mdetect s'il s'agit pas d'un smartphone ni d'une tablette
		if(!$this->mdetect->DetectMobileQuick() && !$this->mdetect->DetectSmartphone() && !$this->mdetect->DetectTierTablet()){
			// Si c'est le cas on regarde si le propriétaire à bien indiqué une
			// route ou une url de redirection pour les ordinateurs
			// Si oui, on procède à la redirection
			if($this->comRoute != '')
				$this->getRedirectResponse($this->comRoute);
		}	
	}

	// Gestion de la redirection
	public function getRedirectResponse($url){
		// Regex pour déterminer s'il s'agit d'une url ou bien d'une route
		if(preg_match('#^(http|https)://[\w-]+[\w.-]+\.[a-zA-Z]{2,6}#i', $url)){ 
			// Il s'agit d'une url
			// On génére et transmet la redirection
			$redirect = new RedirectResponse($url);
			$this->response->setResponse($redirect);
		} 
		else {
			// Si c'est pas une url l'utilisateur nous a donc transmit une route :)
			// On teste l'existence de la route pour la redirection
			$route = $this->router->getRouteCollection()->get($url);
			if($route){
				// Si elle existe on génère la redirection et on modifie change la valeur de Response
				$redirect = new RedirectResponse($this->router->generate($url));
				$this->response->setResponse($redirect);
			}
		}
	}
}