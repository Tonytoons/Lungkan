//js for Admin site
var error_txt = 'Oops! Something went wrong, Please try again later.';

function doNothing(){}

function imagePreview(input, id) {
	//console.log(input.files[0].type.match('image.*'));   
	$('#'+id).html('');
    if (input.files && input.files[0] && input.files[0].type.match('image.*')) { 
        var reader = new FileReader();
        reader.onload = function (e) {
        	if(action=='news'){
        		var attr = $(input).attr('id');
				var res = attr.replace("pic", "src-preview"); 
				console.log(res); 
				$("#"+res).val(e.target.result);  
				console.log($("#"+res).val());
        	} 
            var html = '<img src="'+e.target.result+'" style="max-width:25%;">';  
            $('#'+id).html(html);  
        }
        reader.readAsDataURL(input.files[0]);
    }
} 

function addFSlang()
{
	var fab_id = $('#id').val();
	var lang = $('#lang').val();
	var name_lang = $('#name_lang').val();
	var nickname_lang = $('#nickname_lang').val();
	var note_lang = $('#note_lang').val();
	var about_lang = $('#about_lang').val();
	var recent_top_clients_lang = $('#recent_top_clients_lang').val();
	var what_in_lang = $('#what_in_lang').val();
	if (name_lang.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name_lang').focus();
		return false;
	}
	else
	{
		//window.location.href = basePath + '/admin/fabsquad/act/addMoreLang/fabsquad_id/'+fab_id+'/name_lang/'+name_lang+'/nickname_lang/'+nickname_lang+'/note_lang/'+note_lang+'/lang/'+lang+'/';
		var url = basePath + '/index/fabsquad/act/addMoreLang/fabsquad_id/'+fab_id+'/lang/'+lang+'/?jsoncallback=?';
		var data = 'name_lang='+name_lang+'&nickname_lang='+nickname_lang+'&note_lang='+note_lang+'&about_lang='+about_lang+'&recent_top_clients_lang='+recent_top_clients_lang+'&what_in_lang='+what_in_lang; //alert(data);
		$.ajax({
				  type: "POST",
				  url: url,
				  data: data,
				  success : function(html) 
				  {
				  	location.reload();
					window.location.href = basePath + '/index/fabsquad/act/editForm/id/'+fab_id+'/#listing';
				  }
				  //dataType: dataType
				});
	}
}

