<?php
	/**
		* Plugin 	myCannonical
		* @author	Cyrille G.  @ re7net.com
		* URL canonique
		* genere l'url canonique de votre page , d'acceuil, categorie, statique ou article  sous la forme <link rel="canonical" href="URL" />
		* conformémént à votre configuration urlrewriting, compatible avec le plugin MyBetterUrl
		* indique le numero de page : page1
		* identifie les plugins generant une page
	**/
	class myCannonical extends plxPlugin {
		

		public $lang = '';

        const BEGIN_CODE = '<?php' . PHP_EOL;
        const END_CODE = PHP_EOL . '?>';		
		
		
		/**
			* Constructeur de la classe
			*
			* @param	default_lang	langue par défaut
			* @return	stdio
			* @author	Stephane F
		**/
		public function __construct($default_lang) {
			
	
			
			
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			


			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN);
			
			
			# déclaration des hooks
			$this->addHook('ThemeEndHead', 'ThemeEndHead');

	
			
		}
		

		
		public function ThemeEndHead() {
		
	echo self::BEGIN_CODE;
?>


		$pagination='';
		$reqUri=   $plxShow->plxMotor->get;
		preg_match('/(\/?page[0-9]+)$/', $reqUri, $matches);
		if( $matches) $pagination =$reqUri;
		if($plxShow->catId(true) AND intval($plxShow->catId()) =='0') echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite().$pagination.'" />'.PHP_EOL  ;
		if($plxShow->plxMotor->mode=='categorie' AND $plxShow->catId(true) AND intval($plxShow->catId()) !='0') echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?categorie'. intval($plxShow->catId()).'/'.$plxShow->plxMotor->aCats[$plxShow->catId()]['url']).$pagination.'" />'.PHP_EOL  ;
		if($plxShow->plxMotor->mode=='article'  AND $plxShow->plxMotor->plxRecord_arts->f('numero')) echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?article' . intval($plxShow->plxMotor->plxRecord_arts->f('numero')) . '/' . $plxShow->plxMotor->plxRecord_arts->f('url')).'" />'.PHP_EOL  ;
		if( $plxShow->plxMotor->mode=='static'  ) { 
			echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?static'. intval($plxShow->staticId()).'/'.$plxShow->plxMotor->aStats[str_pad($plxShow->staticId(),3,0,STR_PAD_LEFT)]['url']).'" />'.PHP_EOL ;
		}
		else{
			# enfin on regarde si il s'agit d'un plugin qui squatte les pages statiques			
			foreach($plxShow->plxMotor->plxPlugins->aPlugins as $plug){				
				if($plug->getParam('url') == $plxShow->plxMotor->mode)  echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?'.$_SERVER['QUERY_STRING']).'"/>'.PHP_EOL;
			}
		}
			<?php

            echo self::END_CODE;
		
		}
		

	}
