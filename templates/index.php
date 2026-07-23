<?php
declare(strict_types=1);

use OC\Security\CSP\ContentSecurityPolicy;
use OC\Security\CSP\ContentSecurityPolicyNonceManager;
use OCP\IAppConfig;
use OCP\Security\IContentSecurityPolicyManager;
use OCP\Server;
use OCP\Util;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;


Util::addScript(OCA\AllInOneAccessibility\AppInfo\Application::APP_ID, 'main');

$appConfig = Server::get(IAppConfig::class);
$contentSecurityPolicyManager = Server::get(IContentSecurityPolicyManager::class);
$contentSecurityPolicyNonceManager = Server::get(ContentSecurityPolicyNonceManager::class);
$db = Server::get(IDBConnection::class);
$qb = $db->getQueryBuilder();

$qb->select('*')
	->from('accounts_data')
	->where($qb->expr()->isNotNull('value'))
	->andWhere($qb->expr()->eq('name',$qb->createNamedParameter('email', IQueryBuilder::PARAM_STR)))
	->andWhere($qb->expr()->eq('uid',$qb->createNamedParameter('admin', IQueryBuilder::PARAM_STR)));
$cursor = $qb->execute();
$row_email = $cursor->fetch();
$cursor->closeCursor();

$qb->select('*')
	->from('accounts_data')
	->where($qb->expr()->isNotNull('value'))
	->andWhere($qb->expr()->eq('name',$qb->createNamedParameter('displayname', IQueryBuilder::PARAM_STR)))
	->andWhere($qb->expr()->eq('uid',$qb->createNamedParameter('admin', IQueryBuilder::PARAM_STR)));
$cursor_name = $qb->execute();
$row_name = $cursor_name->fetch();
$cursor_name->closeCursor();

// Example Usage
//$domain = isset($_GET['domain'])?base64_decode($_GET['domain']):$domains[0]; //$_SERVER['HTTP_HOST']; // Change as needed
$domain = $_SERVER['HTTP_HOST']; // Change as needed
$username = (isset($row_name['value']) && !empty($row_name['value']))?$row_name['value']:'Next Cloud';
$email = (isset($row_email['value']) && !empty($row_email['value']))?$row_email['value']:'';

 $arrUrls =array('https://freeada.skynettechnologies.com','https://www.skynettechnologies.com','https://ada.skynettechnologies.us','https://ajax.googleapis.com','https://nextcloud.skynettechnologies.us');
        foreach($arrUrls as $k => $url) {
            $policy = new ContentSecurityPolicy();
            //$policy->addAllowedStyleDomain($url);
			$policy->addAllowedScriptDomain($url);
			//$policy->addAllowedImageDomain($url);
			$policy->addAllowedConnectDomain($url);
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
//$domains = get_user_domains();
//Add User Detail ADA dashboard
function fetchApiData($domain, $username, $email) {
    // Encode the domain in base64
    $website_name = base64_encode($domain);
    
    $packageType = "free-widget";
    $arrDetails = [
        'name' => $username,
        'email' => $email,
        'company_name' => $username,
        'website' => $website_name,
        'package_type' => $packageType,
        'start_date' => date('c'), // ISO 8601 format
        'end_date' => '',
        'price' => '',
        'discount_price' => '0',
        'platform' => 'NextCloud',
        'api_key' => '',
        'is_trial_period' => '',
        'is_free_widget' => '1',
        'bill_address' => '',
        'country' => '',
        'state' => '',
        'city' => '',
        'post_code' => '',
        'transaction_id' => '',
        'subscr_id' => '',
        'payment_source' => ''
    ];
    
    $apiUrl = "https://ada.skynettechnologies.us/api/get-autologin-link-new";
    
    // Prepare the POST request
    $response = sendPostRequest($apiUrl, ['website' => $website_name]);
    
    if ($response && isset($response['link'])) {
    
    } else {
        
        
        $secondApiUrl = "https://ada.skynettechnologies.us/api/add-user-domain";
        $secondResponse = sendPostRequest($secondApiUrl, $arrDetails);
        
        if ($secondResponse && isset($secondResponse['success']) && $secondResponse['success']) {
        
        } else {
        
        }
    }
}
function sendPostRequest($url, $data) {
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true // Capture API errors in response
        ]
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return null;
    }
    
    return json_decode($result, true);
}