function checkFSQ()
{
	var cate = $('[name="cate[]"]:checked'); //alert(cate);
	var name = $('#name').val();
	var email = $('#email').val();
	var emailFormat = isValidEmail(email);
	var phone = $('#phone').val();
	var password = $('#password').val();
	if (cate.length == 0)
	{
		alert('No category!');
		document.getElementById('0').focus();
		return false;
	}
	else if (name.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name').focus();
		return false;
	}
	else if (email.length == 0)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (emailFormat == false)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (password.length == 0)
	{
		alert('Check password!');
		document.getElementById('password').focus();
		return false;
	}
	else if (password.length < 4)
	{
		alert('should be 8 characters!');
		document.getElementById('password').focus();
		return false;
	}
	else if (isNaN(phone))
	{
		alert('Number only!');
		document.getElementById('phone').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkPhotoFABsquad()
{
	var fileInput = $('#pic')[0], file = fileInput.files && fileInput.files[0];
	if(file)
	{
		var img = new Image(); 
		
		img.src = window.URL.createObjectURL( file );
		img.onload = function()
		{
			var width = img.naturalWidth, height = img.naturalHeight;
			/*
			window.URL.revokeObjectURL( img.src );
			if( (width != 480) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('pic').focus();
			}
			else if( (height != 480) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('pic').focus();
			}
			*/
		};
	}
}


function addSlang()
{
	var service_id = $('#id').val();//alert(cate_id);
	var lang = $('#lang').val(); //alert(lang);
	var name_lang = $('#name_lang').val(); //alert(name_lang);
	var detail_lang = $('#detail_lang').val(); //alert(detail_lang);
	var long_detail_lang = $('#long_detail_lang').val();
	if (name_lang.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name_lang').focus();
		return false;
	}
	else if (detail_lang.length == 0)
	{
		alert('Please input detail!');
		document.getElementById('detail_lang_lang').focus();
		return false;
	}
	else
	{
		//window.location.href = basePath + '/admin/service/act/addMoreLang/service_id/'+service_id+'/name_lang/'+name_lang+'/detail_lang/'+detail_lang+'/long_detail_lang/'+long_detail_lang+'/lang/'+lang+'/';
		var url = basePath + '/index/service/act/addMoreLang/service_id/'+service_id+'/lang/'+lang+'/?jsoncallback=?';
		var data = 'name_lang='+name_lang+'&detail_lang='+detail_lang+'&long_detail_lang='+long_detail_lang; //alert(data);
		$.ajax({
				  type: "POST",
				  url: url,
				  data: data,
				  success : function(html) 
				  {
				  	location.reload();
					window.location.href = basePath + '/index/service/act/editForm/id/'+service_id+'/#listing';
				  }
				  //dataType: dataType
				}); 
	}
}

function checkPhoto640400()
{
	var fileInput = $('#gallery')[0], file = fileInput.files && fileInput.files[0];
	if(file)
	{
		var img = new Image();
		img.src = window.URL.createObjectURL( file );
		img.onload = function()
		{
			var width = img.naturalWidth, height = img.naturalHeight;
	
			window.URL.revokeObjectURL( img.src );
			if( (width != 640) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('gallery').focus();
			}
			else if( (height != 400) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('gallery').focus();
			}
		};
	}
}

function checkPhoto480()
{
	var fileInput = $('#pic')[0], file = fileInput.files && fileInput.files[0];
	if(file)
	{
		var img = new Image();
		img.src = window.URL.createObjectURL( file );
		img.onload = function()
		{
			var width = img.naturalWidth, height = img.naturalHeight;
	
			window.URL.revokeObjectURL( img.src );
			if( (width != 480) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('pic').focus();
			}
			else if( (height != 640) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('pic').focus();
			}
		};
	}
}

function checkPhoto255()
{
	var fileInput = $('#thumb')[0], file = fileInput.files && fileInput.files[0];
	if(file)
	{
		var img = new Image();
		img.src = window.URL.createObjectURL( file );
		img.onload = function()
		{
			var width = img.naturalWidth, height = img.naturalHeight;
	
			window.URL.revokeObjectURL( img.src );
			if( (width != 225) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('thumb').focus();
			}
			else if( (height != 225) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('thumb').focus();
			}
		};
	}
}

function checkService()
{
	var sub_cate_id = $('#sub_cate_id').val(); //alert(sub_cate_id);
	var name = $('#name').val();
	//var sku_id = $('#sku_id').val();
	//var price = $('#price').val();
	//var duration = $('#duration').val();
	if ( sub_cate_id == null )
	{
		alert('No sub category!');
		return false;
	}
	else if (name.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name').focus();
		return false;
	}
	/*else if (isNaN(price))
	{
		alert('Number only!');
		document.getElementById('price').focus();
		return false;
	}
	else if (isNaN(duration))
	{
		alert('Number only!');
		document.getElementById('duration').focus();
		return false;
	}*/
	else
	{
		return true;
	}
}

function checkPhoto100()
{
	var fileInput = $('#pic')[0], file = fileInput.files && fileInput.files[0];
	if(file)
	{
		var img = new Image();
		img.src = window.URL.createObjectURL( file );
		img.onload = function()
		{
			var width = img.naturalWidth, height = img.naturalHeight;
			/*
			window.URL.revokeObjectURL( img.src );
			if( (width > 250) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('pic').focus();
			}
			*/
		};
	}
}

function checkPCODE()
{
	var code = $('#code').val();
	var discount = $('#discount').val();
	var count = $('#count').val();
	var expire_date = $('#expire_date').val();
	if (code.length == 0)
	{
		alert('Please input promo code!');
		document.getElementById('code').focus();
		return false;
	}
	else if (discount.length == 0)
	{
		alert('Please input discount!');
		document.getElementById('discount').focus();
		return false;
	}
	else if (isNaN(discount))
	{
		alert('Number only!');
		document.getElementById('discount').focus();
		return false;
	}
	else if (isNaN(count))
	{
		alert('Number only!');
		document.getElementById('count').focus();
		return false;
	}
	else if (expire_date.length == 0)
	{
		alert('Please input expiry date!');
		document.getElementById('expire_date').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkEuser()
{
	var name = $('#name').val();//alert(cate_id);
	var email = $('#email').val();
	var emailFormat = isValidEmail(email);
	if (name.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name').focus();
		return false;
	}
	else if (email.length == 0)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (emailFormat == false)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkAddUser()
{
	var name = $('#name').val();//alert(cate_id);
	var email = $('#email').val();
	var emailFormat = isValidEmail(email);
	var password = $('#password').val();
	var cpassword = $('#cpassword').val();
	if (name.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name').focus();
		return false;
	}
	else if (email.length == 0)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (emailFormat == false)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (password.length == 0)
	{
		alert('Please input password!');
		document.getElementById('password').focus();
		return false;
	}
	else if (cpassword.length == 0)
	{
		alert('Please input confirm password!');
		document.getElementById('cpassword').focus();
		return false;
	}
	else if (cpassword != password)
	{
		alert('Please check confirm password!');
		document.getElementById('cpassword').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function addScateLang()
{
	var scate_id = $('#id').val();//alert(cate_id);
	var lang = $('#lang').val(); //alert(lang);
	var name_lang = $('#name_lang').val(); //alert(name_lang);
	var detail_lang = $('#detail_lang').val(); //alert(detail_lang);
	if (name_lang.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name_lang').focus();
		return false;
	}
	else
	{
		window.location.href = basePath + '/index/subcategory/act/addMoreLang/scate_id/'+scate_id+'/name_lang/'+name_lang+'/detail_lang/'+detail_lang+'/lang/'+lang+'/';
	}
}

function checkLogin()
{
	var email = $('#email').val();
	var password = $('#password').val();
	var emailFormat = isValidEmail(email);
	if (email.length == 0)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (emailFormat == false)
	{
		alert('invalid email!');
		document.getElementById('email').focus();
		return false;
	}
	else if (password.length == 0)
	{
		alert('invalid password!');
		document.getElementById('password').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkPhoto()
{
	var fileInput = $('#pic')[0], file = fileInput.files && fileInput.files[0];
	if(file)
	{
		var img = new Image();
		img.src = window.URL.createObjectURL( file );
		img.onload = function()
		{
			var width = img.naturalWidth, height = img.naturalHeight;
	
			window.URL.revokeObjectURL( img.src );
			if( (width != 480) || (height != 255) )
			{ //alert(width);
				alert('Image size not match');
				fileInput.value = '';
				document.getElementById('pic').focus();
			}
		};
	}
}

function checkCountry()
{
	var name = $('#name').val();
	var cc_code = $('#cc_code').val();
	var cc = $('#cc').val();
	if (name.length == 0)
	{
		alert('Do not forget name!');
		document.getElementById('name').focus();
		return false;
	}
	else if (cc_code.length == 0)
	{
		alert('Do not forget country code!');
		document.getElementById('cc_code').focus();
		return false;
	}
	else if (cc.length == 0)
	{
		alert('Do not forget country code number!');
		document.getElementById('cc').focus();
		return false;
	}
	else if (isNaN(cc))
	{
		alert('Number only!');
		document.getElementById('cc').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkCate()
{
	var name = $('#name').val();
	if (name.length == 0)
	{
		alert('Do not forget name!');
		document.getElementById('name').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function addCateLang()
{
	var cate_id = $('#id').val();//alert(cate_id);
	var lang = $('#lang').val(); //alert(lang);
	var name_lang = $('#name_lang').val(); //alert(name_lang);
	var detail_lang = $('#detail_lang').val(); //alert(detail_lang);
	if (name_lang.length == 0)
	{
		alert('Please input name!');
		document.getElementById('name_lang').focus();
		return false;
	}
	else
	{
		/*
		var url = basePath + '/admin/category/act/addMoreLang/';
		var data = {
						cate_id : cate_id,
						name_lang : name_lang,
						detail_lang : detail_lang,
						lang : lang
			};
		$.post(url, data, function(Data)
		{
			window.location.href = basePath + '/admin/category/act/editForm/id/'+cate_id+'/#listing';
		}, 'json');
		*/
		window.location.href = basePath + '/index/category/act/addMoreLang/cate_id/'+cate_id+'/name_lang/'+name_lang+'/detail_lang/'+detail_lang+'/lang/'+lang+'/';
	}
}

function checkSMP()
{
	var sku_id = $('#sku_id').val();
	var price = $('#price').val();
	var duration = $('#duration').val();
	var commission = $('#commission').val();
	
	if (sku_id.length == 0)
	{
		alert('SKU ID!');
		document.getElementById('sku_id').focus();
		return false;
	}
	else if (price.length == 0)
	{
		alert('Price!');
		document.getElementById('price').focus();
		return false;
	}
	else if (duration.length == 0)
	{
		alert('Duration!');
		document.getElementById('duration').focus();
		return false;
	}
	else if (isNaN(price))
	{
		alert('Number only!');
		document.getElementById('price').focus();
		return false;
	}
	else if (isNaN(duration))
	{
		alert('Number only!');
		document.getElementById('duration').focus();
		return false;
	}
	else if (isNaN(commission))
	{
		alert('Number only!');
		document.getElementById('commission').focus();
		return false;
	}
	else if (commission.length == 0)
	{
		alert('Commission!');
		document.getElementById('commission').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkA()
{
	var name = $('#name').val();
	var address = $('#address').val();
	if (name.length == 0)
	{
		alert('Place name!');
		document.getElementById('name').focus();
		return false;
	}
	else if (address.length == 0)
	{
		alert('Address!');
		document.getElementById('address').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function checkC()
{
	var name = $('#name').val();
	var card = $('#card').val();
	var expiration_year = $('#expiration_year').val();
	var expiration_month = $('#expiration_month').val();
	var code = $('#code').val();
	var city = $('#city').val();
	var postal_code = $('#postal_code').val();
	if (name.length == 0)
	{
		alert('Name!');
		document.getElementById('name').focus();
		return false;
	}
	else if (card.length == 0)
	{
		alert('Card number 16 degit!');
		document.getElementById('card').focus();
		return false;
	}
	else if (card.length != 16)
	{
		alert('Card number 16 degit!');
		document.getElementById('card').focus();
		return false;
	}
	else if (isNaN(card))
	{
		alert('Number only!');
		document.getElementById('card').focus();
		return false;
	}
	else if (expiration_year.length == 0)
	{
		alert('Expiration year 4 degit! ex. 2029');
		document.getElementById('expiration_year').focus();
		return false;
	}
	else if (expiration_year.length != 4)
	{
		alert('Expiration year 4 degit! ex. 2029');
		document.getElementById('expiration_year').focus();
		return false;
	}
	else if (isNaN(expiration_year))
	{
		alert('Number only!');
		document.getElementById('expiration_year').focus();
		return false;
	}
	else if (expiration_month.length == 0)
	{
		alert('Expiration month 2 degit! ex. 01');
		document.getElementById('expiration_month').focus();
		return false;
	}
	else if (expiration_month.length != 2)
	{
		alert('Expiration month 2 degit! ex. 01');
		document.getElementById('expiration_month').focus();
		return false;
	}
	else if (isNaN(expiration_month))
	{
		alert('Number only!');
		document.getElementById('expiration_month').focus();
		return false;
	}
	else if (code.length != 3)
	{
		alert('Security code 3 degit! ex. 123');
		document.getElementById('code').focus();
		return false;
	}
	else if (isNaN(code))
	{
		alert('Number only!');
		document.getElementById('code').focus();
		return false;
	}
	else if (city.length == 0)
	{
		alert('City!');
		document.getElementById('city').focus();
		return false;
	}
	else if (isNaN(postal_code))
	{
		alert('Postal code!');
		document.getElementById('postal_code').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function goDB()
{
	var d = $('#reservation').val();
	var withmins = $('#withmins').val(); //alert(d);
	window.location.href = basePath + '/index/dashboard/date/'+d+'/withmins/'+withmins+'/';
}

function goDB2()
{
	var d = $('#reservation2').val(); //alert(d);
	window.location.href = basePath + '/index/dashboard/date/'+d+'/#sales';
}

function goDB3()
{
	var d = $('#reservation3').val(); //alert(d);
	window.location.href = basePath + '/index/dashboard/date/'+d+'/#sales';
}

function goDB4()
{
	var d = $('#reservation4').val(); //alert(d);
	window.location.href = basePath + '/index/dashboard/date/'+d+'/cityID/'+cityID+'/#customers';
}

function goDB5()
{
	var d = $('#reservation5').val(); //alert(d);
	window.location.href = basePath + '/index/dashboard/date/'+d+'/cityID/'+cityID+'/#customers';
}

function goDB6()
{
	var d = $('#reservation6').val(); //alert(d);
	var hkd = $('#hkd').val();
	window.location.href = basePath + '/index/dashboard/date/'+d+'/hkd/'+hkd+'/cityID/'+cityID+'/#sales';
}

function go2booking()
{
	var d = $('#reservation').val();
	var booking_status = $('#booking_status').val();
	var url = basePath + '/index/booking/date/'+d+'/booking_status/'+booking_status+'/cityID/'+cityID+'/'; //alert(url);
	window.location.href = basePath + '/index/booking/date/'+d+'/booking_status/'+booking_status+'/cityID/'+cityID+'/';
}

function go2user()
{
	var d = $('#reservation').val();
	window.location.href = basePath + '/index/user/date/'+d+'/cityID/'+cityID+'/';
}

function go2fabsquad()
{
	var d = $('#reservation').val();
	window.location.href = basePath + '/index/fabsquad/date/'+d+'/cityID/'+cityID+'/';
}

function checkChangeStatus()
{
	var booking_date = $('#booking_date').val();
	var booking_time = $('#booking_time').val();
	var ppl = $('#ppl').val();
	var booking_address = $('#booking_address').val();
	var system_note = $('#system_note').val();
	
	if (booking_date.length == 0)
	{
		alert('Please input booking date!');
		document.getElementById('booking_date').focus();
		return false;
	}
	else if (booking_time.length == 0)
	{
		alert('Please input booking time!');
		document.getElementById('booking_time').focus();
		return false;
	}
	else if (ppl.length == 0)
	{
		alert('Please input number of people!');
		document.getElementById('ppl').focus();
		return false;
	}
	else if (isNaN(ppl))
	{
		alert('Number only!');
		document.getElementById('ppl').focus();
		return false;
	}
	else if (booking_address.length == 0)
	{
		alert('Please input booking address!');
		document.getElementById('booking_address').focus();
		return false;
	}
	else if (system_note.length == 0)
	{
		alert('Please input some reason why You wanna do some change for this booking!');
		document.getElementById('system_note').focus();
		return false;
	}
	else
	{
		return true;
	}
}

function homePopup(v)
{
	if(v == 2)
	{
		$('#detail_en').hide();
		$('#detail_th').hide();
		$('#detail_en2').show();
		$('#detail_th2').show();
       	$('#cke_detail_th2').show();
		$('#cke_detail_en2').show();
	}
	else
	{
		$('#cke_detail_th2').hide();
		$('#cke_detail_en2').hide();
		$('#detail_en').show();
		$('#detail_th').show();
		$('#detail_en2').hide();
		$('#detail_th2').hide();
	}
}

function promocodeFor(v)
{
	if(v == 1)
	{
		$('#showCate').hide();
	}
	else
	{
		$('#showCate').show();
	}
}

var avs = 0;
function openAVS()
{
	if(avs == 1)
	{
		avs = 0;
		$('#avs-box').hide();
	}
	else
	{
		avs = 1;
		$('#avs-box').show();
	}
}

function enSubEbooking()
{
	$('#submit').prop('disabled', false);
}

function isValidEmail(str)
{
	var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	return (filter.test(str));
}

function confirmDel()
{
  	var is_confirmed = confirm(' delete?');
  	return is_confirmed;
}

function confirmCapture(p)
{
  	var is_confirmed = confirm('CAPTURE '+p+' ?');
  	return is_confirmed;
}

function confirmReverse(p)
{
  	var is_confirmed = confirm('Reverse '+p+' ?');
  	return is_confirmed;
}

function go2payout()
{
	var d = $('#reservation').val(); //alert(d);
	window.location.href = basePath + '/index/fabsquadpayout/date/'+d+'/booking_status/7/cityID/'+cityID+'/';
}

function go2salesreport()
{
	var d = $('#reservation').val(); //alert(d);
	window.location.href = basePath + '/index/salesreport/date/'+d+'/booking_status/7/cityID/'+cityID+'/';
}

/*--booking--*/
var stype = 5;
var sid = '';
var smid = '';
var ppl = 1;
var gender = 0;
var for_lang = 'no';
var note = '';
var simg = '';
var cate_name = '';
var service_name = '';
var seg_name = '';
var promocode = '';
var price = '';
var duration = '';
var bookingTime = 0;
var dtcb = '';

function getSub(cate, sub, k)
{
	bookingState('booking-loader');
	cate_name = cate;
	$('.booking-cate-top').css({"text-decoration": "none"});
	$('#sub-'+sub).css({"text-decoration": "underline"});
	var url = api + 'services/stype/1/id/'+sub+'/?jsoncallback=?'; //alert(url);
	$.ajax({
				url: url,
				type: "GET",
				dataType: "json",
				success : function(jsonData) 
				{
				  	if(jsonData.status == 200)
					{
						makeServiceListHTML(jsonData.items, k);
					}
					else
					{
						alert(error_txt);
					}
				}
	});
}

function makeServiceListHTML(data, k)
{
	var html = '';
	$.each(data, function(index, item) 
	{
		var on_call = item['on_call'];
		if(on_call == 'no')
		{
			var img = item['img'];
			html += '<div class="booking-service-img" style="background:url('+img+') center no-repeat; background-size:cover;" onClick="getSdetail('+item['id']+');">';
			html += '<div class="booking-service-listD">';
			html += '<div class="booking-service-name">'+item['name']+'</div>';
			html += '<div class="booking-service-sdetail">'+item['detail']+'</div>';
			html += '<div class="booking-service-price">'+item['price_txt']+'</div>';
			html += '<div class="booking-service-view">VIEW MORE...</div>';
			html += '</div></div>';
		}
	});
	$('#service-list').html(html);
	$('.box-header').hide();
	$('#'+k).show();
	bookingState('services');
}

function bookingState(id)
{
	$('.content').hide();
	$('#'+id).show();
}

function getSdetail(id)
{
	bookingState('booking-loader');
	sid = id;
	var url = api + 'services/stype/2/id/'+id+'/?jsoncallback=?'; //alert(url);
	$.ajax({
				url: url,
				type: "GET",
				dataType: "json",
				success : function(jsonData) 
				{
				  	if(jsonData.status == 200)
					{ 
						if(jsonData.items['on_call'] == 'yes')
						{
							alert('This service can book by phone call only!');
						}
						else
						{
							makeServiceDetailHTML(jsonData.items);
						}
					}
					else
					{
						alert(error_txt);
					}
				}
	});
}

function makeServiceDetailHTML(data)
{
	bookingTime = data['bookingTime'];
	simg = data['thumbnail'];
	service_name = data['name'];
	var html = '';
	html += '<img class="booking-service-imgd" src="'+data['gallery'][0]+'">';
	html += '<div class="booking-service-des">'+data['long_detail']+'</div>';
	html += '<div class="booking-seg-bar">';
	var width = 100/data['price'].length;
	for (var i = 0; i < data['price'].length; ++i) 
	{
		var bclass = 'booking-segment';
		if(i == 0) bclass = 'booking-segment-l';
		if(i == data['price_index']) 
		{
			bclass = 'booking-segment-a';
			smid = data['price'][i]['segment_id'];
			seg_name = data['price'][i]['segment'];
			price = data['price'][i]['price'];
			duration = data['price'][i]['duration'];
		}
		var sn = data['price'][i]['segment'];
		html += '<div class="'+bclass+' booking-segment-normal" style="width:'+width+'%;" id="segment-'+i+'" onClick="bookingSMID('+i+', '+data['price'][i]['segment_id']+', '+data['price'][i]['price']+', '+data['price'][i]['duration']+');" data-name="'+sn+'">'+sn+'</div>';
	}
	html += '</div>';
	
	for (var i = 0; i < data['price'].length; ++i) 
	{
		html += '<div class="booking-service-des-in booking-hide" style="display:none;" id="booking-in-detail-'+i+'">';
		html += '<p> price: '+data['price'][i]['price']+'</p>';
		html += '<p> duration: '+data['price'][i]['duration']+'</p>';
		if(data['price'][i]['detail'] != "undefined") html += '<div>'+data['price'][i]['detail']+'</div>';
		
		if(data['price'][i]['FABsquad'])
		{
			html += '<div class="box box-primary" style="margin-bottom:0px;">';
            html += '<h3 class="box-title" aria-hidden="true" id="booking-top-bar">FABsquad</h3>';
            html += '</div>';
			for (var k = 0; k < data['price'][i]['FABsquad'].length; ++k) 
			{ //console.log(k);
				html += '<div class="booking-detail-fs-box">';
				html += '<img class="booking-detail-fs-img" src="'+data['price'][i]['FABsquad'][k]['img']+'">';
				html += '<div class="booking-detail-fs-r">';
				html += '<div class="booking-detail-fs-name">'+data['price'][i]['FABsquad'][k]['full_name']+'</div>';
				html += '<div class="booking-detail-fs-star">';
				html += '<label>';
                for (var j = 0; j < data['price'][i]['FABsquad'][k]['avg']; ++j)
                {
                	html += '<i class="fa fa-star text-red"></i>';
                }
                html += '</label>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
			}
		}
		html += '</div>';
	}
	html += '<div class="col-md-4 booking-book" onClick="goBookingPage();">continue</div>';
	
	$('#booking-top-bar').html(data['name']);
	$('#service-in-detail').html(html);
	$('#booking-in-detail-'+data['price_index']).show();
	bookingState('service-detail');
}

function bookingSMID(id, sm_id, p, d)
{
	price = p;
	duration = d;
	seg_name = $('#segment-'+id).data("name");
	$('.booking-hide').hide();
	$('#booking-in-detail-'+id).show();
	$(".booking-segment-normal").css({"background-color": "#FFF", "color": "#666"});
	$('#segment-'+id).css({"background-color": "#E9847C", "color": "#FFF"});
	smid = sm_id;
}

function goBookingPage()
{ 
	bookingState('booking-loader');
	var cbhr = bookingTime/60;
	moment.createFromInputFallback = function(config) { config._d = new Date(config._i); };
	var date = new Date();
    date.setHours(date.getHours() + cbhr); //cbhr
    var dtcb = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " +  date.getHours() + ":" + date.getMinutes(); //alert(dtcb);
	$('.booking-page-detail-img').attr("src", simg);
	$('.booking-page-detail-cate').html(cate_name);
	$('.booking-page-detail-service').html(service_name+' | '+seg_name);
	$('#booking-total').html('Total : '+duration+' MIN | '+price+' THB');
	$('#booking-page-top').html('BOOK YOUR SERVICE');
	$('#address').html(address);
	$('#booking-dt').val(dtcb);
	$('#phone').val(phone);
	bookingState('booking-page');
	$('#'+omesiCoxCard).css({"background-color": "#E9847C", "color": "#FFF"});
	$('#booking-dt').datetimepicker({
                                       format:'YYYY-MM-DD HH:mm',
                                       minDate: dtcb
                                    });
}

function checkPromocode()
{
	var pc = $('#promocode').val();
	var url = api + 'promocode/id/'+id+'/promocode/'+pc+'/stype/'+stype+'/sid/'+sid+'/smid/'+smid+'/ppl/1/?jsoncallback=?'; //alert(url);
	
	$.ajax({
				url: url,
				type: "GET",
				dataType: "json",
				success : function(jsonData) 
				{
				  	if(jsonData.status == 200)
					{ 
						alert(jsonData.items['msg']);
						promocode = pc;
						price = jsonData.items['total_price'];
						$('#booking-total').html('Total : '+duration+' MIN | '+price+' THB');
					}
					else
					{
						$('#promocode').val('');
						alert(jsonData.items);
					}
				}
	});
}

function bookingChangeUA(ad, uLat, uLon)
{
	address = ad;
	lat = uLat;
	lon = uLon;
	$('#address').val(ad);
}

function bookingChangeUC(oc)
{
	omesiCoxCard = oc;
	$('#'+omesiCoxCard).css({"background-color": "#E9847C", "color": "#FFF"});
}

function goBookingProcess()
{
	bookingState('booking-loader');
	var is_confirmed = confirm(' BOOK?');
	if(is_confirmed === true)
	{ //alert('rfrfrfr');
		var phone = $('#phone').val();
		var gender = $('#gender').val();
		var for_lang = $('#for_lang').val();
		var address = $('#address').val();
		var note = $('#note').val();
		var bdt = $('#booking-dt').val();
		moment.createFromInputFallback = function(config) { config._d = new Date(config._i); };
		var datetime = new Date(bdt);
		var month = datetime.getMonth() + 1; 
		month = month.toString(); //alert(month.length);
		if(month.length <= 1) month = '0'+month;
		var day = datetime.getDate();
		day = day.toString();
		if(day.length <= 1) day = '0'+day;
		var date = datetime.getFullYear() + "-" + month + "-" + day;
		var time = datetime.getHours() + ":" + datetime.getMinutes();
		if(time.length <= 4) time = time+'0';
		//alert(time);
		//date = '2001-10-12';
		if(stype && id && sid && smid && date && time && phone && omesiCoxCard && address)
		{
			var url = api + 'booking/stype/'+stype+'/id/'+id+'/promocode/'+promocode+'/sid/'+sid+'/smid/'+smid+'/ppl/1/date/'+date+'/time/'+time+'/omesiCoxCard/'+omesiCoxCard+'/phone/'+phone+'/lat/'+lat+'/lon/'+lon+'/gender/'+gender+'/for_lang/'+for_lang+'/lang/'+lang+'/?note='+note+'&address='+address+'&jsoncallback=?'; //alert(url);
			
			$.ajax({
						url: url,
						type: "POST",
						dataType: "json",
						success : function(jsonData) 
						{
						  	if(jsonData.status == 200)
							{ 
								alert(jsonData.items);
								bookingState('cate');
							}
							else
							{
								alert(jsonData.items);
								bookingState('booking-page');
							}
						}
			});
		}
		else
		{ //alert('rrrrrr');
			alert(error_txt);
		}
	}
}

$(function(){
	/*
	$(".removeImg").click(function(){
		var removeImg = $(this).attr('id');
		var res = removeImg.replace("removeImg", "imgBlock");
		console.log(res);  
	});
	
	$(".removeTxt").click(function(){ 
		var removeTxt = $(this).attr('id');  
		var res = removeTxt.replace("removeTxt", "txtBlock");
		console.log(res);  
	});
	*/ 
	
	$("#btn-addContent-en").click(function(){
		var count = $("#input-description-en .form-group").length; 
		count++;
			var html = ''; 
			html += '<div class="form-group" id="txtBlock-en-'+count+'">';    
	  		html += '	<label for="removeTxt-en-'+count+'">Content</label>';   
	  		html += '	<textarea class="form-control" name="data[description_en][]" placeholder="Content" rows="5" required></textarea>'; 
	  		html += '	<p>';
	  		html += '	    <button type="button" id="removeTxt-en-'+count+'" onclick="removeTxt(this);" class="btn btn-danger btn-xs removeTxt"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>';
	  		html += '	</p>';  
			html += '</div>';   
		$("#input-description-en").append(html); 
	});
	
	$("#btn-addContent-th").click(function(){
		var count = $("#input-description-th .form-group").length;
		count++;
		//console.log(count); 
		var html = ''; 
			html += '<div class="form-group" id="txtBlock-th-'+count+'">';   
	  		html += '	<label for="removeTxt-th-'+count+'">Content</label>';   
	  		html += '	<textarea class="form-control" name="data[description_th][]" placeholder="Content" rows="5" required></textarea>'; 
	  		html += '	<p>';
	  		html += '	    <button type="button" id="removeTxt-th-'+count+'" onclick="removeTxt(this);" class="btn btn-danger btn-xs removeTxt"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>';
	  		html += '	</p>';   
			html += '</div>';   
		$("#input-description-th").append(html); 
	});
	   
	$("#btn-addImage-en").click(function(){
		var count = $("#input-description-en .form-group").length;
			count++; 
		var html = ''; 
			html += '<div class="form-group" id="imgBlock-en-'+count+'">'; 
      		html += '	<label for="id="pic-en-'+count+'">Image</label>';   
      		html += '	<input type="file" id="pic-en-'+count+'" name="pic" accept="image/*" onchange="imagePreview(this,\'img-preview-en-'+count+'\')" required>'; 
      		html += '	<textarea class="form-control" name="data[description_en][]" id="src-preview-en-'+count+'" rows="5" style="display:none;"></textarea>';
      		html += '	<div id="img-preview-en-'+count+'"></div>';     
      		html += '	<button type="button" id="removeImg-en-'+count+'" onclick="removeImg(this);" class="btn btn-danger btn-xs removeImg"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>';
    		html += '</div>'; 
		$("#input-description-en").append(html);   
	});
	
	$("#btn-addImage-th").click(function(){ 
		var count = $("#input-description-th .form-group").length;
			count++; 
		var html = ''; 
			html += '<div class="form-group" id="imgBlock-th-'+count+'">';  
      		html += '	<label for="pic-th-'+count+'">Image</label>';    
      		html += '	<input type="file" id="pic-th-'+count+'" name="pic" accept="image/*" onchange="imagePreview(this,\'img-preview-th-'+count+'\')" required>'; 
      		html += '	<textarea class="form-control" name="data[description_th][]" id="src-preview-th-'+count+'" rows="5" style="display:none;"></textarea>';
      		html += '	<div id="img-preview-th-'+count+'"></div>';     
      		html += '	<button type="button" id="removeImg-th-'+count+'" onclick="removeImg(this);" class="btn btn-danger btn-xs removeImg"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>';
    		html += '</div>'; 
		$("#input-description-th").append(html);   
	});
});

function removeImg(ths){
	var attr = $(ths).attr('id'); 
	var res = attr.replace("removeImg", "imgBlock");
	var res2 = attr.replace("removeImg", "src-preview"); 
	var img = $("#"+res2).val(); 
	//console.log(res2);   
	console.log(img); 
	var removeImage = $("#removeImage").val();
	if(img){    
		console.log(img.indexOf('image'));  
		if(img.indexOf('image')<=0){ 
			if(removeImage){ 
				$("#removeImage").val($("#removeImage").val()+','+img); 
			}else{ 
				$("#removeImage").val(img); 
			}
			
		}
		 
	}
	$("#"+res).remove(); 
} 

function removeTxt(ths){ 
	var attr = $(ths).attr('id');
	var res = attr.replace("removeTxt", "txtBlock"); 
	console.log(attr); 
	$("#"+res).remove();    
}

function addImg(ths){
	var attr = $(ths).attr('id'); 
	var res = attr.replace("removeImg", "imgBlock");
	$("#"+res).remove(); 
} 

function addTxt(ths){ 
	var attr = $(ths).attr('id');
	var res = attr.replace("removeTxt", "txtBlock"); 
	$("#"+res).remove();    
}