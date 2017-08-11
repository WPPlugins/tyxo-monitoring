<?php

class TyxoPage {

    var $data;

	function __construct($auth_response_data) {

	    $this->data = $auth_response_data;

        #
        # Update Selected ID's
        #

        if(!empty($auth_response_data['selected']['webcheck'])){
            update_option('webcheck_big_id', $auth_response_data['selected']['webcheck']);
        } else {
            delete_option('webcheck_big_id');
        }

        if(!empty($auth_response_data['selected']['tracker'])){
            update_option('tracker_big_id', $auth_response_data['selected']['tracker']);
        } else {
            delete_option('tracker_big_id');
        }

        if(!empty($auth_response_data['selected']['statuspage'])){
            update_option('statuspage_big_id', $auth_response_data['selected']['statuspage']);
        } else {
            delete_option('statuspage_big_id');
        }


        #
        # Webcheck
        #

        $webcheck_options    = '<option value="0">Not selected</option>';
        if(!empty($this->data['webchecks']) && is_array($this->data['webchecks'])) {

            $selected_big_id    = (!empty($this->data['selected']['webcheck']))?$this->data['selected']['webcheck']:null;
            foreach ($this->data['webchecks'] as $webcheck) {

                $big_id     = (!empty($webcheck['big_id']))?$webcheck['big_id']:null;
                $title      = (!empty($webcheck['title']))?$webcheck['title']:null;
                $url        = (!empty($webcheck['title']))?$webcheck['url']:null;
                $selected   = ($selected_big_id == $big_id)?' selected':'';
                $webcheck_options    .= '<option value="'.$big_id.'"'.$selected.'>'.$title.' ['.$url.']</option>';

            }

        }

        #
        # Tracker
        #

        $tracker_options    = '<option value="0">Inactive / Not selected</option>';
        if(!empty($this->data['trackers']) && is_array($this->data['trackers'])) {

            $selected_big_id    = (!empty($this->data['selected']['tracker']))?$this->data['selected']['tracker']:null;
            foreach ($this->data['trackers'] as $tracker) {

                $big_id     = (!empty($tracker['big_id']))?$tracker['big_id']:null;
                $title      = (!empty($tracker['title']))?$tracker['title']:null;
                $url        = (!empty($tracker['title']))?$tracker['url']:null;
                $selected   = ($selected_big_id == $big_id)?' selected':'';
                $tracker_options    .= '<option value="'.$big_id.'"'.$selected.'>'.$title.' ['.$url.']</option>';

            }

        }


        #
        # Status Page
        #

        $statuspage_options    = '<option value="0">Inactive / Not selected</option>';
        if(!empty($this->data['statuspages']) && is_array($this->data['statuspages'])) {

            $selected_big_id    = (!empty($this->data['selected']['statuspage']))?$this->data['selected']['statuspage']:null;
            foreach ($this->data['statuspages'] as $statuspage) {

                $big_id     = (!empty($statuspage['big_id']))?$statuspage['big_id']:null;
                $title      = (!empty($statuspage['title']))?$statuspage['title']:null;
                $url        = (!empty($statuspage['title']))?$statuspage['url']:null;
                $selected   = ($selected_big_id == $big_id)?' selected':'';
                $statuspage_options    .= '<option value="'.$big_id.'"'.$selected.'>'.$title.' ['.$url.']</option>';

            }

        }

	    echo '<script>tyxo_api_key = "'.TYXO_API_KEY.'";</script>';
        echo '<div id="tyxo_loader_container"></div><div class="txwrap">
			
			<div class="forms-container">
            <form name="login" action="" method="post">
			
                <div class="panel">
                    <div class="panel-body" style="text-align: center">
                        <img src="'.TYXO_PLUGIN_LIB_HTTP.'static/ic/tyxo_logo_bgwhite.png" height="44">
                    </div>
                </div>
                
                <div class="panel">
                
                    <div class="panel-heading">
                        <h2>Uptime Checker</h2>
                    </div>
                    <div class="panel-body">
                        <select name="webcheck_big_id" class="width100p">'.$webcheck_options.'</select>
                    </div>
                    
                </div>
                
                <div class="panel">
                
                    <div class="panel-heading">
                        <h2>Live Tracker</h2>
                    </div>
                    <div class="panel-body">
                        <select name="tracker_big_id" class="width100p">'.$tracker_options.'</select>
                    </div>
                    
                </div>
                
                <div class="panel">
                
                    <div class="panel-heading">
                        <h2>Status Page</h2>
                    </div>
                    <div class="panel-body">
                        <select name="statuspage_big_id" class="width100p">'.$statuspage_options.'</select>
                    </div>
                    
                </div>
                
                <div class="cont">
                    <button type="submit" name="save" value="1" class="huge width100p" style="background-color: #2ab27b">Save Changes</button>
                </div>
            
            </form>
            </div>
            
            
            <div class="panel" style="margin-top: 64px">
			    <div class="panel-body" style="text-align: center">
			        <div><a href="'.$_SERVER['REQUEST_URI'].'&amp;logout=1&amp;s='.sha1(TYXO_API_KEY).'">Log Out</a></div>
			    </div>
            </div>
			
		</div>';

	}


}

?>