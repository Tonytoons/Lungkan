$(document).on('ready', function() {  
    var clipboard = new ClipboardJS('#btn-clipboard', {
        text: function() { 
            return document.querySelector('input[id=referralLink]').value;
        } 
    });
    clipboard.on('success', function(e) { 
      document.getElementById("referralLink").select();
    });
    
    $('#pagination1').pagination({
      ajax: function(options, refresh, $target){
        $.ajax({
          url: base_url+'view/'+id+'/?act=messages&rd='+makeid(),
          data:{ 
            page: options.current,
            length: 10
          },
          dataType: 'json'
        }).done(function(res){
          var html = ''; 
          //console.log(res.data);
          if(res.total>0){  
              $.each(res.data, function(i, item) {
                    html += '<div class="col-sm-12 col-md-12 col-lg-12">';
                    html += '    <h3 class="h6 font-weight-normal mb-2">'+item.message+'</h3>';
                    html += '    <div class="media">'; 
                    html += '      <div class="u-avatar mr-2">';
                    html += '        <img class="img-fluid rounded" src="'+item.user_cover+'" alt="'+item.name+'">';
                    html += '      </div>';  
                    html += '      <div class="media-body text-left">'; 
                    if(lang=='th'){ 
                      html += '        <h4 class="h6 font-weight-normal mt-1">'+item.name+' เมื่อวันที่ '+item.date_fomat+' - ผู้เข้าร่วม '+item.amount_fomat+' บาท</h4>';
                    }else{
                      html += '        <h4 class="h6 font-weight-normal mt-1">'+item.name+' on the '+item.date_fomat+' - Participant '+item.amount_fomat+' THB</h4>';
                    }
                    html += '      </div>';
                    html += '   </div>';    
                    html += '</div>';   
                    //if(i != 0 && (i+1)==res.length){ 
                        html += '<hr class="my-3">';
                    //}   
              }); 
          }  
          
          refresh({ 
            total: res.total, // optional
            length: res.length // optional
          }); 
          if(res.total<res.length){     
              $('#pagination1').hide();
          }  
          if(res.total==0){ 
            if(lang=='th'){
              html = '<div class="col-sm-12 col-md-12 col-lg-12 text-center"><small class="form-text text-muted mb-3 mt-3">ยังไม่มีข้อความ</small></div>';
              //$('#tab1 .card').hide();
            }else{
              html = '<div class="col-sm-12 col-md-12 col-lg-12 text-center"><small class="form-text text-muted mb-3 mt-3">No message yet.</small></div>';
            
            }
          } 
          $("#message-list").html(html);
        }).fail(function(error){
        
        });
      }
    }); 
    
    
    $('#pagination2').pagination({
      ajax: function(options, refresh, $target){
        $.ajax({ 
          url: base_url+'view/'+id+'/?act=participants&rd='+makeid(),
          data:{ 
            page: options.current,
            length: 20
          }, 
          dataType: 'json'
        }).done(function(res){
          var html = '';   
          console.log(res);
          if(res.total>0){  
              $.each(res.data, function(i, item) { 
                    html += '<div class="col-sm-12 col-md-6 col-lg-6">';
                    html += '    <div class="media  mb-2">';  
                    html += '      <div class="u-avatar mr-2">';
                    html += '        <img class="img-fluid rounded" src="'+item.user_cover+'" alt="'+item.name+'">';
                    html += '      </div>';  
                    html += '      <div class="media-body text-left">';
                    html += '        <h4 class="h6 font-weight-normal mb-0">'+item.name+'</h4>';
                    if(lang=='th'){
                      html += '        <small class="form-text text-muted mb-0 mt-0">'+item.amount_fomat+' บาท</small>';
                      html += '        <small class="form-text text-muted mb-0 mt-0">เมื่อวันที่ '+item.date_fomat+'</small>';
                    }else{
                      html += '        <small class="form-text text-muted mb-0 mt-0">'+item.amount_fomat+' THB</small>';
                      html += '        <small class="form-text text-muted mb-0 mt-0">on the '+item.date_fomat+'</small>';
                    }
                    
                    html += '      </div>';
                    html += '    </div>';    
                    html += '</div>';
                    
              });    
          }  
          
          refresh({ 
            total: res.total, // optional
            length: res.length // optional
          });
          //console.log([res.total,res.length]);
          if(res.total<res.length){     
              $('#pagination2').hide();
          }  
          if(res.total==0){
            if(lang=='th'){
              html = '<div class="col-sm-12 col-md-12 col-lg-12 text-center"><small class="form-text text-muted mb-3 mt-3">ยังไม่มีผู้เข้าร่วม</small></div>';
            }else{
              html = '<div class="col-sm-12 col-md-12 col-lg-12 text-center"><small class="form-text text-muted mb-3 mt-3">No participant yet.</small></div>';
            }
          } 
          
          $("#participants-list").html(html);
          
        }).fail(function(error){
        
        });
      }
    }); 
    
    
    $(".social-share").jsSocials({
        showCount: false,
        showLabel: true,
        shareIn: "popup", 
        shares: [
            { share: "facebook", label: "facebook" },
            "twitter",
            "whatsapp",
            "line",
            "wechat",
            //"googleplus",
            //"email",
             //"facebook",
            //
            //"linkedin",
            
            //{ share: "pinterest", label: "Pin this" },
            //"stumbleupon", 
        ] 
    }); 
    
     
    $('.owl-carousel').owlCarousel({
      items: 1,
      margin: 10,
      autoHeight: true,
      loop:true,
      autoplay:true,
      autoplayTimeout:6000,
      autoplayHoverPause:true,
      dots:false
    });   
     
}); 