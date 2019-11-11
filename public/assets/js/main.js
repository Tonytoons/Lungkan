var btn_loader = '';
var txt_btn = '';
var error_alert = 'Sorry, we can\'t process this time please try again later!';
var base_url = basePath+''+lang+'/'; 
var submit = true;
var fbID = 0;
var connected = 0;
var album_del = [];
var albums = {};


window.fbAsyncInit = function() {
  FB.init({
      appId            : '697267507357441', 
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v3.2'
  });    
  //FB.AppEvents.logPageView(); 
};  

(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
 
/*--Facebook--*/
if (typeof(FB) != 'undefined' && FB != null )
{ 
	FB.getLoginStatus(function(response) {
	 	///console.log(response.status);
	    if (response.status === 'connected') {
	       connected = 1; 
	    }
	});  
} 

function getUserData(callback)
{  
	FB.api('/me', {fields: 'id,name,email'}, function(response)
	{ 
		fbID = response.id;  
		if(!response.email){   
			response.email = response.id+'@facebook.com'; 
		}        
		callback(response); 
	}); 
} 

function connectFB(callback)
{
	if(connected==0){
		FB.login(function(response) {  
			if (response.authResponse) { 
				getUserData(function(rs){
					callback(rs); 
				}); 
			}else{   
				setTimeout(function(){     
					$(btn_loader).buttonLoader('stop');	
			 	},1000);     
			}   
		}, {scope: 'email,public_profile', return_scopes: true});
	}else{
		getUserData(function(rs){
			callback(rs); 
		});  
	} 
} 



$('.btn-loader').click(function() {
	btn_loader = $(this); 
	$(btn_loader).buttonLoader('start');
});

$.fn.buttonLoader = function(action) {
	var self = $(this); 
	$(self).attr("disabled", false);
	if (action == 'start') { 
		if ($(self).attr("disabled") == "disabled") {
			return false;
		}   
		$(self).attr("disabled", true);
		txt_btn = $(self).html();
		var text = 'Loading...';
		if ($(self).attr('data-load-text') != undefined && $(self).attr('data-load-text') != "") {
			var text = $(self).attr('data-load-text');
		}
		$(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin" title="button-loader"></i></span> ' + text);
		$(self).addClass('active');
	}
	if (action == 'stop') { 
		$(self).html(txt_btn);
		$(self).removeClass('active');
		$(self).attr("disabled", false);
	} 
}


function popupModal(name){ 
    $('#register-modal, #login-modal').modal('hide'); 
    setTimeout(function(){
        if(name=='register'){ 
            $('#register-modal').modal('show');     
        }else{ 
            $('#login-modal').modal('show'); 
        }     
    },500); 
}  


$(window).on('load', function() {
    // initialization of HSMegaMenu component
    $('.js-mega-menu').HSMegaMenu({
        event: 'hover',
        pageContainer: $('.container'),
        breakpoint: 767.98,
        hideTimeOut: 0
    });

    // initialization of svg injector module
    $.HSCore.components.HSSVGIngector.init('.js-svg-injector');
});

$(document).on('ready', function() {
    // initialization of header
    $.HSCore.components.HSHeader.init($('#header'));
    
    // initialization of unfold component
    $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
        afterOpen: function() {
            $(this).find('input[type="search"]').focus();
        }
    });
   

    // initialization of malihu scrollbar
    $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

    // initialization of forms
    $.HSCore.components.HSFocusState.init();

   
    // initialization of show animations
    $.HSCore.components.HSShowAnimation.init('.js-animation-link');

    // initialization of fancybox
    $.HSCore.components.HSFancyBox.init('.js-fancybox');

    // initialization of forms
    $.HSCore.components.HSRangeSlider.init('.js-range-slider');

    // initialization of slick carousel
    $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

    // initialization of go to
    $.HSCore.components.HSGoTo.init('.js-go-to');
    
}); 

