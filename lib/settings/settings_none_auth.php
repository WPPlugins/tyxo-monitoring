<?php

class TyxoPage {

    function __construct() {

        if(!empty($_POST['login'])) {
            $this->login();
        }

        if(!empty($_POST['signup'])) {
            $this->signup();
        }

        # Sign Up Error Message
        $sign_up_error_msg = get_option('signup_msg_error');
        if(!empty($sign_up_error_msg)) {
            delete_option('signup_msg_error');
        }

        # Log In Error Message
        $login_error_msg = get_option('login_msg_error');
        if(!empty($login_error_msg)) {
            delete_option('login_msg_error');
        }


        $login_email   = (!empty($_POST['tyxo_email']))?$_POST['tyxo_email']:'';
        $signup_email  = (!empty($_POST['tyxo_signup_email']))?$_POST['tyxo_signup_email']:get_bloginfo('admin_email');
        $signup_title  = (!empty($_POST['tyxo_title']))?$_POST['tyxo_title']:get_bloginfo('name');
        $signup_url    = (!empty($_POST['tyxo_website_url']))?$_POST['tyxo_website_url']:get_site_url();


        echo '<script>tyxo_api_key = "'.TYXO_API_KEY.'";</script>';
        echo '<div id="tyxo_loader_container"></div><div class="txwrap" style="width: 740px">
			
			<div class="panel">
			    <div class="panel-body" style="text-align: center">
			        <img src="'.TYXO_PLUGIN_LIB_HTTP.'static/ic/tyxo_logo_bgwhite.png" height="44">
			    </div>
            </div>
			
			<div class="col2 cf">
			    <div class="col">
			    
                    <div class="forms-container">
                    <form name="login" action="" method="post">
                    
                        <div class="panel">
                            <div class="panel-body" style="padding-bottom: 0">
                            
                            <div class="auth-head">
                                <h2>Already have Tyxo account?</h2>
                                <h3>Log In</h3>
                            </div>'.$login_error_msg.'
                            
                            <div class="cont">
                                <h3>Email</h3>
                                <input type="text" name="tyxo_email" value="'.htmlentities($login_email).'" class="width100p">
                            </div>
                            
                            <div class="cont">
                                <h3>Password</h3>
                                <input type="password" name="tyxo_pwd" class="width100p">
                            </div>
                                        
                            <div class="cont">
                                <button type="submit" name="login" value="1" class="huge width100p" style="background-color: #2ab27b">Log In</button>
                            </div>
                            
                            </div>
                        </div>
                        <a href="https://tyxo.com/password/reset" target="_tab">Forgot your password?</a>
                    
                    </form>
                    </div>
			    
                </div><div class="col">
			    
			        <div class="forms-container">
                    <form name="signup" action="" method="post" id="#myForm">
                    
                        <div class="panel">
                            <div class="panel-body" style="padding-bottom: 0">
                            
                                <div class="auth-head">
                                    <h2>Don\'t have an account? Make one.</h2>
                                    <h3>Sign Up</h3>
                                </div>'.$sign_up_error_msg.'
                            
                                <div class="cont">
                                    <h3>Email (needs to be verified)</h3>
                                    <input type="text" name="tyxo_signup_email" value="'.htmlentities($signup_email).'" class="width100p">
                                </div>
                                
                                <div class="cont">
                                    <h3>Password</h3>
                                    <input type="password" name="tyxo_pwd" class="width100p">
                                </div>
                                      
                                <div style="margin: 32px 0; text-align: center">
                                    <svg width="152px" height="8px" viewBox="0 0 152 8" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="morse-code" style="fill: #E2E2E2;">
                                      <circle id="Oval-2" cx="4" cy="4" r="4"></circle>
                                      <circle id="Oval-2" cx="70" cy="4" r="4"></circle>
                                      <rect id="Rectangle-18" x="12" y="0" width="50" height="8"></rect>
                                      <rect id="Rectangle-18-Copy" x="78" y="0" width="50" height="8"></rect>
                                      <circle id="Oval-2" cx="136" cy="4" r="4"></circle>
                                      <circle id="Oval-2-Copy" cx="148" cy="4" r="4"></circle>
                                    </g>
                                    </svg>
                                </div>
                                
                                
                                <div class="cont">
                                    <h3>Website URL</h3>
                                    <input type="text" name="tyxo_website_url" value="'.htmlentities($signup_url).'" placeholder="http://example.com" class="width100p">
                                </div>
                                
                                <div class="cont">
                                    <h3>Title (for quick reference)</h3>
                                    <input type="text" name="tyxo_title" value="'.htmlentities($signup_title).'" class="width100p">
                                </div>
                                            
                                <div class="cont">
                                    <button type="submit" name="signup" value="1" class="huge width100p" style="background-color: #2ab27b">Sign Up</button>
                                </div>
                            
                            </div>
                        </div>
                    
                    </form>
                    </div>
			    
			    
                </div>
            </div>
			
			
		</div>
        ';

    }

