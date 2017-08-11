<?php

class TyxoPage {

    function __construct() {

        echo '<script>tyxo_api_key = "'.TYXO_API_KEY.'";</script>';
        echo '<div id="tyxo_loader_container"></div><div class="txwrap">
			'.$this->html().'
		</div>';

    }

    function html() {

        if(empty(TYXO_TRACKER_BIG_ID)) {

            $html   = '<div class="panel">
                <div class="panel-body">
                    <div>Live Tracker is not configured yet.</div>
                    <div>Please, go to <strong>Tyxo Monitoring</strong> > <strong>Settings</strong> tab.</div>
                </div>
            </div>';

        } else {

            $html = '<script>
			
            jQuery( document ).ready(function() {

               function process(res) {
                  
                  if(isset(res.container) && isset(res.html)) {
                      jQuery(res.container).html(res.html);
                  }
                  
                  if(isset(res.error_msg)) {
                      jQuery(".txwrap").html(res.error_msg);
                  }
                  
               }
               
               var params       = {};
               params.big_id    = "'.TYXO_TRACKER_BIG_ID.'";
               tx_jsonp("tracker/overview.do", params,  process, loader_animation_base, 180);
                         
            });
            
            
        </script>';

        }

    return $html;
    }

}

new TyxoPage();
?>