window.alert = function(title) { 
	swal({
		title : title,
		text : "",
		type : 'warning', 
		timer: 5000,  
	    showCancelButton: false,
	    showConfirmButton: false
	}).then( 
	  function () {}, 
	  // handling the promise rejection
	  function (dismiss) {
	    if (dismiss === 'timer') {
	      $(btn_loader).buttonLoader('stop'); 
	    }
	  }
	);    
	setTimeout(function() {
		$(btn_loader).buttonLoader('stop');
	}, 100); 
	return false;
}; 
 
function alert_error(title) {
	swal({
		title : title,
		text : "",
		type : 'error',
		timer: 5000, 
	    showCancelButton: false,
	    showConfirmButton: false
	}).then(
	  function () {},
	  // handling the promise rejection
	  function (dismiss) {
	    if (dismiss === 'timer') {
	      $(btn_loader).buttonLoader('stop'); 
	    }
	  }
	);  
	setTimeout(function() {
		$(btn_loader).buttonLoader('stop');
	}, 1000); 
	return false;
}  

function alert_success(title) {  
	swal({
		title : title,
		text : "",
		type : 'success',
		timer: 5000, 
	    showCancelButton: false,
	    showConfirmButton: false
	}).then( 
	  function () {}, 
	  // handling the promise rejection
	  function (dismiss) {
	    if (dismiss === 'timer') {
	      $(btn_loader).buttonLoader('stop'); 
	    }
	  }
	); 
	setTimeout(function() {
	   $(btn_loader).buttonLoader('stop');
	}, 1000); 
	return false;
} 