    function login() {

        $json_array             = array();
        $json_array['email']    = (!empty($_POST['tyxo_email']))?$_POST['tyxo_email']:null;
        $json_array['password'] = (!empty($_POST['tyxo_pwd']))?$_POST['tyxo_pwd']:null;
        $json_array['siteurl']  = get_site_url();
        $auth_response          = TyxoClass::apiCall("auth/login.do", array("json"=>wp_json_encode($json_array)));

        #
        # Check for errors
        #

        if(!empty($auth_response['error_msg'])) {
            update_option('login_msg_error', '<div class="auth_error_msg">'.$auth_response['error_msg'].'</div>');
        } else {
            delete_option('login_msg_error');
        }

        #
        # Set Auth API Key
        #

        if(!empty($auth_response['apikey'])) {
            update_option('api_key', $auth_response['apikey']);
            $this->succes_auth();
        } else {
            delete_option('api_key');
        }

    }


    function signup() {

        $json_array             = array();
        $json_array['email']    = (!empty($_POST['tyxo_signup_email']))?$_POST['tyxo_signup_email']:null;
        $json_array['password'] = (!empty($_POST['tyxo_pwd']))?$_POST['tyxo_pwd']:null;
        $json_array['website']  = (!empty($_POST['tyxo_website_url']))?$_POST['tyxo_website_url']:null;
        $json_array['title']    = (!empty($_POST['tyxo_title']))?$_POST['tyxo_title']:null;
        $json_array['timezone'] = get_option('timezone_string');
        $auth_response          = TyxoClass::apiCall("auth/signup.do", array("json"=>wp_json_encode($json_array)));

        #
        # Check for errors
        #

        if(!empty($auth_response['error_msg'])) {
            update_option('signup_msg_error', '<div class="auth_error_msg">'.$auth_response['error_msg'].'</div>');
        } else {
            delete_option('signup_msg_error');
        }

        #
        # Set Auth API Key
        #

        if(!empty($auth_response['apikey'])) {

            update_option('api_key', $auth_response['apikey']);

        } else {
            delete_option('api_key');
        }

        #
        # Set Profile ID
        #

        if(!empty($auth_response['profile_id'])) {

            update_option('profile_big_id', $auth_response['profile_id']);

        } else {
            delete_option('profile_big_id');
        }


        if(!empty($auth_response['apikey']) && !empty($auth_response['profile_id'])) {
            $this->succes_auth();
        }

    }


    function succes_auth() {

        echo '<div class="txwrap">
			
            <div class="panel">
                <div class="panel-body" style="text-align: center">
                    <img src="'.TYXO_PLUGIN_LIB_HTTP.'static/ic/tyxo_logo_bgwhite.png" height="44">
                    <h2>Success!</h2>
                    <div><a href="'.$_SERVER['REQUEST_URI'].'">Continue to Settigns page</a></div>
                </div>
            </div>
        
        </div>';
        exit;

    }

}

new TyxoPage();
?>