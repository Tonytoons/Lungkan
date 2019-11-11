var table;
var type_img = ['jpg','png','gif','jpeg'];
var language = $('#language').val();
if(language=='') language= 1;

function changelang(url){
    language = $('#language').val(); 
    window.location =  basePath+'admin/'+action+'/'; 
} 
 

	 
function uploadImage(file, thiss) {  
	//console.log(image);  
	var baseUrl = basePath+'admin/uploadimage/';  
    var data = new FormData();
      
    data.append("image", file);   
    data.append("actionpage", action);  
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

$(function(){
   autosize($('.autosize')); 
});


$(window).load(function() {  
    
    
   // console.log(language);
    // admin page
    if(action == 'admin'){    
       
        table = $('#dataTable').DataTable({  
            ajax: basePath+'admin/admin/?task=list',
            iDisplayLength: 50,
            "bSort": false, 
            "serverSide": true,
            "processing": true, 
            aLengthMenu: [ 
    	        [50, 100, 200, 400, 800, 1000],
    	        [50, 100, 200, 400, 800, 1000]
    	    ],  
            columns: [
                { "data": null, 
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var pageNo = table.page.info();
                        //console.log(((iRow+1)+pageNo.start)); 
                        $(nTd).html(((iRow+1)+pageNo.start));    
                    }, 
                },
                { "data": null, 
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html('<img src="'+sData.image+'" style="max-width:100px;">');    
                    }, 
                },
                { "data": "name" },
                { "data": "email" },  
                { "data": null, 
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var status = 'inactive';
                        if(oData.status == 1) status = 'active';
                        $(nTd).html(status);    
                    },
                },
                { "data": null, 
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var level = 'Admin';
                        if(oData.level == 2) level = 'Content'; 
                        //if(oData.level == 3) level = 'งานประชาสัมพันธ์';
                        $(nTd).html(level);   
                    }, 
                }, 
                { "data": null, 
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var html = '';
                        html += ' <a class="btn btn-info btn-sm" href="'+basePath+'admin/admin/?task=edit&id='+oData.id+'"><i class="fa fa-edit"></i>  Edit</a> ';
                        html += ' <a class="btn btn-danger btn-sm" href="'+basePath+'admin/admin/?task=del&id='+oData.id+'"onclick="return confirm(\'Are you sure you want to delete. !\');" ><i class="fa fa-trash-o"></i>  Delete</a> ';
                        $(nTd).html(html);     
                    },
                }, 
            ], 
            createdRow : function( row, data, index ) {
    	        //$(row).attr('data-id', data.pkbook);
    	    }
        }); 
          
    }else if(action=='category'){
        
         table = $('#dataTable').DataTable( {
                ajax: basePath+'admin/'+action+'/?task=list',
                iDisplayLength: 50, 
                "bSort": false, 
                "serverSide": true, 
                "processing": true,
                aLengthMenu: [ 
        	        [50, 100, 200, 400, 800, 1000],
        	        [50, 100, 200, 400, 800, 1000]
        	    ],  
                columns: [
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var pageNo = table.page.info();
                            //console.log(((iRow+1)+pageNo.start)); 
                            $(nTd).html('<button type="button" class="btn btn-warning btn-xs"><i class="fa fa-arrows-alt" aria-hidden="true"></i> drag</button> '+ ((iRow+1)+pageNo.start));    
                        },  
                    },   
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<img src="'+sData.image+'" style="max-width:100px;">');    
                        }, 
                    },
                    { "data": "name" }, 
                    { "data": "createdate" },
                    { "data": "lastupdate" },
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var status = 'inactive';
                            if(oData.status == 1) status = 'active';
                            $(nTd).html(status);    
                        },
                    },
                    { "data": null,  
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var html = '';
                            html += ' <a class="btn btn-info btn-sm" href="'+basePath+'admin/'+action+'/?task=edit&id='+oData.id+'"><i class="fa fa-edit"></i>  Edit</a> ';
                            html += ' <a class="btn btn-danger btn-sm" href="'+basePath+'admin/'+action+'/?task=del&id='+oData.id+'"onclick="return confirm(\'Are you sure you want to delete. !\');" ><i class="fa fa-trash-o"></i>  Delete</a> ';
                            $(nTd).html(html);     
                        },
                    }, 
                ],  
                rowReorder: {
                dataSrc: 'readingOrder',
                //editor:  editor
            },
            select: true,
            createdRow : function( row, data, index ) {
    	        $(row).attr('data-id', data.id);
    	    }
        }); 
     
        table.on( 'row-reorder', function ( e, diff, edit ) {
        	var i = 1;
        	$('#dataTable tbody tr').each(function() { 
    		    var id =  parseInt($(this).attr('data-id'));
    		    reOrder(id,i); 
    		    console.log([id,i]);      
    		    i++; 
    		});     	
         });
    }else if(action=='news'){ 
        
         table = $('#dataTable').DataTable( {
                ajax: basePath+'admin/'+action+'/?task=list',
                iDisplayLength: 50, 
                "bSort": false, 
                "serverSide": true, 
                "processing": true,
                aLengthMenu: [ 
        	        [50, 100, 200, 400, 800, 1000],
        	        [50, 100, 200, 400, 800, 1000]
        	    ],  
                columns: [
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var pageNo = table.page.info();
                            //console.log(((iRow+1)+pageNo.start)); 
                            $(nTd).html('<button type="button" class="btn btn-warning btn-xs"><i class="fa fa-arrows-alt" aria-hidden="true"></i> drag</button> '+ ((iRow+1)+pageNo.start));    
                        },  
                    }, 
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { 
                            $(nTd).html('<img src="'+sData.image+'" style="max-width:150px;">');    
                        },
                    },
                    { "data": "name" },  
                    { "data": "cate" },  
                    { "data": "views" },  
                    { "data": "createdate" },
                    { "data": "lastupdate" },
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var status = 'inactive';
                            if(oData.status == 1) status = 'active';
                            $(nTd).html(status);    
                        },
                    },
                    { "data": null,  
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var html = '';
                            html += ' <a class="btn btn-info btn-sm" href="'+basePath+'admin/'+action+'/?task=edit&id='+oData.id+'"><i class="fa fa-edit"></i>  Edit</a> ';
                            html += ' <a class="btn btn-danger btn-sm" href="'+basePath+'admin/'+action+'/?task=del&id='+oData.id+'"onclick="return confirm(\'Are you sure you want to delete. !\');" ><i class="fa fa-trash-o"></i>  Delete</a> ';
                            $(nTd).html(html);     
                        },
                    }, 
                ],  
                rowReorder: {
                dataSrc: 'readingOrder',
                //editor:  editor
            },
            select: true,
            createdRow : function( row, data, index ) {
    	        $(row).attr('data-id', data.id);
    	    }
        }); 
     
        table.on( 'row-reorder', function ( e, diff, edit ) {
        	var i = 1;
        	$('#dataTable tbody tr').each(function() { 
    		    var id =  parseInt($(this).attr('data-id'));
    		    reOrder(id,i); 
    		    console.log([id,i]);      
    		    i++; 
    		});     	
         });
    }else if(action=='banners'){
        
         table = $('#dataTable').DataTable( {
                ajax: basePath+'admin/'+action+'/?task=list',
                iDisplayLength: 50, 
                "bSort": false, 
                "serverSide": true, 
                "processing": true,
                aLengthMenu: [ 
        	        [50, 100, 200, 400, 800, 1000],
        	        [50, 100, 200, 400, 800, 1000]
        	    ],  
                columns: [
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var pageNo = table.page.info();
                            //console.log(((iRow+1)+pageNo.start)); 
                            $(nTd).html('<button type="button" class="btn btn-warning btn-xs"><i class="fa fa-arrows-alt" aria-hidden="true"></i> drag</button> '+ ((iRow+1)+pageNo.start));    
                        },  
                    }, 
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { 
                            $(nTd).html('<img src="'+sData.image+'" style="max-width:150px;">');    
                        },
                    },
                    { "data": "name" }, 
                    { "data": "createdate" },
                    { "data": "lastupdate" },
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var status = 'inactive';
                            if(oData.status == 1) status = 'active';
                            $(nTd).html(status);    
                        },
                    }, 
                    { "data": null,  
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var html = '';
                            html += ' <a class="btn btn-info btn-sm" href="'+basePath+'admin/'+action+'/?task=edit&id='+oData.id+'"><i class="fa fa-edit"></i>  Edit</a> ';
                            html += ' <a class="btn btn-danger btn-sm" href="'+basePath+'admin/'+action+'/?task=del&id='+oData.id+'"onclick="return confirm(\'Are you sure you want to delete. !\');" ><i class="fa fa-trash-o"></i>  Delete</a> ';
                            $(nTd).html(html);     
                        },
                    }, 
                ],  
                rowReorder: {
                dataSrc: 'readingOrder',
                //editor:  editor
            },
            select: true,
            createdRow : function( row, data, index ) {
    	        $(row).attr('data-id', data.id);
    	    }
        }); 
     
        table.on( 'row-reorder', function ( e, diff, edit ) {
        	var i = 1;
        	$('#dataTable tbody tr').each(function() { 
    		    var id =  parseInt($(this).attr('data-id'));
    		    reOrder(id,i); 
    		    console.log([id,i]);      
    		    i++; 
    		});     	
         });
    }else if(action=='subcategory'){ 
        
         table = $('#dataTable').DataTable( {
                ajax: basePath+'admin/'+action+'/?task=list',
                iDisplayLength: 50, 
                "bSort": false, 
                "serverSide": true, 
                "processing": true,
                aLengthMenu: [ 
        	        [50, 100, 200, 400, 800, 1000],
        	        [50, 100, 200, 400, 800, 1000]
        	    ],  
                columns: [
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var pageNo = table.page.info();
                            //console.log(((iRow+1)+pageNo.start)); 
                            $(nTd).html('<button type="button" class="btn btn-warning btn-xs"><i class="fa fa-arrows-alt" aria-hidden="true"></i> drag</button> '+ ((iRow+1)+pageNo.start));    
                        },  
                    }, 
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { 
                            $(nTd).html('<img src="'+sData.image+'" style="max-width:150px;">');    
                        },
                    },
                    { "data": "name" },   
                    { "data": "cate_name_en" },
                    { "data": "createdate" },
                    { "data": "lastupdate" },
                    { "data": null, 
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var status = 'inactive';
                            if(oData.status == 1) status = 'active';
                            $(nTd).html(status);    
                        },
                    },
                    { "data": null,  
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var html = '';
                            html += ' <a class="btn btn-info btn-sm" href="'+basePath+'admin/'+action+'/?task=edit&id='+oData.id+'"><i class="fa fa-edit"></i>  Edit</a> ';
                            html += ' <a class="btn btn-danger btn-sm" href="'+basePath+'admin/'+action+'/?task=del&id='+oData.id+'"onclick="return confirm(\'Are you sure you want to delete. !\');" ><i class="fa fa-trash-o"></i>  Delete</a> ';
                            $(nTd).html(html);     
                        },
                    }, 
                ],  
                rowReorder: {
                dataSrc: 'readingOrder',
                //editor:  editor
            },
            select: true,
            createdRow : function( row, data, index ) {
    	        $(row).attr('data-id', data.id);
    	    }
        }); 
     
        table.on( 'row-reorder', function ( e, diff, edit ) {
        	var i = 1;
        	$('#dataTable tbody tr').each(function() { 
    		    var id =  parseInt($(this).attr('data-id'));
    		    reOrder(id,i); 
    		    console.log([id,i]);      
    		    i++; 
    		});     	
         });
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}); 

