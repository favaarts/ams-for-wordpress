<?php

get_header();  ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<div class="container-wrap">
    <div class="site-content"> <!-- site-content" -->
     <div class="container no-sidebar ams-content">
      <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <!-- Entry content -->
        <div class="entry-content">

            <!-- New Asstes Details page -->
            <?php
                global $wp, $wpdb;
                
                $prodictidaray = $wp->query_vars['proname'];
                $prodictid = explode("-",$prodictidaray);

                $ab = $wp->query_vars['category'];
                
                
                $bgcolor = get_option('wpams_button_colour_btn_label');
                if(empty($bgcolor))
                {
                    $bgcolor = "#337AB7";
                }
                
                //
                $post = get_post($prodictid[0]);
                $blocks = parse_blocks($post->post_content);

                if($blocks[0]['attrs']['register_assets_url'])
                {
                    $landingurl = $blocks[0]['attrs']['register_assets_url'];
                }
                else
                {
                    $apiurl = get_option('wpams_url_btn_label');
                    $landingurl = "https://".$apiurl.".amsnetwork.ca/login";
                }

                if($blocks[0]['attrs']['ams_purchase_url'])
                {
                    $purchaselandingurl = $blocks[0]['attrs']['ams_purchase_url'];
                }
                else
                {
                    $apiurl = get_option('wpams_url_btn_label');
                    $purchaselandingurl = "https://".$apiurl.".amsnetwork.ca/login";
                }

                $targeturl = $blocks[0]['attrs']['register_assets_urltab'];
                if(empty($targeturl))
                {
                    $targeturl = "_self";
                }

                $arrayResult = get_apirequest(NULL,NULL,$prodictid[1]);
            ?>
            <div class="wp-block-columns main-content main-content-three-col">
            <input type="hidden" id="getpageid" value="<?php echo $prodictid[1]; ?>">
            <input type="hidden" id="assettype" value="<?php echo $arrayResult['asset_detail']['type']; ?>">
            <input type="hidden" id="bulkassetscalendar" value="<?php echo $arrayResult['asset_detail']['is_bulk_asset']; ?>">    
                <?php
                foreach($arrayResult as $json_value) 
                {
                    $priceone = explode("-",$json_value['price_types'][0][0]);
                    $pricetwo = explode("-",$json_value['price_types'][1][0]);
                ?>
                <div class="categorysearchdata right-col">
                    <div class="eventdetail assetsdetail">
                        <div class="event-img-sec">
                            <?php
                                echo"<div class='img-sec'>";
                                if($json_value['photo'] == NULL || $json_value['photo'] == "")
                                {  
                                     echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$json_value['name'].">";
                                }
                                else
                                {
                                    echo "<img src=".$json_value['photo_medium']." alt=".$json_value['name'].">";
                                } 
                                echo "</div>";


                                echo "<div class='ing-title'>
                                    <h1>". $json_value['name'] ."</h1>
                                    <span>". $json_value['category_name'] ."</span>";
                                echo "<div class='enrollment enrtop'>";
                                        if (!isset($blocks[0]['attrs']['member']))
                                        {
                                            echo "<h3>Member Price</h3>";
                                        
                                            echo "<p>". $priceone[1] ."</p>";
                                        } 
                                      
                                echo "</div>";
                                
                                echo "<div class='enrollment enrtop'>";
                                        if (!isset($blocks[0]['attrs']['nonmember']))
                                        {    
                                            echo "<h3>Non - Member Price</h3>";
                                        
                                            echo "<p>". $pricetwo[2] ."</p>";
                                        } 
                                      
                                echo "</div>";    

                                echo "<div class='enrollment enrtop'>";
                                        if (isset($blocks[0]['attrs']['purchaseprice']))
                                        {   
                                            if($json_value['purchase_amount'])
                                            {
                                                echo "<h3>Purchase Price</h3>";
                                                echo "<p>$". $json_value['purchase_amount'] ."</p>";
                                            } 
                                        } 
                                echo "</div>";
                                    
                                echo "</div>";
                            ?>
                            
                        </div>

                        <div class="event-detail-sec">
                            <div class="left-sec">
                                <?php
                                if($json_value['description'])
                                {
                                    echo "<h2 class='infotitle'>Information</h2>";
                                    echo "<div class='text-sec assetsdescription'>";
                                    echo "<p class='text-italic'>". $json_value['description'] ."</p>";
                                    echo "</div>";
                                    
                                }

                                if($json_value['external_url_resources'])
                                {
                                    if (!isset($blocks[0]['attrs']['externallink']))
                                    {
                                    echo "<div class='externaldiv'>";
                                    echo "<a class='external after' href='".$json_value['external_url_resources']."' target='_blank' style='color: $bgcolor;'>External Resources <span><i class='fa fa-external-link' aria-hidden='true'></i></span></a>";
                                    echo "</div>";
                                    }
                                }
                                
                                /*-- html tab --*/
                                   
                                echo "<div class='tabs effect-3 assetstab'>";
                                    /*-- tab-title --*/
                                    if (!isset($blocks[0]['attrs']['includedacc']))
                                    {
                                        if($json_value['included_accessories'])
                                        {
                                    echo "<input type='radio' id='tab-1' name='tab-effect-3'>";
                                        }
                                    }
                                    
                                    if (!isset($blocks[0]['attrs']['warrantyinfo']))
                                    {
                                        if($json_value['warranty_info'])
                                        {    
                                    echo "<input type='radio' id='tab-2' name='tab-effect-3'>";
                                        }
                                    }
                                     
                                    if (!isset($blocks[0]['attrs']['availabilitycalendar']))
                                    {    
                                        echo "<input type='radio' id='tab-3' name='tab-effect-3'>";
                                    } 

                                echo "<div class='labels'>";
                                    if (!isset($blocks[0]['attrs']['includedacc']))
                                    {
                                        if($json_value['included_accessories'])
                                        {    
                                    echo "<label for='tab-1' id='label-tab-1' class='label'><span style='color: $bgcolor;'>Included Accessories</span></label>";
                                        }
                                    }
                                       

                                    if (!isset($blocks[0]['attrs']['warrantyinfo']))
                                    {
                                        if($json_value['warranty_info'])
                                        {    
                                    echo "<label for='tab-2' id='label-tab-2' class='label'><span style='color: $bgcolor;'>Warranty Information</span></label>";
                                        }
                                    }    

                                    if (!isset($blocks[0]['attrs']['availabilitycalendar']))
                                    {
                                    echo "<label for='tab-3' id='label-tab-3' class='label'><span style='color: $bgcolor;'>Availability</span></label>";
                                    }    
                                echo "</div>";
                                
                                
                                    /*-- tab-content --*/

                                echo "<div class='tab-content'>";
                                if($json_value['included_accessories'])
                                    {
                                echo    "<section id='tab-item-1'>
                                            ".$json_value['included_accessories']."
                                        </section>";
                                    }

                                if($json_value['warranty_info'])
                                    {        
                                echo    "<section id='tab-item-2'>
                                            ".$json_value['warranty_info']."
                                        </section>";
                                    }   

                                if (!isset($blocks[0]['attrs']['availabilitycalendar']))
                                {    
                                echo    "<section id='tab-item-3'>
                                            <div id='calendar'></div>
                                        </section>";
                                }     

                                echo "</div>";
                                echo "</div>";
                                
                                /*-- html end tab --*/

                                ?>
                                
                            </div>
                            

                            <div class="right-sec">
                                
                                <div class="ragister-sec">
                                    <div class="reg-sec">
                                        <?php
                                        if (!isset($blocks[0]['attrs']['showhidebookurl']))
                                        {
                                            if($json_value['status_text'] == "Active")
                                            {
                                            echo "<a href='$landingurl' target='$targeturl' style='background-color: $bgcolor;'>Book This Item</a>";
                                            }    
                                            else
                                            {
                                            echo "<p><span class='label label-danger btn-common' disabled>Unavailable</span></p>";
                                            }
                                        }    
                                        ?>
                                        
                                    </div>

                                    <div class="reg-sec">
                                        <?php
                                        if (isset($blocks[0]['attrs']['showpurchaseurl']))
                                        {
                                            if(!empty($json_value['purchase_amount']))
                                            {
                                                echo "<a href='$purchaselandingurl' target='$targeturl' style='background-color: $bgcolor;'>Buy This Item</a>";
                                            }    
                                              
                                        }    
                                        ?>
                                    </div>
                                
                                </div>
                                
                                <?php
                                    if($json_value['barcode'])
                                    {
                                        echo "<div class='location-sec'>";
                                        echo "<h3>Barcode Number</h3>";
                                        echo "<p>".$json_value['barcode']."</p>";
                                        echo "</div>";
                                    }

                                    if($json_value['serial_number'])
                                    {
                                        echo "<div class='location-sec'>";
                                        echo "<h3>Serial Number</h3>";
                                        echo "<p>".$json_value['serial_number']."</p>";
                                        echo "</div>";
                                    }

                                    if($json_value['insurance_value'])
                                    {
                                        echo "<div class='location-sec'>";
                                        echo "<h3>Insurance Value</h3>";
                                        echo "<p>".$json_value['insurance_value']."</p>";
                                        echo "</div>";
                                    }
                                ?>        
                                
                           </div>
                            
                        </div>
                    </div>  
                </div> 
            <?php } ?>
            </div>
            <!-- End asstes details page -->

            
        </div>
        <!-- .entry-content -->


            </main><!-- #main -->
        </div><!-- #primary -->
      </div>  
     </div><!-- .container no-sidebar -->
    </div><!-- .site-content -->
