<?php
/*
Plugin Name: Dicm.dk - Toolbox
Plugin URI: http://dicm.dk
Description: A toolbox from dicm.dk
Version: 1.0
Author: Kim Vinberg - dicm.dk
Author URI: http://dicm.dk
License: 

Copyright 2013 Kim Vinberg (email : info@dicm.dk)

*/

if ( is_admin() ){ // admin actions
// Hook for adding admin menus
add_action('admin_menu', 'dtb_add_menus');
add_action( 'admin_init', 'register_dtb_settings' );

if(isset($_POST['dtb_submit'])) {

update_option( 'dtb_license', $_POST['dtb_license'] );
}

function register_dtb_settings() {
	//register our settings
	register_setting( 'dtb_settings_group', 'dtb_license' );
}

//THIS IS THE APP VERSION: FRONT END: OPEN SOURCE:
$apiUsername = $_SERVER['SERVER_NAME'];
$apiPassword = get_option('dtb_license');


 function call($method, $address, $params = null) {
 global $apiUsername;
 global $apiPassword;
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //DICM fix
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        
        // Authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUsername:$apiPassword");
        
        // Request method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        

        // PUT parameters
        if ($method == "PUT" && $params != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
		
        // POST parameters
        if ($method == "POST" && $params != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
		
        // URL including API version and sub-address
        curl_setopt($ch, CURLOPT_URL, "http://api.dicm.dk/v1/" . $address);
        $rawResponse = curl_exec($ch);

        curl_close($ch);

        // Return response array

		return $rawResponse;
		
    }
	
	

// action function for above hook
function dtb_add_menus() {

   // Add a new top-level menu (ill-advised):
    add_menu_page(__('Dicm - Toolbox','menu-test'), __('Dicm - Toolbox','menu-test'), 'manage_options', 'mt-top-level-handle', 'dtb_toplevel_page' ,'','0');


   // add_submenu_page('mt-top-level-handle', __('Test Sublevel','menu-test'), __('Test Sublevel','menu-test'), 'manage_options', 'sub-page', 'dtb_sublevel_page');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('mt-top-level-handle', __('Options','menu-options'), __('Options','menu-options'), 'manage_options', 'dtb_options', 'dtb_sublevel_options');
}

// dtb_settings_page() displays the page content for the Test settings submenu
function dtb_settings_page() {
    echo "<h2>" . __( 'Test Settings', 'menu-test' ) . "</h2>";
}

// dtb_tools_page() displays the page content for the Test Tools submenu
function dtb_tools_page() {
    echo "<h2>" . __( 'Test Tools', 'menu-test' ) . "</h2>";
}

// dtb_toplevel_page() displays the page content for the custom Dicm.dk - Toolbox menu
function dtb_toplevel_page() {
    echo "<h2>" . __( 'Dicm.dk - Toolbox', 'menu-test' ) . "</h2>";
}

// dtb_sublevel_page() displays the page content for the first submenu
// of the custom Dicm.dk - Toolbox menu
/*function dtb_sublevel_page() {
    echo "<h2>" . __( 'Test Sublevel', 'menu-test' ) . "</h2>";
}*/

// dtb_sublevel_page2() displays the page content for the second submenu
// of the custom Dicm.dk - Toolbox menu
function dtb_sublevel_options() {
	echo "<div class=\"wrap\">";
	echo '<div id="icon-options-general" class="icon32"><br></div>';
    echo "<h2>" . __('Dicm - Toolbox options','menu-options') . "</h2>";
  
  $validateLicense = json_decode(call("POST", "validatelicense", ""))->success; 

  //ApiCall('cmd=ValidateLicense&domain='.$_SERVER['SERVER_NAME'].'&license='.get_option('dtb_license').'');
    ?>
    
    <p>On this page, you can activate and deactivate options in the plugin Dicm - Toolbox.</p>
    
   

      <form method="post" action="">
    <?php settings_fields( 'dtb_settings_group' ); ?>
    <?php// do_settings( 'dtb_settings_group' );
     ?>
    
            <?php settings_fields('todo_list_options'); ?>
            <table class="form-table">
                <tr valign="top"><th scope="row">License:</th>
                    <td><input type="text" name="dtb_license" value="<?php echo get_option('dtb_license'); ?>" /> <?php   if($validateLicense != 1) { ?><font color="#FF0000">* Required</font> <?php } ?>
                    <p class="description">Example: JG32-OMOX-U8K0-87TL-QRKU-19IR</p>
                    </td>
                </tr>
             <tr valign="top"><th scope="row">License And Server Status:</th>
                    <td><?php if($validateLicense == 1) { echo "<font color=\"#008000\">Everything in order.</font>";} else { echo "<font color=\"#FF0000\">Your license is invalid or server down, plugin cannot work now.</font>    <p class=\"description\">You an buy a license from <a href=\"http://dicm.dk\" target=\"_blank\">http://dicm.dk</a> or see current server status at <a href=\"http://api.dicm.dk\" target=\"_blank\">http://api.dicm.dk</a></p>";} ?>       
                    </td>
                </tr>
              </table>
            
            <?php 
             if($validateLicense == 1) { 
            ?> 
             <hr> 
              <table class="form-table">  
                <tr valign="top"><th scope="row">Return To Login Fix <b>* Free</b>:</th>
                    <td><input type="checkbox" name=""> Check to activate
                     <p class="description">Function that maybe can avoid the problem with logging in where username and password is correct. Can be activated at any time.</p>
                    </td>
                </tr>
                <tr valign="top"><th scope="row">Hidden Field Comment Spam Removal <b>* Free</b>:</th>
                    <td><input type="checkbox" name=""> Check to activate
                     <p class="description">Add a hidden field to comment form, if the field is filled, it's a bot and comment will be stopped.<br><font color="#FF0000">Warning!</font> Can conflict with other plugins!</p>
                    </td>
                </tr>
                <tr valign="top"><th scope="row">Cookie Comment Spam Removal <b>* Free</b>:</th>
                    <td><input type="checkbox" name=""> Check to activate
                     <p class="description">Adds a cookie to the users before commenting, if the user got the cookie, then the comment will be posted.Some bots do not set cookies.</p>
                    </td>
                </tr>
					<tr valign="top"><th scope="row">API Comment Spam Removal:</th>
                    <td><input type="checkbox" name=""> Check to activate
                     <p class="description">Checks with dicm.dk's API for known spammers or spam content. If a spammer is found , the comment is blocked. Recommended to use, gets better every day.</p>
                    </td>
                </tr>
					<tr valign="top"><th scope="row">API Block Login:</th>
                    <td>
                    <input type="checkbox" name=""> Check to activate
                    <p class="description">Used to only allow some users to connect to the login. Can be set by country, ip and with a bypass in case you are not at the usual ip.<br><font color="#008000">Highly Recommended!</font> One of the best ways of stopping hackers from accesing your site with brute force.</p>
                    <input type="text" name="dtb_api_block_login_ips" value="" />
                    <p class="description">Allowed ip's (keep empty if no specific ip). Format: <?php echo $_SERVER['REMOTE_ADDR'];?>,<?php echo $_SERVER['REMOTE_ADDR'];?><br><font color="#FF0000">Warning!</font> If you type wrong ip, you will get locked out. Your current ip is: <?php echo $_SERVER['REMOTE_ADDR'];?></p>
<select name="country" multiple>
<option value="">All allowed</option>
<option value="AF">Afghanistan</option>
<option value="AL">Albania</option>
<option value="DZ">Algeria</option>
<option value="AS">American Samoa</option>
<option value="AD">Andorra</option>
<option value="AG">Angola</option>
<option value="AI">Anguilla</option>
<option value="AG">Antigua &amp; Barbuda</option>
<option value="AR">Argentina</option>
<option value="AA">Armenia</option>
<option value="AW">Aruba</option>
<option value="AU">Australia</option>
<option value="AT">Austria</option>
<option value="AZ">Azerbaijan</option>
<option value="BS">Bahamas</option>
<option value="BH">Bahrain</option>
<option value="BD">Bangladesh</option>
<option value="BB">Barbados</option>
<option value="BY">Belarus</option>
<option value="BE">Belgium</option>
<option value="BZ">Belize</option>
<option value="BJ">Benin</option>
<option value="BM">Bermuda</option>
<option value="BT">Bhutan</option>
<option value="BO">Bolivia</option>
<option value="BL">Bonaire</option>
<option value="BA">Bosnia &amp; Herzegovina</option>
<option value="BW">Botswana</option>
<option value="BR">Brazil</option>
<option value="BC">British Indian Ocean Ter</option>
<option value="BN">Brunei</option>
<option value="BG">Bulgaria</option>
<option value="BF">Burkina Faso</option>
<option value="BI">Burundi</option>
<option value="KH">Cambodia</option>
<option value="CM">Cameroon</option>
<option value="CA">Canada</option>
<option value="IC">Canary Islands</option>
<option value="CV">Cape Verde</option>
<option value="KY">Cayman Islands</option>
<option value="CF">Central African Republic</option>
<option value="TD">Chad</option>
<option value="CD">Channel Islands</option>
<option value="CL">Chile</option>
<option value="CN">China</option>
<option value="CI">Christmas Island</option>
<option value="CS">Cocos Island</option>
<option value="CO">Colombia</option>
<option value="CC">Comoros</option>
<option value="CG">Congo</option>
<option value="CK">Cook Islands</option>
<option value="CR">Costa Rica</option>
<option value="CT">Cote D'Ivoire</option>
<option value="HR">Croatia</option>
<option value="CU">Cuba</option>
<option value="CB">Curacao</option>
<option value="CY">Cyprus</option>
<option value="CZ">Czech Republic</option>
<option value="DK">Denmark</option>
<option value="DJ">Djibouti</option>
<option value="DM">Dominica</option>
<option value="DO">Dominican Republic</option>
<option value="TM">East Timor</option>
<option value="EC">Ecuador</option>
<option value="EG">Egypt</option>
<option value="SV">El Salvador</option>
<option value="GQ">Equatorial Guinea</option>
<option value="ER">Eritrea</option>
<option value="EE">Estonia</option>
<option value="ET">Ethiopia</option>
<option value="FA">Falkland Islands</option>
<option value="FO">Faroe Islands</option>
<option value="FJ">Fiji</option>
<option value="FI">Finland</option>
<option value="FR">France</option>
<option value="GF">French Guiana</option>
<option value="PF">French Polynesia</option>
<option value="FS">French Southern Ter</option>
<option value="GA">Gabon</option>
<option value="GM">Gambia</option>
<option value="GE">Georgia</option>
<option value="DE">Germany</option>
<option value="GH">Ghana</option>
<option value="GI">Gibraltar</option>
<option value="GB">Great Britain</option>
<option value="GR">Greece</option>
<option value="GL">Greenland</option>
<option value="GD">Grenada</option>
<option value="GP">Guadeloupe</option>
<option value="GU">Guam</option>
<option value="GT">Guatemala</option>
<option value="GN">Guinea</option>
<option value="GY">Guyana</option>
<option value="HT">Haiti</option>
<option value="HW">Hawaii</option>
<option value="HN">Honduras</option>
<option value="HK">Hong Kong</option>
<option value="HU">Hungary</option>
<option value="IS">Iceland</option>
<option value="IN">India</option>
<option value="ID">Indonesia</option>
<option value="IA">Iran</option>
<option value="IQ">Iraq</option>
<option value="IR">Ireland</option>
<option value="IM">Isle of Man</option>
<option value="IL">Israel</option>
<option value="IT">Italy</option>
<option value="JM">Jamaica</option>
<option value="JP">Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakhstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="NK">Korea North</option>
<option value="KS">Korea South</option>
<option value="KW">Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Laos</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU">Luxembourg</option>
<option value="MO">Macau</option>
<option value="MK">Macedonia</option>
<option value="MG">Madagascar</option>
<option value="MY">Malaysia</option>
<option value="MW">Malawi</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MQ">Martinique</option>
<option value="MR">Mauritania</option>
<option value="MU">Mauritius</option>
<option value="ME">Mayotte</option>
<option value="MX">Mexico</option>
<option value="MI">Midway Islands</option>
<option value="MD">Moldova</option>
<option value="MC">Monaco</option>
<option value="MN">Mongolia</option>
<option value="MS">Montserrat</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Nambia</option>
<option value="NU">Nauru</option>
<option value="NP">Nepal</option>
<option value="AN">Netherland Antilles</option>
<option value="NL">Netherlands (Holland, Europe)</option>
<option value="NV">Nevis</option>
<option value="NC">New Caledonia</option>
<option value="NZ">New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="NW">Niue</option>
<option value="NF">Norfolk Island</option>
<option value="NO">Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau Island</option>
<option value="PS">Palestine</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PO">Pitcairn Island</option>
<option value="PL">Poland</option>
<option value="PT">Portugal</option>
<option value="PR">Puerto Rico</option>
<option value="QA">Qatar</option>
<option value="ME">Republic of Montenegro</option>
<option value="RS">Republic of Serbia</option>
<option value="RE">Reunion</option>
<option value="RO">Romania</option>
<option value="RU">Russia</option>
<option value="RW">Rwanda</option>
<option value="NT">St Barthelemy</option>
<option value="EU">St Eustatius</option>
<option value="HE">St Helena</option>
<option value="KN">St Kitts-Nevis</option>
<option value="LC">St Lucia</option>
<option value="MB">St Maarten</option>
<option value="PM">St Pierre &amp; Miquelon</option>
<option value="VC">St Vincent &amp; Grenadines</option>
<option value="SP">Saipan</option>
<option value="SO">Samoa</option>
<option value="AS">Samoa American</option>
<option value="SM">San Marino</option>
<option value="ST">Sao Tome &amp; Principe</option>
<option value="SA">Saudi Arabia</option>
<option value="SN">Senegal</option>
<option value="RS">Serbia</option>
<option value="SC">Seychelles</option>
<option value="SL">Sierra Leone</option>
<option value="SG">Singapore</option>
<option value="SK">Slovakia</option>
<option value="SI">Slovenia</option>
<option value="SB">Solomon Islands</option>
<option value="OI">Somalia</option>
<option value="ZA">South Africa</option>
<option value="ES">Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SZ">Swaziland</option>
<option value="SE">Sweden</option>
<option value="CH">Switzerland</option>
<option value="SY">Syria</option>
<option value="TA">Tahiti</option>
<option value="TW">Taiwan</option>
<option value="TJ">Tajikistan</option>
<option value="TZ">Tanzania</option>
<option value="TH">Thailand</option>
<option value="TG">Togo</option>
<option value="TK">Tokelau</option>
<option value="TO">Tonga</option>
<option value="TT">Trinidad &amp; Tobago</option>
<option value="TN">Tunisia</option>
<option value="TR">Turkey</option>
<option value="TU">Turkmenistan</option>
<option value="TC">Turks &amp; Caicos Is</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="GB">United Kingdom</option>
<option value="US">United States of America</option>
<option value="UY">Uruguay</option>
<option value="UZ">Uzbekistan</option>
<option value="VU">Vanuatu</option>
<option value="VS">Vatican City State</option>
<option value="VE">Venezuela</option>
<option value="VN">Vietnam</option>
<option value="VB">Virgin Islands (Brit)</option>
<option value="VA">Virgin Islands (USA)</option>
<option value="WK">Wake Island</option>
<option value="WF">Wallis &amp; Futana Is</option>
<option value="YE">Yemen</option>
<option value="ZR">Zaire</option>
<option value="ZM">Zambia</option>
<option value="ZW">Zimbabwe</option>
</select>
	<p class="description">Select the allowed countries, if no specific country, then dont select any. Multiply select, use CTRL when clicking.</p>
<p class="description">How to bypass if not on allowed IP or country? Contact dicm.dk and your IP or country can be unlocked for access (only if you are allowed to get access).</p>
							
							
                     
                    </td>
                </tr>
<tr valign="top"><th scope="row">API Block POST and GET'S:</th>
                    <td><input type="checkbox" name=""> Check to activate
                     <p class="description">Check every POST and GET sent where the plugin can access, if it find something bad, it wi ll try to stop the attack.<br><font color="#008000">Highly Recommended!</font> Description says it all.<br><font color="#FF0000">Warning!</font> This can slow down the site a bit and there can be false positives. Contact dicm.dk for more information about this.</p>
                    </td>
                </tr>
                
            </table>
            <p class="submit">
            <input type="hidden" name="dtb_submit" value="1" />
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
        
        <?php
        
        } else { //  if($validateLicense == 1) { 
        ?>
          </table>
            <p class="submit">
            <input type="hidden" name="dtb_submit" value="1" />
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
        
        <hr>
        <?php
        echo "<p style=\"text-align:center;width:100%;float:left;\">Something is wrong, options cannot be edited. Check message above.</p>";
        }
}
 
 /*

<div id="cookiepopup" style="font-size: 11px; height: 100px; color: rgb(205, 205, 205); background-color: rgb(45, 45, 45); position: fixed; bottom: 0px; left: 0px; width: 100%; z-index: 1; opacity: 0.8; font-family: MuseoSansRounded500, 'san serif'; background-position: initial initial; background-repeat: initial initial;">
      <div style="margin:0 auto;width:820px;position: relative;background: url(/res/img/cookie/big.png) no-repeat 20px 14px; padding:10px 20px 10px 90px;">
        <a href="#close" class="close" style="position:absolute; top:10px;right:10px;" title="luk popup"><img src="/res/img/cookie/x.png"></a>
        <h2 style="font-family: MuseoSansRounded500,san serif; font-weight: normal;font-size: 14px;color:white;margin-bottom:5px;">
          Fullrate.dk bruger cookies
        </h2>

        <p style="margin-top:5px;">
          Cookies er nødvendige for at få hjemmesiden til at fungere og giver info om, hvordan du bruger vores hjemmeside, så vi kan forbedre den både for dig og for andre. Fullrate.dk bruger primært cookies til trafikmåling, login og optimering af sidens indhold.
          <br>
          Hvis du klikker videre på siden, accepterer du vores brug af cookies. <a href="/om_fullrate/cookies/" style="color:#cdcdcd;text-decoration: none;border-bottom: 1px dotted #f8f8f8;">Læs mere om cookies på Fullrate.dk</a>
        </p>
      </div>
    </div>
    */
    
    }
    
    ?>