function makeid()
{ 
    var text = ""; 
    var possible = "0123456789";
    for( var i=0; i < 6; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;  
} 


$("form[name='registerform']").validate({ 
      
  rules: {
    name : "required",
    email: {
      required: true,
      email: true
    }, 
    password: {
        required: true,
        minlength : 6
    },  
    confirmPassword : {  
        required: true,
        minlength : 6,
        equalTo : "#signinSrPassword"
    }
  },     
  messages: {     
    name: $('#signinSrName').data('msg'), 
    email: $('#signinSrEmail').data('msg'), 
    password: $('#signinSrPassword').data('msg'),
    confirmPassword: $('#signinSrConfirmPassword').data('msg'), 
    //termsCheckbox: $('#termsCheckbox').data('msg')  
  },    
  submitHandler: function(form) {
    btn_loader = $('.btn-register'); 
    $('.btn-register').buttonLoader('start');  
    $.ajax({ 
    url: base_url+'register/?act=register&rd='+makeid(), 
    type: "POST",  
    dataType: "json", 
    data: $(form).serialize(),    
    cache: false,              
    processData: false,      
    success: function(data) { 
            
      setTimeout(function(){    
				$(btn_loader).buttonLoader('stop');	
		 	},500);
      		 	
			if(data.status==200){  
			    
				alert_success(data.items);        
				setTimeout(function(){       
					if(action=='create'){  
					  
					    $('#login-modal').modal("hide");
					    $("#user_id").val(data.id);
					    validatePot();  
					    //createPot();  
					    //$("btn-createpot").click();
					    //window.location.reload(); 
				    }else{  
				       window.location.reload(); 
				      //window.location = base_url+'?rd='+makeid(); 
				    }  
			 	},500);        
			 	
			}else{       
			    
				alert_error(data.items);
				setTimeout(function(){   
    				$(btn_loader).buttonLoader('stop');	
    		 	},100);     
			} 
			
        },
        error: function(e) {
            console.log(e);
            setTimeout(function(){   
				$(btn_loader).buttonLoader('stop');	
		 	},100);    
		    alert_error(error_alert);  
        } 
    });   
    return false;   
    //console.log(form); 
  }, 
  highlight: function(element, errorClass, validClass) {
    $(element).removeClass('success-class').addClass('error-class');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass('error-class').addClass('success-class');
  }
}); 


$("form[name='registerlineform']").validate({ 
      
  rules: {
    name : "required",
    email: {
      required: true,
      email: true
    }
  },     
  messages: {     
    name: $('#line_signinSrName').data('msg'), 
    email: $('#line_signinSrEmail').data('msg')
  },    
  submitHandler: function(form) {
    btn_loader = $('.btn-register'); 
    $('.btn-register').buttonLoader('start');  
    $.ajax({ 
    url: base_url+'loginline/?act=register&rd='+makeid(), 
    type: "POST",   
    dataType: "json", 
    data: $(form).serialize(),    
    cache: false,              
    processData: false,      
    success: function(data) { 
            
      setTimeout(function(){    
				$(btn_loader).buttonLoader('stop');	
		 	},500);
      		 	
			if(data.status==200){  
			    
				alert_success(data.items);        
				setTimeout(function(){       
					if(action=='create'){
					  
					    $('#login-modal').modal("hide");
					    $("#user_id").val(data.id);
					    validatePot();  
					    //createPot();  
					    //$("btn-createpot").click();
					    //window.location.reload(); 
				    }else{  
				       window.location.reload(); 
				      //window.location = base_url+'?rd='+makeid(); 
				    }  
			 	},500);        
			 	
			}else{       
			    
				alert_error(data.items);
				setTimeout(function(){   
    				$(btn_loader).buttonLoader('stop');	
    		 	},100);     
			} 
			
        },
        error: function(e) {
            console.log(e);
            setTimeout(function(){   
				$(btn_loader).buttonLoader('stop');	
		 	},100);    
		    alert_error(error_alert);  
        } 
    });   
    return false;   
    //console.log(form); 
  }, 
  highlight: function(element, errorClass, validClass) {
    $(element).removeClass('success-class').addClass('error-class');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass('error-class').addClass('success-class');
  }
}); 

$('#termsCheckbox').change(function(){
    if($('#termsCheckbox').is(':checked')){ 
        $('#ms-terms').hide(); 
    }else{   
        $('#ms-terms').show(); 
    }  
});  

$("form[name='loginform']").validate({ 
      
  rules: {
    email: {
      required: true,
      email: true
    },
    password: {
        required: true,
        minlength : 6
    }
  },     
  messages: {     
    email: $('#signinSrEmail').data('msg'), 
    password: $('#signinSrPassword').data('msg'),
  },    
  submitHandler: function(form) {
    btn_loader = $('.btn-login'); 
    $('.btn-login').buttonLoader('start');  
    $.ajax({ 
        url: base_url+'login/?act=login&rd='+makeid(), 
        type: "POST",  
        dataType: "json", 
        data: $(form).serialize(),    
        cache: false,              
        processData: false,      
        success: function(data) { 
            
          setTimeout(function(){    
    				$(btn_loader).buttonLoader('stop');	
    		 	},500);
    		 	
    			if(data.status==200){  
    			    
    				//alert_success(data.items);         
    				setTimeout(function(){ 
    				    if(action=='create'){ 
    					    //window.location.reload();  
    					    //$("btn-createpot").click(); 
    					    $('#login-modal').modal("hide"); 
    					    $("#user_id").val(data.id); 
    					    validatePot();    
    					    //createPot();    
    				    }else{  
    				       window.location.reload(); 
    				       //window.location = base_url+'?rd='+makeid(); 
    				    }
    			 	},500);        
    			 	
    			}else{       
    			    
    				alert_error(data.items);
    				setTimeout(function(){   
        				$(btn_loader).buttonLoader('stop');	
        		 	},100);    
    			} 
			
        },
        error: function(e) {
          console.log(e);
          setTimeout(function(){   
      				$(btn_loader).buttonLoader('stop');	
      		},100);    
		      alert_error(error_alert);  
        } 
    });   
    return false;   
    //console.log(form); 
  }, 
  highlight: function(element, errorClass, validClass) {
    $(element).removeClass('success-class').addClass('error-class');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass('error-class').addClass('success-class');
  }
}); 

$("form[name='home-createpot']").validate({
  rules: { 
    potname: "required",
  }, 
  messages: {        
    potname: $('#potname').data('msg')
  },
  submitHandler: function(form) {
    form.submit(); 
  }, 
  highlight: function(element, errorClass, validClass) {
    $(element).removeClass('success-class').addClass('error-class');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass('error-class').addClass('success-class');
  }
}); 

$("form[name='forgotpassform']").validate({ 
  rules: {  
     email: {
      required: true,
      email: true
    },
  },   
  messages: {       
    email: $('#signinSrEmail').data('msg')
  },   
  submitHandler: function(form) {
    btn_loader = $('.btn-forgotpass');  
    $('.btn-forgotpass').buttonLoader('start'); 
    $.ajax({  
        url: base_url+'forgotpass/?act=forgotpass&rd='+makeid(), 
        type: "POST",   
        dataType: "json",  
        data: $(form).serialize(),    
        cache: false,              
        processData: false,      
        success: function(data) { 
            
            setTimeout(function(){    
				$(btn_loader).buttonLoader('stop');	
		 	},500);
		 	
			if(data.status==200){  
			    
				alert_success(data.items);         
				setTimeout(function(){     
				    if(action=='create'){  
				        window.location = base_url+'login/?rd='+makeid()+'&potname='+getParameterByName('potname');
				    }else{ 
				        window.location = base_url+'login/?rd='+makeid();   
				    } 
			 	},500);        
			 	
			}else{       
			    
				alert_error(data.items);
				setTimeout(function(){   
    				$(btn_loader).buttonLoader('stop');	
    		 	},100);    
			} 
			
        },  
        error: function(e) {
            console.log(e);
            setTimeout(function(){   
				$(btn_loader).buttonLoader('stop');	
		 	},100);    
		    alert_error(error_alert);  
        } 
    });   
    return false;  
    
  }, 
  highlight: function(element, errorClass, validClass) {
    $(element).removeClass('success-class').addClass('error-class');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass('error-class').addClass('success-class');
  }
}); 

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}