</div>   
<script>
   
    jQuery(document).ready(function() {
    jQuery("#inifiniteLoaderUpdate").hide();

    var assettype = jQuery("#assettype").val();
    var isbulkproduct = jQuery("#bulkassetscalendar").val();

    console.log(isbulkproduct);

    if(assettype == 'Facility')
    {
        var calendardata = 'month,agendaWeek,agendaDay';
        var defaultview = 'agendaWeek';
    }
    else 
    {
        var calendardata = 'month';
        var defaultview = 'month';
    }



    if(isbulkproduct == 1)
    {     
        $('body').on('click', '.fc-next-button', function () {
                var nextmonth = $('#calendar').fullCalendar('getView').intervalStart.format('MM');
                var nextyear = $('#calendar').fullCalendar('getView').intervalEnd.format('YYYY');
                var getpageid = $('#getpageid').val();

                $.ajax({
                    url: amsjs_ajax_url.ajaxurl,
                    type:'post',
                    dataType: 'JSON',
                    data: { action: 'get_ajaxbulkassetscalendar', getpageid:getpageid,nextmonth:nextmonth,nextyear:nextyear},
                    success: function (datanew) {

                             console.log(datanew);      

                            $('#calendar').fullCalendar( 'destroy' );

                            jQuery('#calendar').fullCalendar({
                                editable:false,
                                header:{
                                 left:'prev,next today',
                                 center:'title',
                                 right:calendardata,
                                },
                                defaultDate: moment().format(nextyear+'-'+nextmonth+'-'+'01'),
                                events: datanew,
                                eventRender: function(event, eventElement) {
                                    if (event.title == "Not Available") {
                                      eventElement.addClass('notavailable');
                                    }
                                }
                            });
                    }
                });
        });

        $('body').on('click', '.fc-prev-button', function () {
            var nextmonth = $('#calendar').fullCalendar('getView').intervalStart.format('MM');
            var nextyear = $('#calendar').fullCalendar('getView').intervalEnd.format('YYYY');
            var getpageid = $('#getpageid').val();

            $.ajax({
                url: amsjs_ajax_url.ajaxurl,
                type:'post',
                dataType: 'JSON',
                data: { action: 'get_ajaxbulkassetscalendar', getpageid:getpageid,nextmonth:nextmonth,nextyear:nextyear},

                success: function (datanew) {

                        $('#calendar').fullCalendar( 'destroy' );

                        jQuery('#calendar').fullCalendar({
                            editable:false,
                            header:{
                             left:'prev,next today',
                             center:'title',
                             right:calendardata,
                            },
                            defaultDate: moment().format(nextyear+'-'+nextmonth+'-'+'01'),
                            events: datanew,
                            eventRender: function(event, eventElement) {
                                if (event.title == "Not Available") {
                                  eventElement.addClass('notavailable');
                                }
                            }
                        });  
                }
            });
        });
    }    

    $('.assetstab').each(function(){
      $(this).find('input[type=radio]:nth-child(1)').attr('checked', true);
    });

    function getCalendarData()
    {

        $('#calendar').fullCalendar( 'destroy' );
        jQuery('#calendar').fullCalendar({
            editable:false,
            header:{
             left:'prev,next today',
             center:'title',
             right:calendardata,
            },
            events: <?php include( plugin_dir_path( __FILE__ ) . '/inc/getassetscalendar.php') ?>,
            defaultView: defaultview,
            aspectRatio: 1.5,
            selectable:true,
            selectHelper:true,
            editable:true,
            eventRender: function(event, eventElement) {
                if (event.title == "Not Available") {
                  eventElement.addClass('notavailable');
                }
            }
        });
           
    }

    if ($('input#tab-3').is(':checked')) 
    {
        getCalendarData();
    }

    $("#tab-3").click(function () {
        if ($(this).is(":checked")) {
             getCalendarData();
        }
    });
    
    

  });
  
   
</script> 
<?php
get_footer();
