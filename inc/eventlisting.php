<?php

function amseventlisting_function( $slug ) {
    ob_start();  

$post_id = get_the_ID();
$post = get_post($post_id);
$eventblocksetting = parse_blocks($post->post_content);

foreach($eventblocksetting as $blockdata) 
{
    if($blockdata['blockName'] == "wpdams-amsnetwork-event/amsnetwork-block-event")
    { 
        $gridlayout = $blockdata['attrs']['radio_attr_event'];
        $event_pagination = $blockdata['attrs']['event_pagination'];
        $eventsidebar = $blockdata['attrs']['eventsidebar'];
        $tagsevents = $blockdata['attrs']['tagsevents'];
        $organizationevents = $blockdata['attrs']['organizationevents'];
        $displaypastevents = $blockdata['attrs']['displaypastevents'];
        $earlybird = $blockdata['attrs']['earlybird'];
        $eventshowbutton = $blockdata['attrs']['eventshowbutton'];
        
    }
}




if($gridlayout == "calendar_view")
{ 
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css' />
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js'></script>";
}   
    ?>

<div id="category" class="category cat-wrap">

  
<div class="entry-content main-content-wrap">
 
<!-- ======================================================================
notes::
main-content - this class is for two columns.
main-content main-content-three-col - this class is for three columns.
main-content main-content-four-col - this class is for four columns.
======================================================================  -->

<?php



$bgcolor = get_option('wpams_button_colour_btn_label');
if(empty($bgcolor))
{
  $bgcolor = "#337AB7";
}

if($event_pagination != NULL)
{
  $pagination = $event_pagination;
}
else
{
  $pagination = 8;
}

if($gridlayout == "four_col")
{
   $blockclass = 'main-content-four-col';
   $eventperpage = $pagination;
}
else if($gridlayout == "two_col")
{
  $blockclass = '';
  $eventperpage = $pagination;
}
else if($gridlayout == "calendar_view")
{
  $blockclass = 'calender-view-main';
  $eventperpage = $pagination;
}
else
{
   $blockclass = 'main-content-three-col';
   $eventperpage = $pagination;
}

?>

<div class="wp-block-columns main-content <?= $blockclass; ?>" >
   
  <input type="hidden" name="slugurl" id="slugurl" value="<?=$post_slug?>">  
  <input type="hidden" id="gridlayoutview" value="<?=$gridlayout?>">

    <?php
    if (!isset($eventsidebar))
      {
    ?>
    <!-- Sidebar -->
    <div class="wp-block-column left-col" >
        <?php

        $locationArrayResult = get_eventlocation();
        
        if(!isset($locationArrayResult['error']))
        {
            
              
        ?>

            <div class="searchbox">
                <h4>Search</h4>
                <input type="text" class="searrch-input" name="getevent" id="getevent"></input>
            </div>

          <div class="othersearch">

            <div class="alltypeevent">
              <h4>Type</h4>
              <select class='ul-cat-wrap' id='alltypeevent'>
                <option value="All">All Programs</option>
                <option value="Events">Events</option>
                <option value="Workshops">Workshops</option>
                <option value="Classes">Classes</option>
              </select>
            </div>
            
            <div class="allstatus">
              <h4>Status</h4>
              <select class='ul-cat-wrap' id='allstatus'>
                <option value="1">Active</option>
                <option value="2">Cancelled</option>
                <option value="3">Finished</option>
              </select>
            </div>

            <div class="evtlocation">
              <h4>Location</h4>
              <select class='ul-cat-wrap' id='evtlocation'>
                <option value="">All Locations</option>
                <?php
                foreach($locationArrayResult['json']['locations'] as $c => $c_value) {
                  echo "<option  value='".$c_value."'>".$c_value."</option>";     
                }
              ?>
              </select>
            </div>

            <?php
            if (!isset($tagsevents))
            {
            ?>
            <div class="taglabels">
              <h4>Labels</h4>
              <select class='ul-cat-wrap' id='taglabels'>
                <option value="">Select Labels</option>
                <?php 
                $geteventtags = get_eventorganizationtags();
                foreach($geteventtags['organization_tags'] as $c_value) 
                {
                  echo "<option value='".$c_value['name']."'>".$c_value['name']."</option>";
                }  
                ?>
              </select>
            </div>
            <?php 
            } 

            if (isset($organizationevents))
            {
            ?>
            <div class="taglabels">
              <h4>Organizations</h4>
              <select class='ul-cat-wrap' id='organizations'>
                <option value="">Select Organizations</option>
                <?php 
                $geteventtags = get_organizationevents();
                foreach($geteventtags['organizations'] as $c_value) 
                {
                  echo "<option value='".$c_value['id']."'>".$c_value['full_name']."</option>";
                }  
                ?>
              </select>
            </div>
            <?php } ?>
            <div class="searchbutton">
              <!-- <input type="button" class="inputsearchbutton" id="searchdata" style="background-color: <?=$bgcolor?>" value="Search"> -->
              <img class="buttonloader" src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>" >
            </div>  

          </div>  
      <?php } ?>    
            
    </div>  
    <!-- End sidebar -->
    <?php } ?>
    
    <div class="categorysearchdata right-col eventpage" >
        <div class="productdetail"></div>
        <div class="right-col-wrap">
            
        

        <?php
          $arrayResult = get_eventlisting(NULL);
          

          global $post;
          $pageid = $post->ID;
          $pageslug = $post->post_name;

          if($gridlayout == "list_view")
          {
                foreach($arrayResult['programs'] as $x_value) 
                { 
                  if(isset($x_value['id']))
                  {

                   $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];  
                
                echo "<div class='listview-events'>";
                  echo "<div class='productstyle-list-items'>";
                     
                      
                        if (isset($organizationevents))
                        {
                          if(empty($x_value['organization_logo']))
                          {
                            echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                            echo "<div class='product-img-wrap'>";
                              echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                            echo "</div>";  
                            echo "</a>";
                          }
                          else 
                          {
                            echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                            echo "<div class='product-img'>";
                              echo "<img class='organizationlogo' src=".$x_value['organization_logo'] .">";
                            echo "</div>"; 
                            echo "</a>"; 
                          }
                        }
                        else
                        {
                          if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                          {
                            echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                            echo "<div class='product-img-wrap'>";
                              echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                            echo "</div>";  
                            echo "</a>"; 
                          } 
                          else
                          {
                            echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                            echo "<div class='product-img'>";
                              echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                            echo "</div>"; 
                            echo "</a>";  
                          }
                        }     
                      

                      echo "<div class='product-content'>";
                        echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                          
                          if (!isset($displaypastevents))
                          {
                            $date=$x_value['earliest_scheduled_program_date'];
                          }
                          else
                          {
                            $date=$x_value['upcoming_scheduled_program_date'];
                          }

                          if(empty($date))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'><strong>Start Day: </strong> </span>".date('D, M d Y', strtotime($date))."</P>"; 
                          }

                          if($x_value['location'])
                          {
                            echo "<p class='locationname'><strong>Location: </strong>".$x_value['location']."</p>";
                          }
                          
                          if (!isset($earlybird))
                          {
                            $earlybirddate=$x_value['earlybird_cutoff'];
                            if(empty($earlybirddate))
                            {
                              echo "<p>No Date Scheduled</P>";
                            }
                            else
                            {
                              echo "<p class='product-date'><span class='datetitle'><strong>Early Bird Registration Deadline: </strong> </span>".date('D, M d Y', strtotime($earlybirddate))."</P>"; 
                            }
                          }  

                          //drop_cutoff
                          $dropdate=$x_value['drop_cutoff'];
                          if(empty($dropdate))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'><strong>Final Registration Deadline: </strong> </span>".date('D, M d Y', strtotime($dropdate))."</P>"; 
                          }
                      echo "</div>";
                      
                        
                    echo "</div>";
                  echo "</div>";
                
                   } 
                } 

        
          }
          else if($gridlayout == "calendar_view")
          {
            echo  "<div class='eventbuttonloader'></div>";
            echo "<div id='calendar_div'>";
            echo "</div>";
          } 
          else
          { 
          
            foreach($arrayResult['programs'] as $x_value) { 

                if(isset($x_value['id']))
                {
                    
                    echo "<div class='productstyle eventlayout'>";
                    
                        if(isset($x_value['name']))
                        {
                            $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];

                            // Check if organization toogle is ON
                            if (isset($organizationevents))
                            {
                              if(empty($x_value['organization_logo']))
                              {
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                                echo "<div class='eventlayout-image'>";
                                  echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                                echo "</div>";
                                echo "</a>";
                              }
                              else 
                              {
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                                echo "<div class='product-img-wrap'>";
                                  echo "<img class='organizationlogo' src=".$x_value['organization_logo'] .">";
                                echo "</div>"; 
                                echo "</a>"; 
                              }
                            }
                            else
                            {
                              if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                              {
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                                echo "<div class='product-img-wrap'>";
                                  echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                                echo "</div>";
                                echo "</a>";   
                              } 
                              else
                              {
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'>";
                                echo "<div class='eventlayout-image'>";
                                  echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                                echo "</div>";  
                                echo "</a>";
                              }
                            }

                            

                            echo "<div class='eventtitle'>";
                              
                              if (!isset($displaypastevents))
                              {
                                $date=$x_value['earliest_scheduled_program_date'];
                              }
                              else
                              {
                                $date=$x_value['upcoming_scheduled_program_date'];
                              }

                                if(empty($date))
                                {
                                  echo "<p>No Date Scheduled</P>";
                                }
                                else
                                {
                                  echo "<p><span class='datetitle'><strong>Earliest Date: </strong> </span>".date('D, M d', strtotime($date))."</P>"; 
                                }
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                            echo "</div>";
                              
                            }
                        
                    echo "</div>";
                }
            }
                        
                        
          }    
        ?>
            
       </div>  
            <input type="hidden" id="inputpageslug" value="<?php echo $pageslug; ?>">
            <input type="hidden" id="inputpageid" value="<?php echo $pageid; ?>">
            <div class="eventbutton">
                <p class="para"></p>
                <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>
                <?php
                if (!isset($eventshowbutton))
                {
                echo "<input type='button' id='seemore' style='background-color: $bgcolor' value='View More'>";
                }
                ?>  
            </div> 
    </div> 
    
