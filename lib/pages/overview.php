<?php

class TyxoOverviewPage {

	function __construct() {

	    //update_option('profile_big_id', 6487722668844);

		$this->display_content();

	}

	function display_content() {

        echo '<script>tyxo_api_key = "'.TYXO_API_KEY.'";</script>';
        echo '<div id="tyxo_loader_container"></div><div class="txwrap">
			
			
			<canvas id="chart" width="680" height="180"></canvas>

			
		<script>
			
	    jQuery(document).ready(function(){
        
	        Chart.defaults.global.legend.display = false;
	        
	        var ctx = jQuery("#chart");
	        var data = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: false,
                        fill: true,
                        lineTension: 0.1,
                        backgroundColor: "rgba(255,255,255, 1)", //
                        borderColor: "rgba(0,0,0, 1)", // Line Color
                        borderCapStyle: \'butt\',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: \'miter\',
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: [65, 59, 80, 81, 56, 55, 40],
                        spanGaps: false,
                    }
                ]
            };
            
            var myChart = new Chart(ctx, {
            type: "line",
            data: data,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }],
                        xAxes: [{
                            //display: false
                        }]
                    }
                }
            });
             
        });
                    
        </script>
			
			
		</div>';

	}


}

new TyxoOverviewPage();
?>