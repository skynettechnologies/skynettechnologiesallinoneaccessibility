<?php

declare(strict_types=1);

namespace OCA\AllInOneAccessibility\AppInfo;
use OC\Security\CSP\ContentSecurityPolicy;
use OC\Security\CSP\ContentSecurityPolicyNonceManager;
use OCP\Accounts\IAccount;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\IAppConfig;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Security\IContentSecurityPolicyManager;
use OCP\Server;
use OCP\Util;

class Application extends App implements IBootstrap {
	public const APP_ID = 'allinoneaccessibility';

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context): void {
        $appConfig = Server::get(IAppConfig::class);
        $contentSecurityPolicyManager = Server::get(IContentSecurityPolicyManager::class);
		$contentSecurityPolicyNonceManager = Server::get(ContentSecurityPolicyNonceManager::class);

		/*try {
			$accountDetails = Server::get(IUser::class);
			$userDetails = $accountDetails->getSystemEMailAddress();

		}catch (\Exception $e){
			echo $e->getMessage();
		}*/


		/*$url = 'https://www.skynettechnologies.com/accessibilitynode/js/all-in-one-accessibility-js-widget-minify.js?colorcode=#c90e00&token=null&position=middle_right';
		// Live
		//$url = 'https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js?colorcode=#c90e00&token=null&position=middle_right',
		Util::addHeader(
				'script',
				[
					'src' => 'https://www.skynettechnologies.com/accessibilitynode/js/all-in-one-accessibility-js-widget-minify.js?colorcode=#c90e00&token=null&position=middle_right',
					'nonce' => $contentSecurityPolicyNonceManager->getNonce()
				], ''
			);*/
		$protocol = (isset($_SERVER['HTTPS']))?(($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http"):'http';
		$websiteUrl = $protocol . "://" . $_SERVER['HTTP_HOST'];

		Util::addHeader(
            'script',
            [
                'src' => $websiteUrl.'/apps/allinoneaccessibility/src/js/aioawidget.js',
                'nonce' => $contentSecurityPolicyNonceManager->getNonce()
            ], ''
        );
			
        //$url = $appConfig->getValueString(self::APP_ID, 'url');
        $arrUrls =array('https://freeada.skynettechnologies.com','https://www.skynettechnologies.com','https://stagingada.skynettechnologies.us','https://staging.skynettechnologies.com','https://ada.skynettechnologies.us');
        foreach($arrUrls as $k => $url) {
            $policy = new ContentSecurityPolicy();
            //$policy->addAllowedStyleDomain($url);
			$policy->addAllowedScriptDomain($url);
			$policy->addAllowedImageDomain($url);
			$policy->addAllowedConnectDomain($url);
            $policy->allowInlineStyle();
            $policy->allowEvalWasm();
            $policy->allowInlineStyle();
            $policy->allowEvalScript();
			$contentSecurityPolicyManager->addDefaultPolicy($policy);
        }
        $arrUrlsF =array('*');
        foreach($arrUrlsF as $k => $urlF) {
            $policy = new ContentSecurityPolicy();
            $policy->addAllowedFontDomain($urlF);
			$contentSecurityPolicyManager->addDefaultPolicy($policy);
        }
        $arrUrlsS =array('https://fonts.googleapis.com');
        foreach($arrUrlsS as $k => $urlS) {
            $policy = new ContentSecurityPolicy();
            $policy->addAllowedStyleDomain($urlS);
			$contentSecurityPolicyManager->addDefaultPolicy($policy);
        }
        
	}
}