$("form[name='newpassform']").validate({ 
  rules: {  
    password: {
        required: true,
        minlength : 6
    },
  },   
  messages: {       
    password: $('#signinSrPassword').data('msg')
  },   
  submitHandler: function(form) {
    btn_loader = $('.btn-newpass');  
    $('.btn-newpass').buttonLoader('start');  
    $.ajax({  
        url: base_url+'forgotpass/?act=newpass&rd='+makeid(), 
        type: "POST",   
        dataType: "json",  
        data: $(form).serialize(),    
        cache: false,              
        processData: false,      
        success: function(data) { 
            
            setTimeout(function(){    
				$(btn_loader).buttonLoader('stop');	
		 	},500);
		 	
			if(data.status==200){  
			    
				alert_success(data.items);         
				setTimeout(function(){     
				    if(action=='create'){   
				        window.location = base_url+'login/?rd='+makeid()+'&potname='+getParameterByName('potname');
				    }else{ 
				        window.location = base_url+'login/?rd='+makeid();   
				    } 
			 	},500);        
			 	
			}else{       
			    
				alert_error(data.items);
				setTimeout(function(){   
    				$(btn_loader).buttonLoader('stop');	
    		 	},100);    
			} 
			
        },  
        error: function(e) {
            console.log(e);
            setTimeout(function(){   
				$(btn_loader).buttonLoader('stop');	
		 	},100);    
		    alert_error(error_alert);  
        } 
    });   
    return false;  
    
  }, 
  highlight: function(element, errorClass, validClass) {
    $(element).removeClass('success-class').addClass('error-class');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass('error-class').addClass('success-class');
  }
}); 


function switchLang(lang){
  
	var url=window.location.href;   
	 
	//setCookie('ck_lang',lang, 365);
	
	if(lang=='th'){   
		
		url = url.replace("/en/", "/th/"); 
		
	}else{      
		url = url.replace("/th/", "/en/"); 
	 
	} 
	 
	var n = url.search(lang);
	
	if(n<1){   
		 url += lang+'/'; 
	} 
	
	if(action=='index'){  
		 url = '/'+lang+'/?rd='+makeid(); 
		 //console.log(url);    
	}   
	console.log(url);   
	window.location = url; 
}