/*
if(action=='activitiesgallery'){ 
    Dropzone.options.imageUpload = {  
        maxFilesize:1,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        init: function() { 
	        this.on("queuecomplete", function (file) {  
	            console.log(file);   
	            table.ajax.reload(null, false); 
	            this.removeAllFiles(); 
	        }); 
	    } 
    }; 
        
    function remove(id){   
    	if(confirm('Are you sure you want to delete. !')){ 
    		$('.loading').show();
    		var url = basePath+'admin/'+action+'/id/'+id+'/act/delete/';
    		$.get(url, function(data){ 
    			table.ajax.reload(null, false);
    			$('.loading').hide();  
    		});
    	}  
    } 
    
	$('#input-content').summernote({ 
	  height: 500,   //set editable area's height
	  codemirror: { // codemirror options
	    theme: 'monokai'
	  }, 
	  callbacks: {
	    onImageUpload: function(image) {
	        uploadImage(image[0]);
	    }
	  }
	});

}*/


 


function reOrder(i, s){  
	var url = basePath+'admin/'+action+'/?task=reoder&id='+i+'&sortby='+s+''; 
	$.get(url, function(rs){ 
	    console.log(rs); 
	}); 
}
    

function changetype(type){ 
    var url = basePath+'admin/'+action+'/?task=listtype/'+type.value+'/'; 
    table.ajax.url(url).load();  
 }  
 
 function removeImage(file) {
	var base_Url = basePath+'admin/delimg/'; 
	var data = new FormData();  
	data.append("image", file);
	data.append("action", action);
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