</div>
     
</div>



<script type="text/javascript">
jQuery(document).ready(function($) {

   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   var gridlayoutview = jQuery("#gridlayoutview").val();
   $('#inifiniteLoader').hide();
   console.log(total);

  if(gridlayoutview == 'calendar_view')
   {
      $('.eventbutton').hide();
      $(".eventbuttonloader").hide();

      jQuery('#calendar_div').fullCalendar({
        editable:false,
        header:{
         left:'prev,next today',
         center:'title',
         right:'month,agendaWeek,agendaDay'
        },
        defaultDate: moment().format("YYYY-MM-DD"),
        events: <?php include( plugin_dir_path( __FILE__ ) . 'geteventcalendar.php'); ?>,
        selectable:true,
        selectHelper:true,
        editable:true,
        eventOverlap: function(stillEvent, movingEvent) {
            return stillEvent.allDay && movingEvent.allDay;
          }
        ,
        eventClick: function(event) {
          if (event.url) {
              window.open(event.url, "_blank");
              return false;
          }  
        },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;height:auto;background:#ccc;position:absolute;z-index:10001;border-radius: 2px;padding: 5px;">'+ calEvent.title+'</div>';
            var $tooltip = $(tooltip).appendTo('body');

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $tooltip.fadeIn('500');
                $tooltip.fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $tooltip.css('top', e.pageY + 10);
                $tooltip.css('left', e.pageX + 20);
            });
        },

        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.tooltipevent').remove();
        }, 
      });

      $("#alltypeevent").change(function() {
        
        event.preventDefault();
        
        var eventtype = $(this).val();
        var eventstatus = jQuery('#allstatus').val();
        var evtlocation = jQuery('#evtlocation').val();
        var pageslug = jQuery('#inputpageslug').val();
        var pageid = jQuery('#inputpageid').val();

          jQuery.ajax({
              url: amsjs_ajax_url.ajaxurl,
              type: 'post',
              data: { action: 'get_eventlocation', eventtype: eventtype, eventstatus: eventstatus},
              success: function(data) {
                jQuery('#evtlocation').html(data);
              }
          });

          jQuery.ajax({
              url: amsjs_ajax_url.ajaxurl,
              type: 'post',
              dataType: 'JSON',
              data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid},
              beforeSend: function() {
                $(".eventbuttonloader").show();
                $("#calendar_div").css("opacity", "0.4");
                $(".eventpage .right-col-wrap").css("position", "relative");

              },
              success: function(data) {
                $(".eventbuttonloader").hide();
                $("#calendar_div").css("opacity", "1");
                $(".eventpage .right-col-wrap").css("position", "unset");
                getEventCalendarData(data)
              }
          });

      });


      $("#allstatus").change(function() {
          
          var eventtype = jQuery('#alltypeevent').val();
          var eventstatus = $(this).val();
          var evtlocation = jQuery('#evtlocation').val();
          var pageslug = jQuery('#inputpageslug').val();
          var pageid = jQuery('#inputpageid').val();

          jQuery.ajax({
              url: amsjs_ajax_url.ajaxurl,
              type: 'post',
              data: { action: 'get_eventlocation', eventtype: eventtype, eventstatus: eventstatus},
              success: function(data) {
                jQuery('#evtlocation').html(data);
              }
          });

          jQuery.ajax({
              url: amsjs_ajax_url.ajaxurl,
              type: 'post',
              dataType: 'JSON',
              data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid},
              beforeSend: function(){
                $(".eventbuttonloader").show();
                $("#calendar_div").css("opacity", "0.4");
                $(".eventpage .right-col-wrap").css("position", "relative");
              },
              success: function(data) {
                $(".eventbuttonloader").hide();
                $("#calendar_div").css("opacity", "1");
                $(".eventpage .right-col-wrap").css("position", "unset");
                getEventCalendarData(data)
              }
          });

      });

       $("#evtlocation").change(function() {
        
        var eventtype = jQuery('#alltypeevent').val();
        var eventstatus = jQuery('#allstatus').val();
        var evtlocation = $(this).val();
        var pageslug = jQuery('#inputpageslug').val();
        var pageid = jQuery('#inputpageid').val();

        jQuery.ajax({
              url: amsjs_ajax_url.ajaxurl,
              type: 'post',
              dataType: 'JSON',
              data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid},
              beforeSend: function(){
                $(".eventbuttonloader").show();
                $("#calendar_div").css("opacity", "0.4");
                $(".eventpage .right-col-wrap").css("position", "relative");
              },
              success: function(data) {
                $(".eventbuttonloader").hide();
                $("#calendar_div").css("opacity", "1");
                $(".eventpage .right-col-wrap").css("position", "unset");
                getEventCalendarData(data)
              }
          });

      });

      $("#taglabels").change(function() {
    
        var eventtype = jQuery('#alltypeevent').val();
        var eventstatus = jQuery('#allstatus').val();
        var evtlocation = jQuery("#evtlocation").val();
        var taglabels = jQuery(this).val();
        var pageslug = jQuery('#inputpageslug').val();
        var pageid = jQuery('#inputpageid').val();

        jQuery.ajax({
              url: amsjs_ajax_url.ajaxurl,
              type: 'post',
              dataType: 'JSON',
              data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,taglabels: taglabels},
              beforeSend: function(){
                $(".eventbuttonloader").show();
                $("#calendar_div").css("opacity", "0.4");
                $(".eventpage .right-col-wrap").css("position", "relative");
              },
              success: function(data) {
                $(".eventbuttonloader").hide();
                $("#calendar_div").css("opacity", "1");
                $(".eventpage .right-col-wrap").css("position", "unset");
                getEventCalendarData(data)
              }
          });

      }); 

      function getEventCalendarData(eventsdata)
      {
          $('#calendar_div').fullCalendar( 'destroy' );

          jQuery('#calendar_div').fullCalendar({
              editable:false,
              header:{
               left:'prev,next today',
               center:'title',
               right:'month,agendaWeek,agendaDay',
              },
              defaultDate: moment().format("YYYY-MM-DD"),
              events: eventsdata,
              selectable:true,
              selectHelper:true,
              editable:true,
              eventOverlap: function(stillEvent, movingEvent) {
                  return stillEvent.allDay && movingEvent.allDay;
                }
              ,
              eventClick: function(event) {
                if (event.url) {
                    window.open(event.url, "_blank");
                    return false;
                }  
              },
              eventMouseover: function(calEvent, jsEvent) {
                  var tooltip = '<div class="tooltipevent" style="width:auto;height:auto;background:#ccc;position:absolute;z-index:10001;border-radius: 2px;padding: 5px;">'+ calEvent.title+'</div>';
                  var $tooltip = $(tooltip).appendTo('body');

                  $(this).mouseover(function(e) {
                      $(this).css('z-index', 10000);
                      $tooltip.fadeIn('500');
                      $tooltip.fadeTo('10', 1.9);
                  }).mousemove(function(e) {
                      $tooltip.css('top', e.pageY + 10);
                      $tooltip.css('left', e.pageX + 20);
                  });
              },

              eventMouseout: function(calEvent, jsEvent) {
                  $(this).css('z-index', 8);
                  $('.tooltipevent').remove();
              },  
          });
      }

 }
 else
 { 


    $("#alltypeevent").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = $(this).val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'get_eventlocation', eventtype: eventtype, eventstatus: eventstatus},
            success: function(data) {
              jQuery('#evtlocation').html(data);
            }
        });


        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg},
            beforeSend: function(){
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });

    });

    $("#allstatus").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = $(this).val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();


      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'get_eventlocation', eventtype: eventtype, eventstatus: eventstatus},
            success: function(data) {
              jQuery('#evtlocation').html(data);
            }
        });


        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg},
            beforeSend: function(){
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });

    });

    $("#evtlocation").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = $(this).val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

      jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg},
            beforeSend: function(){
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });

    });

    $("#taglabels").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery("#evtlocation").val();
      var taglabels = jQuery(this).val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg,taglabels: taglabels},
            beforeSend: function(){
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });

    });


    $("#organizations").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery("#evtlocation").val();
      var taglabels = jQuery("#taglabels").val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();
      var organizations = jQuery('#organizations').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg,taglabels: taglabels, organizations: organizations},
            beforeSend: function(){
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });

    });
    


    $("#getevent").keyup(function(){

      count = 2;     
      event.preventDefault();

      var getevent = jQuery('#getevent').val();
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery("#evtlocation").val();
      var taglabels = jQuery("#taglabels").val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();
      var organizations = jQuery('#organizations').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

      jQuery.ajax({
          url: amsjs_ajax_url.ajaxurl,
          type: 'post',
          data: { action: 'searcheventdata_action', getevent: getevent, organizations: organizations, eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid, eventperpg: eventperpg},
          success: function(data) {
            jQuery('.right-col-wrap').html(data);
            //jQuery('#seemore').hide();
            AjaxInitProgram();
          }
      });

    });

    $('#searchdata').click(function(){
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();


      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg},
            beforeSend: function(){
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });
    });
    

   

    function AjaxInitProgram() {     
      var totalprogram = jQuery("#totalprogram").val();
      total = totalprogram;
      console.log(total);
      if(total > 0)
      {
        jQuery('#seemore').show();
        jQuery(".para").hide();
      }
      else
      {
        jQuery('#seemore').hide();
      }
    }  
    
    $('#seemore').click(function(){
        var numItems = jQuery('.productstyle').length;
        var listnumItems = jQuery('.listview-events').length;   
        
        var totalItems = "";
        if(numItems != '')  
        {
          totalItems = numItems;
        }
        else
        {
          totalItems = listnumItems;
        }

        console.log(totalItems);
        console.log(total);


        if (totalItems >= total){
            jQuery('#seemore').hide();
            jQuery(".para").text("No More Events found.");  
        }else{
            jQuery('#seemore').hide();   
            loadArticle(count);
        }
        count++;
        
    });


    function loadArticle(pageNumber){
     var eventperpg = <?php echo $eventperpage; ?>;
     var slugvar = $('#inputpageslug').val();
     var slugvarid = $('#inputpageid').val();

      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
      var taglabels = jQuery("#taglabels").val();
      var organizations = jQuery('#organizations').val();

      $.ajax({
         url: amsjs_ajax_url.ajaxurl,
         type:'POST',
         data: { action: 'geteventonclick_action', page:pageNumber, eventperpg:eventperpg, eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslugname: slugvar, pageslugid: slugvarid, taglabels: taglabels, organizations: organizations},
         beforeSend: function(){
              $("#inifiniteLoader").show();
         },
         success: function (html) {
           jQuery('#inifiniteLoader').hide('1000');
           jQuery('.right-col-wrap').append(html);
           jQuery('#seemore').show();
         }
      });
      return false;
    }

  }  
   
});    
</script>

  
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amseventlisting', 'amseventlisting_function');

?>