fetchApiData($domain, $username, $email);
?>
    <link href="/apps/allinoneaccessibility/src/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/apps/allinoneaccessibility/src/css/style.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
        body{
            font-family: "Roboto", sans-serif;
        }
        h1{
            font-family: "Rubik", sans-serif;
        }
        .aioa-cancel-button {
            text-decoration: none;
            display: inline-block;
            vertical-align: middle;
            border: 2px solid #420083;
            border-radius: 4px;
            background-color: #420083;
            box-shadow: 0px 0px 2px 0px #333333;
            color: #ffffff;
            text-align: center;
            box-sizing: border-box;
            padding: 10px;
        }
        .aioa-cancel-button:hover {
            border-color: #420083;
            background-color: white;
            box-shadow: 0px 0px 2px 0px #333333;
            color:black;
        }
        .aioa-cancel-button:hover .mb-text {
            color: #420083;
        }
		.aioa-settings-panel {
			margin-left:25%;
		}

        .aioa-settings-panel .icon input[type=radio] +label{
            width: 130px;
            height: 130px;
            padding: 10px !important;
            text-align: center;
            background-color: #f7f9ff;
            outline: 4px solid #f7f9ff;
            outline-offset: -4px;
            border-radius: 10px;
            background: #420083;
        }
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .header-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .header-content img {
            max-width: 510px; /* Adjust image size */
            height: auto;
        }
        /* Rest of your styles */
        .all-in-one-accessibility-wrap .accessibility-settings .all-one-accessibility-form .icon-size-wrapper .option, .all-in-one-accessibility-wrap .accessibility-settings .all-one-accessibility-form .icon-type-wrapper .option {
            width: 130px;
            height: 130px;
            padding: 10px !important;
            text-align: center;
            background-color: #420083;
            outline: 4px solid #fff;
            outline-offset: -4px;
            border-radius: 10px;
        }
        .all-in-one-accessibility-wrap .accessibility-settings .all-one-accessibility-form {
            margin: 0 auto 40px;
            border-radius: 19px;
            background: #e9efff;
            padding: 48px 35px 13px 35px;
        }
    </style>
    <div class="panel panel-default aioa-settings-panel">
        <div class="panel-body">
            <input type="hidden" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" />
            <input type="hidden" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" />
            <input type="hidden" id="domain" name="domain" value="<?php echo $domain ?>" />
            <div class="shopify-wrap-block">
                <div class="container">
                    <div class="set-width">
                        <div class="all-in-one-accessibility-wrap">
                            <div class="accessibility-settings">
                                <div class="all-one-accessibility-form">
                                    <div class="all-one-accessibility-form-inner">
                                        <form id="widget-form" name="widget-form" class="form-controler">

                                            <div class="mb-3 row">
                                                <div class="col-sm-12">
                                                    <div class="header-content">
                                                        <h1 class="mb-0 text-black">
                                                            <img src="/apps/allinoneaccessibility/src/img/all-in-one-accessibility-logo.svg" alt="All in One Accessibility - Skynet Technologies">
                                                        </h1>
                                                    </div>
                                                    <div class="form-text">
                                                        <B>NOTE: Currently, All in One Accessibility is dedicated to enhancing accessibility
                                                            specifically for websites and online stores.</B>
                                                    </div>
                                                    <B>  <p class="form-text" id="upgrade_html_notes">Please <a
                                                                    href="https://ada.skynettechnologies.us/trial-subscription" target="_blank">Upgrade</a>
                                                            to full
                                                            version of All in One Accessibility Pro with 10 days free trial</p></B>
                                                </div>
                                            </div>
                                            <div class="mb-3 row ">

                                            </div>
                                    </div>
                                    <div class="mb-3 row d-none" id="license_key_html">
                                        <label for="inputPassword" class="col-sm-3 col-form-label">License Key required for full
                                            version:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="license_key" class="form-control" id="license_key" value=""
                                                   onkeyup="mylicensekey()" />

                                            <p class="form-text " id="error_message">Please enter valid License Key</p>
                                        </div><br>
                                    </div>
                                    <div class="mb-3 row " id="colorcode_html">
                                        <h3 class="col-sm-3 col-form-label">Hex color code:</h3>
                                        <div class="col-sm-9">
                                            <input type="text" name="colorcode"  style="height:auto" class="form-control" id="colorcode" value="" />
                                            <div class="form-text">You can customize the ADA Widget color. For example: FF5733</div>
                                        </div>
                                    </div>
                                    <div class="icon-custom-position-wrapper mb-3 row">
                                        <div class="col-sm-12 mb-4">
                                            <label class="custom-switcher ">
                          <span class="custom-switcher_right">
                            <input type="checkbox" id="custom-position-switcher" class="custom-switcher_inp_2"
                                   value="1" />
                            <span class="custom-switcher_body" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                  title="Toggle to override <Top Left> position" data-bs-custom-class="switcher-tooltip">
                            </span>
                            <span class="custom-switcher_label">Enable precise accessibility widget icon position:</span>
                          </span>
                                            </label>
                                            <div class="custom-position-controls hide">
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <div class="input-group mb-3">
                                                            <input type="number"  style="height:auto;border-bottom-right-radius: 0px;
    border-top-right-radius: 0px;"  min="0" max="250" class="form-control" id="custom_position_x_value"
                                                                   aria-label="Value in pixels" aria-describedby="pos-value-input_1" oninput="this.value = Math.min(Math.max(this.value, 0), 250)" />
                                                            <span class="input-group-text"  style="height:auto"  id="pos-value-input_1">PX</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <select class="form-select"  style="height:auto" name="custom_position_x_direction" aria-label="Default select example">
                                                            <option selected value="cust-pos-to-the-right">To the right</option>
                                                            <option value="cust-pos-to-the-left">To the left</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <div class="input-group mb-3">
                                                            <input type="number"  style="height:auto;border-bottom-right-radius: 0px;
    border-top-right-radius: 0px;"  min="0" max="250" class="form-control" id="custom_position_y_value"
                                                                   aria-label="Value in pixels" aria-describedby="pos-value-input_2" oninput="this.value = Math.min(Math.max(this.value, 0), 250)"/>
                                                            <span class="input-group-text"  style="height:auto"  id="pos-value-input_2">PX</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <select class="form-select"  style="height:auto" name="custom_position_y_direction" aria-label="Default select example">
                                                            <option selected value="cust-pos-to-the-lower">To the bottom</option>
                                                            <option value="cust-pos-to-the-upper">To the top</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="description">0 - 250px are permitted values</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 row widget-position" id="position_html">
                                        <label class="fcol-sm-3 col-form-label">Where would you like to place the accessibility icon on your
                                            site?
                                        </label>
                                        <div class="col-sm-12 three-col">
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-top-left" name="position" value="top_left"
                                                       class="form-radio" />

                                                <label for="edit-position-top-left" class="option">Top left</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-top-center" name="position" value="top_center"
                                                       class="form-radio" />

                                                <label for="edit-position-top-center" class="option">Top Center</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-top-right" name="position" value="top_right"
                                                       class="form-radio" />

                                                <label for="edit-position-top-right" class="option">Top Right</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-middel-left" name="position" value="middel_left"
                                                       class="form-radio" />

                                                <label for="edit-position-middel-left" class="option">Middle left</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-middel-right" name="position" value="middel_right"
                                                       class="form-radio" />

                                                <label for="edit-position-middel-right" class="option">Middle Right</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-bottom-left" name="position" value="bottom_left"
                                                       class="form-radio" />

                                                <label for="edit-position-bottom-left" class="option">Bottom left</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-bottom-center" name="position" value="bottom_center"
                                                       class="form-radio" />

                                                <label for="edit-position-bottom-center" class="option">Bottom Center</label>
                                            </div>
                                            <div
                                                    class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                <input type="radio" id="edit-position-bottom-right" checked="" name="position"
                                                       value="bottom_right" class="form-radio" />

                                                <label for="edit-position-bottom-right" class="option">Bottom Right</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- widget icon size -->

                                    <h3>Select Widget Size:</h3>
                                    <div class="form-radios  mb-3">
                                        <div class="form-radio-item">
                                            <input data-drupal-selector="edit-widget-size-regularsize" aria-describedby="edit-widget-size--description"  type="radio" id="edit-widget-size-regularsize" name="widget_size" value="0" checked="checked" class="form-radio form-boolean form-boolean--type-radio" wfd-id="id15">
                                            <label for="edit-widget-size-regularsize" class="form-item__label option">Regular Size</label>
                                        </div>
                                        <div class="form-radio-item">
                                            <input data-drupal-selector="edit-widget-size-oversize" aria-describedby="edit-widget-size--description" type="radio" id="edit-widget-size-oversize" name="widget_size" value="1" class="form-radio form-boolean form-boolean--type-radio" wfd-id="id16">
                                            <label for="edit-widget-size-oversize" class="form-item__label option">Oversize</label>
                                        </div>
                                        <div style="font-size: small;" id="edit-widget-size--wrapper--description" data-drupal-field-elements="description" class="fieldset__description">It only works on desktop view.</div>
                                    </div>

                                    <div class="icon-type-wrapper row " id="select_icon_type">
                                        <label class="fcol-sm-12 col-form-label" style="margin-left: -10.500px; margin-right: -10.500px;">Select icon type:</label>
                                        <div class="col-sm-12" style=" margin-right: -15px;">
                                            <div class="row"><?php
                                                 for($it=1;$it<=29;$it++){
                                                     ?><div class="col-auto mb-30">
                                                         <div
                                                                 class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                             <input type="radio" id="edit-type-<?php echo $it;?>" <?php echo ($it==1)?' checked':''?> name="aioa_icon_type"
                                                                    value="aioa-icon-type-<?php echo $it;?>" class="form-radio" />
                                                             <label for="edit-type-<?php echo $it;?>" class="option">
                                                                 <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-<?php echo $it;?>.svg"
                                                                      width="65" height="65" style="height: 65px;" />
                                                                 <span class="visually-hidden">Type <?php echo $it;?></span>
                                                             </label>
                                                         </div>
                                                     </div><?php
                                                 }
                                            ?></div>
                                        </div>
                                        <div class="icon-custom-size-wrapper mb-3 row">
                                            <div class="col-sm-12">
                                                <label class="custom-switcher ">
                          <span class="custom-switcher_right">
                            <input type="checkbox" id="custom-size-switcher" class="custom-switcher_inp_2" value="1" />
                            <span class="custom-switcher_body" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                  title="Toggle to override selected size" data-bs-custom-class="switcher-tooltip">
                            </span>
                            <span class="custom-switcher_label">Enable icon custom size:</span>
                          </span>
                                                </label>
                                                <div class="custom-size-controls hide">
                                                    <div class="row">

                                                    </div>
                                                    <div class="col-auto controls ms-0">
                                                        <label for="exact-icon-size" class="form-label">Select exact icon size:</label>
                                                        <div class="input-group mb-2">
                                                            <input type="number" class="form-control"  style="height:auto"  id="widget_icon_size_custom" name="widget_icon_size_custom" oninput="this.value = Math.min(Math.max(this.value, 0), 150)" value="" min="20" max="150" aria-label="Value in pixels" aria-describedby="size-value-input_1" />
                                                            <span class="input-group-text"  style="height:auto"  id="size-value-input_1">PX</span>
                                                        </div>
                                                        <div class="description">20 - 150px are permitted values</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="icon-size-wrapper widget-icon row " id="select_icon_size">
                                        <label class="fcol-sm-12 col-form-label">Select icon size for Desktop: </label>

                                        <div class="col-sm-12" style="padding-right: calc(var(--bs-gutter-x)* .2);padding-left: calc(var(--bs-gutter-x)* .2);">
                                            <div class="row">
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-big" name="aioa_icon_size" value="aioa-big-icon"
                                                               class="form-radio" />
                                                        <label for="edit-size-big" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="75" height="75" style="height: 75px;"  class="iconimg"/>
                                                            <span class="visually-hidden">Big</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-medium" checked="" name="aioa_icon_size"
                                                               value="aioa-medium-icon" class="form-radio" />
                                                        <label for="edit-size-medium" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="65" height="65" style="height: 65px;"  class="iconimg"/>
                                                            <span class="visually-hidden">Medium</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-default" name="aioa_icon_size" value="aioa-default-icon"
                                                               class="form-radio" />
                                                        <label for="edit-size-default" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="55" height="55" style="height: 55px;"  class="iconimg"/>
                                                            <span class="visually-hidden">Default</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-small" name="aioa_icon_size" value="aioa-small-icon"
                                                               class="form-radio" />
                                                        <label for="edit-size-small" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="45" height="45" style="height: 45px;"  class="iconimg"/>
                                                            <span class="visually-hidden">Small</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-extra-small" name="aioa_icon_size"
                                                               value="aioa-extra-small-icon" class="form-radio" />
                                                        <label for="edit-size-extra-small" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="35" height="35" style="height: 35px;"   class="iconimg"/>
                                                            <span class="visually-hidden">Extra Small</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="icon-size-wrapper row" style="display: none">
                                        <label class="fcol-sm-12 col-form-label">Select icon size for mobile: </label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-big" name="aioa_icon_size_mb" value="aioa-big-icon-mb"
                                                               class="form-radio" />
                                                        <label for="edit-size-big" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="75" height="75" />
                                                            <span class="visually-hidden">Big</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-medium" checked="" name="aioa_icon_size_mb"
                                                               value="aioa-medium-icon-mb" class="form-radio" />
                                                        <label for="edit-size-medium" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="65" height="65" />
                                                            <span class="visually-hidden">Medium</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-default" name="aioa_icon_size_mb"
                                                               value="aioa-default-icon-mb" class="form-radio" />
                                                        <label for="edit-size-default" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="55" height="55" />
                                                            <span class="visually-hidden">Default</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-small" name="aioa_icon_size_mb" value="aioa-small-icon-mb"
                                                               class="form-radio" />
                                                        <label for="edit-size-small" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="45" height="45" />
                                                            <span class="visually-hidden">Small</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-30">
                                                    <div
                                                            class="js-form-item form-item js-form-type-radio form-type-radio js-form-item-position form-item-position">
                                                        <input type="radio" id="edit-size-extra-small" name="aioa_icon_size_mb"
                                                               value="aioa-extra-small-icon-mb" class="form-radio" />
                                                        <label for="edit-size-extra-small" class="option">
                                                            <img src="/apps/allinoneaccessibility/src/img/aioa-icon-type-1.svg"
                                                                 width="35" height="35"  />
                                                            <span class="visually-hidden">Extra Small</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="save-changes-btn">
                                        <button type="button" id="aioa_submit" class="btn btn-primary" style="background-color: #420083;color: #fff">Save Changes</button>
                                        <div class="mt-3 row " id="save-changes-success">
                                            <div class="col-sm-12">
                                                <div class="form-text">It may take a few seconds for changes to appear on your website. If you
                                                    don't see the changes, try clearing your browser cache or checking in a private browsing window.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <div id="loader">
                                        <div class="spinner"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script nonce="<?php echo $contentSecurityPolicyNonceManager->getNonce();?>" src="/apps/allinoneaccessibility/src/js/jquery.min.js"></script>
    <script nonce="<?php echo $contentSecurityPolicyNonceManager->getNonce();?>" src="/apps/allinoneaccessibility/src/js/aioasetting.js"></script>