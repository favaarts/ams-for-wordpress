

function equipmentdetails(prodictkey)
{
	console.log(prodictkey);
	/*console.log(prodictkey);
	console.log("hello");
	return false;*/
	console.log(prodictkey);

	event.preventDefault();
	var prodictid =  prodictkey;
	
	jQuery('#inifiniteLoader').hide();

	jQuery(window).unbind('scroll');

	jQuery.ajax({
		url: amsjs_ajax_url.ajaxurl,
		type: 'post',
		data : {
			action : 'equipmentproductdetails_action',
			prodictid : prodictid
		},
		success: function(result)
		{
			/*console.log(result);
			return false;*/
			jQuery('.productdetail').html(result);
		}
	});

	
}

function categorydata(getcatid)
{
	event.preventDefault();
	var catid =  getcatid;

	document.getElementById('keyword').value = '';

	/*console.log(catid);*/


	jQuery.ajax({
		url: amsjs_ajax_url.ajaxurl,
		type: 'post',
		data : {
			action : 'getcategory_action',
			catid : catid
		},
		success: function(result)
		{
			/*console.log(result);
			return false;*/
			jQuery('.right-col-wrap').html(result);
		}
	});
}

function fetchequipment()
{
	event.preventDefault();
	var slugurl = jQuery('#slugurl').val();
	var catid = jQuery('#categoryid').val();

	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searchcategorydata_action', keyword: jQuery('#keyword').val(),slugurl:slugurl, catid:catid },
        success: function(data) {
        	//console.log(data);
        	
        	jQuery('.right-col-wrap').html(data);
        		
        }
    });
}


function fetchevent()
{
	event.preventDefault();
	/*var slugurl = jQuery('#slugurl').val();
	var catid = jQuery('#categoryid').val();*/
	var pageslug = jQuery('#inputpageslug').val();
	var pageid = jQuery('#inputpageid').val();
	
	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searcheventdata_action', getevent: jQuery('#getevent').val(), pageslug: pageslug, pageid: pageid},
        success: function(data) {
        	//console.log(data);
        	
        	jQuery('.right-col-wrap').html(data);
        	jQuery('#seemore').hide();
        }
    });
}


function fetchproject()
{
	var pageid = jQuery("#getpageid").val();
	var projectprotected = jQuery("#getproject").attr("data-protectedid");
	
	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searchprojectdata_action', projectdata: jQuery('#getproject').val(), pageid:pageid, projectprotected:projectprotected},
        success: function(data) {
        	//console.log(data);
        	
        	jQuery('.right-col-wrap').html(data);
        	jQuery('#seemore').hide();
        }
    });
}


function btnAMSLogout()
{
	var amsemailoruser = jQuery('#amsemailoruser').val();
    var amspassword = jQuery('#amspassword').val();   
    var getaccesstoken = jQuery('#getaccesstoken').val();

    
      jQuery.ajax({
         url: amsjs_ajax_url.ajaxurl,
         type:'POST',
         data: { action: 'get_amsmemberlogout', getaccesstoken:getaccesstoken},
         beforeSend: function(){
          	jQuery("#inifiniteLoader").show();
            jQuery("#btnAMSLogout").attr("disabled", true);
         },
         success: function (data) {
         	jQuery('#inifiniteLoader').hide();
            jQuery("#btnAMSLogout").attr("disabled", false);
            console.log(data);
            location.reload();
         }
      });
}

function encrypt (msg, pass) {
  var salt = CryptoJS.lib.WordArray.random(128/8);
  
  var key = CryptoJS.PBKDF2(pass, salt, {
      keySize: keySize/32,
      iterations: iterations
    });

  var iv = CryptoJS.lib.WordArray.random(128/8);
  
  var encrypted = CryptoJS.AES.encrypt(msg, key, { 
    iv: iv, 
    padding: CryptoJS.pad.Pkcs7,
    mode: CryptoJS.mode.CBC
    
  });
  
  // salt, iv will be hex 32 in length
  // append them to the ciphertext for use  in decryption
  var transitmessage = salt.toString()+ iv.toString() + encrypted.toString();
  return transitmessage;
}//end of function

function decrypt (transitmessage, pass) {
  var salt = CryptoJS.enc.Hex.parse(transitmessage.substr(0, 32));
  var iv = CryptoJS.enc.Hex.parse(transitmessage.substr(32, 32))
  var encrypted = transitmessage.substring(64);
  
  var key = CryptoJS.PBKDF2(pass, salt, {
      keySize: keySize/32,
      iterations: iterations
    });

  var decrypted = CryptoJS.AES.decrypt(encrypted, key, { 
    iv: iv, 
    padding: CryptoJS.pad.Pkcs7,
    mode: CryptoJS.mode.CBC
    
  })
  return decrypted;
}//end of function