function readURL_Callback(input, callback) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			callback(reader.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function readURL(input) {
	$('#cover-preview').hide();
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('#cover-preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
		$('#cover-preview').show();
	}   
}

function createPot()
{ 
  var user_id = $("#user_id").val(); 
  if(!user_id){
    $('#login-modal').modal('show');
  }else if(submit){ 
    
    $('#register-modal, #login-modal').modal('hide'); 
    
    btn_loader = $('.btn-createpot');  
    $('.btn-createpot').buttonLoader('start'); 
    
    var potname = $("#potname").val();
    var organizer = $("#organizer").val();
    var eventdate = $("#eventdate").val();
    var category = $("#category").val();
    var subcategory = $("#subcategory").val(); 
    var description = $('.js-summernote-editor').summernote('code');
    
    var formData = new FormData(document.getElementsByName('cratepotform')[0]);
    formData.append('description', description);
    
    var thumb = $('#cover-preview').rcrop('getDataURL', 380, 200); 
    formData.append('thumb', thumb);    
        
    var image = $('#cover-preview').rcrop('getDataURL', 1200, 630); 
    formData.append('image', image);  
    //var album = Object.entries(albums); 
    //var album = Object.entries(albums).map(([k, v]) => ([v]));
    Object.keys(albums).map(function(key) { 
      formData.append('albums[]', albums[key]);  
      return [albums[key]];    
    });   
 
    $.ajax({  
        url: base_url+'create/?act=create&rd='+makeid(),  
        type: "POST",
    		contentType: false,
    		cache: false, 
    		processData: false,
    		dataType: 'json',
    		data: formData,      
        success: function(data) { 
            
            setTimeout(function(){    
      				$(btn_loader).buttonLoader('stop');	
      		 	},200);
      		 	  
      			if(data.status==200){ 
      			  
      				//alert_success(data.items);          
      				window.location = base_url+'view/'+data.id+'-'+data.slug; 
      				
      			}else{     
      				alert_error(data.items);
      				setTimeout(function(){   
          				$(btn_loader).buttonLoader('stop');	
          		 	},100);    
      			}  
			
        },  
        error: function(e) {
            console.log(e);
            setTimeout(function(){   
      				$(btn_loader).buttonLoader('stop');	
      		 	},100);    
    		    alert_error(error_alert);  
        } 
    });  
  }else{
    alert(error_alert); 
  }
}

function logOut()
{ 
  var txt = 'Are you sure to log out.';
  if(lang=='th'){
    txt = 'คุณแน่ใจว่าจะออกจากระบบ';
  }
	swal({ 
      title: txt,
      type: "warning", 
      showCancelButton: true,
      //confirmButtonColor: '#5d2a73',  
      confirmButtonText: "Yes",
      cancelButtonText: "No", 
  }).then(function() {
    var url = base_url+'logout/?r='+makeid();
    console.log(url);  
    setTimeout(function() {
		window.location = url; 
	}, 500); 
  },function(dismiss) {
      if(dismiss == 'cancel') {
          
      }
  });
}

function deleteMypot(){ 
  swal({ 
      title: "Are you sure to delete my pot.",
      type: "warning", 
      showCancelButton: true,
      //confirmButtonColor: '#5d2a73',  
      confirmButtonText: "Yes",
      cancelButtonText: "No", 
  }).then(function() {  
    var url = base_url+'managepot/'+id+'/?act=delete&rd='+makeid();
    $.get(url,function(rs){
      //console.log(rs);
      if(rs.status==200){
        window.location = base_url+'mypot/?r='+makeid();
      }else{ 
        alert_error(error_alert); 
      }
    },'json');
  },function(dismiss) {
      if(dismiss == 'cancel') {
          
      }
  });
}

function closeMypot()
{
  swal({ 
      title: "Are you sure to close my pot.",
      type: "warning", 
      showCancelButton: true,
      //confirmButtonColor: '#5d2a73',  
      confirmButtonText: "Yes",
      cancelButtonText: "No", 
  }).then(function() { 
    var url = base_url+'managepot/'+id+'/?act=close&rd='+makeid();
    $.get(url,function(rs){ 
      //console.log(rs); 
      if(rs.status==200){ 
        window.location.reload(); 
      }else{
        alert_error(error_alert); 
      } 
    },'json');
  },function(dismiss) {
      if(dismiss == 'cancel') {
          
      }
  });
}


function editPot() 
{
  var user_id = $("#user_id").val(); 
  if(!user_id){
    $('#login-modal').modal('show');
  }else if(submit){ 
    
    $('#register-modal, #login-modal').modal('hide'); 
    
    btn_loader = $('.btn-editpot');   
    $('.btn-editpot').buttonLoader('start'); 
    
    var potname = $("#potname").val();
    var organizer = $("#organizer").val();
    var eventdate = $("#eventdate").val();
    var category = $("#category").val();
    var subcategory = $("#subcategory").val(); 
    var description = $('.js-summernote-editor').summernote('code');
    
    var formData = new FormData(document.getElementsByName('editpotform')[0]); 
    formData.append('description', description); 
    
    var img = $("#img-cover").val();
    if(img){  
      var thumb = $('#cover-preview').rcrop('getDataURL', 380, 200); 
      formData.append('thumb', thumb);     
           
      var image = $('#cover-preview').rcrop('getDataURL', 1200, 630); 
      formData.append('image', image);   
    }  
    
    Object.keys(albums).map(function(key) { 
      formData.append('albums[]', albums[key]);  
      return [albums[key]];    
    });  
     
    $.each(album_del, function(i, item) {
      formData.append('img_del[]', item);  
    });  
    
     
    var id = $("#id").val(); 
    $.ajax({    
        url: base_url+'edit/'+id+'/?act=edit&rd='+makeid(),  
        type: "POST",
    		contentType: false,
    		cache: false, 
    		processData: false,
    		dataType: 'json',
    		data: formData,     
        success: function(data) { 
            
            setTimeout(function(){    
      				$(btn_loader).buttonLoader('stop');	
      		 	},200);
      		 	  
      			if(data.status==200){   
      				//alert_success(data.items);          
      				setTimeout(function(){       
      				  window.location.reload();     
      		 	  },100);    
      		 	  
      			}else{    
      				alert_error(data.items);
      				setTimeout(function(){   
          				$(btn_loader).buttonLoader('stop');	
          		 	},100);    
      			}  
			
        },  
        error: function(e) {
            console.log(e);
            setTimeout(function(){   
				$(btn_loader).buttonLoader('stop');	
		 	},100);    
		    alert_error(error_alert);  
        } 
    });  
  }else{
    alert(error_alert); 
  }
}

function settingPot()
{ 
  btn_loader = $('.btn-settingpot');   
  $('.btn-settingpot').buttonLoader('start'); 
  /*
  var opne_invite = $("#opne_invite").is(":checked");
  if(opne_invite){
    opne_invite = 1;
  }else{
    opne_invite = 0;
  }
  var hide_participarts_list = $("#hide_participarts_list").is(":checked");
  if(hide_participarts_list){
    hide_participarts_list = 1;
  }else{
    hide_participarts_list = 0;
  }*/ 
  var formData = new FormData(document.getElementsByName('settingpotform')[0]); 
  //formData.append('opne_invite', opne_invite);  
  //formData.append('hide_participarts_list', hide_participarts_list); 
  var id = $("#id").val(); 
  $.ajax({      
      url: base_url+'setting/'+id+'/?act=setting&rd='+makeid(),  
      type: "POST",
  		contentType: false,
  		cache: false, 
  		processData: false,
  		dataType: 'json',
  		data: formData,     
      success: function(data) { 
          
          setTimeout(function(){    
    				$(btn_loader).buttonLoader('stop');	
    		 	},200);
    		 	 
    			if(data.status==200){
    				alert_success(data.items);          
    				setTimeout(function(){       
    				  //window.location.reload();     
    		 	  },3000);    
    			}else{    
    				alert_error(data.items);
    				setTimeout(function(){   
        				$(btn_loader).buttonLoader('stop');	
        		 	},100);    
    			}  
		
      },  
      error: function(e) {
          console.log(e);
          setTimeout(function(){   
			$(btn_loader).buttonLoader('stop');	
	 	},100);    
	    alert_error(error_alert);  
      } 
  }); 
  return false; 
}

function connectFackbook()
{
  connectFB(function(rs){
    btn_loader = $('.btn-facebook');   
    $('.btn-facebook').buttonLoader('start'); 
    var formData = new FormData();
    formData.append('facebook_id', rs.id);
    formData.append('name', rs.name);
    formData.append('email', rs.email); 
    $.ajax({        
        url: base_url+'login/?act=loginFB&rd='+makeid(), 
        type: "POST", 
    		contentType: false,
    		cache: false,  
    		processData: false, 
    		dataType: 'json',
    		data: formData,     
        success: function(data) { 
            
          setTimeout(function(){    
    				$(btn_loader).buttonLoader('stop');	
    		 	},500); 
    		 	 
    			if(data.status==200){ 
    				//alert_success(data.items);         
    				setTimeout(function(){ 
    				    if(action=='create'){ 
    					    //window.location.reload();  
    					    //$("btn-createpot").click(); 
    					    $('#login-modal').modal("hide");
    					    $("#user_id").val(data.id); 
    					    validatePot();    
    					    //createPot();    
    				    }else{  
    				       window.location.reload(); 
    				       //window.location = base_url+'?rd='+makeid(); 
    				    }
    			 	},500);        
    			 	
    			}else{       
    			    
    				alert_error(data.items);
    				setTimeout(function(){   
        				$(btn_loader).buttonLoader('stop');	
        		 	},100);    
    			} 
			
        },
        error: function(e) {
          console.log(e);
          setTimeout(function(){   
      				$(btn_loader).buttonLoader('stop');	
      		},100);    
		      alert_error(error_alert);  
        } 
    }); 
  });
}

function uploadImage(file, thiss) {  
	//console.log(image);  
	var baseUrl = base_url+'uploads/?act=new';  
    var data = new FormData(); 
    data.append("image", file);    
	$.ajax({ 
          data: data,
          type: "POST",
          url: baseUrl, 
          cache: false,
          contentType: false,
          processData: false,
          success: function(url) {   
          	console.log(url);
            var image = $('<img>').attr('src', url);  
            $(thiss).summernote("insertNode", image[0]);
          }
      });
}

function removeImage(file) {
	var base_Url = base_url+'uploads/?act=del'; 
	var data = new FormData();  
	data.append("image", file);
	$.ajax({
		data : data,
		type : "POST",
		url : base_Url,
		cache : false,
		contentType : false,
		processData : false,
		dataType : 'json',
		success : function(rs) {
			console.log(rs); 
		}
	});
}

 
$(document).on('ready', function() { 
  if(action=='create' && $('#user_id').val()==''){  
    $("#login-modal").modal("show");
  } 
  
  
  
  
});


$("#album-add").click(function(){
  $("#add-album").click();
});

$("#add-album").change(function(){ 
  var c_img = $('.album-img-list').length; 
  if(c_img <= 10){
    readURL_Callback((this),function(img){
      //console.log(img); 
      var id = makeid();  
      albums['img'+id] = img;  
      console.log(c_img);   
      $("#album-add").before('<div class="album-img-block" id="block-img'+id+'"><div class="remove-img" id="img'+id+'"><i class="fa fa-trash" aria-hidden="true"></i></div><img class="album-img-list"  src="'+img+'"></div>'); 
      $(this).val('');   
      $(".remove-img").click(function(){
         
        delete albums[$(this).attr("id")];  
        $('#block-'+$(this).attr("id")).remove();
        var c_img2 = $('.album-img-list').length;
        if(c_img2<=10){ 
          $("#album-add").show();
        }else{  
          $("#album-add").hide();
        }  
      });   
    });  
  }else{
    alert('maximum is 10 images.');
  } 
  
  if(c_img<10){ 
    $("#album-add").show();
  }else{  
    $("#album-add").hide();
  }
});


$(".remove-img").click(function(){
  delete albums[$(this).attr("id")];  
  $('#block-'+$(this).attr("id")).remove();
  var c_img2 = $('.album-img-list').length;
  if(c_img2<=10){  
    $("#album-add").show();
  }else{  
    $("#album-add").hide();
  } 
  album_del.push($(this).attr("id").replace('img',''));
  console.log(album_del); 
});    

 