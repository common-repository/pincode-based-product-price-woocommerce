//variation product delete
function bpdpw_variation_delete(id,pid,v_id){

    var link = jQuery('#post_url'+v_id).val();
  
   jQuery.ajax({
  
      type: "POST",
  
      url: link,
  
      data:{
  
      'action':'bpdpw_delete_data',
  
      'id':id,
  
      'variation_id':v_id,
  
      'post_id':pid,
  
    },
  
    success: function(data){
      jQuery('#row_'+v_id).remove();
      Swal.fire({
          title: "Pincode delete",
          text: "Pincode delete successfully!",
          icon: "success",
          button: "ok",
        });
      jQuery('#div_pincode_'+v_id).html(data);
  
    }
  });
  
  }
  

//variation product save
  function bpdpw_variation_save(num_row,vid){ 

    var pincode=jQuery('#pincode_'+num_row+vid).val();
 
    var price=jQuery('#price_'+num_row+vid).val();
 
    var link = jQuery('#post_url'+vid).val();
 
    var product_id = jQuery('#post_ID'+vid).val();
 
   jQuery.ajax({
 
     type: "POST",
 
     url: link,
 
     data:{
 
     'variation_id':vid,
 
     'pincode':pincode,
 
     'action':'bpdpw_data_save',
 
     'price':price,
 
     'product_id':product_id,
 
   },
 
   success: function(data){
     Swal.fire({
         title: "Pincode save",
         text: "Pincode saved successfully!",
         icon: "success",
         button: "ok",
       });
     jQuery('#div_pincode_'+vid).html(data);
 
   }
 
 });
 
 }


//variation product bulk pincode set
 function bpdpw_variation_save_bulk(v_id){
   
    if(v_id==''){}
    else{
    var post_variation_id=jQuery('#post_variation_id').val();
    
    var bulk_pincode_start=jQuery('#pin_start'+v_id).val();
    
    var bulk_pincode_end=jQuery('#pin_end'+v_id).val();
    
     var bulk_price=jQuery('#bulk_price'+v_id).val();
    
    var link = jQuery('#post_bulk_url'+v_id).val();
    
    var product_id = jQuery('#post_ID'+v_id).val();
    
    if(parseInt(bulk_pincode_start)>parseInt(bulk_pincode_end)){
       jQuery('.error_msg'+v_id).html('<span class="alert alert-success bg-danger" style="background:#fa5246;padding:10px 25px 10px 25px;color:white;">Please enter valid Pincode range</span>');
       Swal.fire({
            title: "Bulk Pincode",
            text: "Something went wrong!",
            icon: "error",
            button: "ok",
          });
       return false;
    }
    if(bulk_pincode_start=='' || bulk_pincode_end=='' || bulk_price=='' || v_id==''){
      jQuery('.error_msg'+v_id).html('<span class="alert alert-success bg-danger" style="background:#fa5246;padding:10px 25px 10px 25px;color:white;">Please fill all details</span>');
      Swal.fire({
            title: "Bulk Pincode",
            text: "Something went wrong!",
            icon: "error",
            button: "ok",
          });
      return false;
    }
    
    jQuery.ajax({
    
      type: "POST",
    
      url: link,
    
      data:{
    
      'pincode_start':bulk_pincode_start,
    
      'post_variation_id':v_id,
    
      'pincode_end':bulk_pincode_end,
    
      'action':'bpdpw_variation_bulk_data_save',
    
      'price':bulk_price,
    
      'product_id':product_id,
    
    },
    
    success: function(data){
     
       
          Swal.fire({
            title: "Bulk Pincode",
            text: "All pincode saved successfully!",
            icon: "success",
            button: "ok",
          });
      
      jQuery('#div_pincode_'+v_id).html(data);
    
    }
    
    });
    
    }
    }
 
 
  function openNav() {

    document.getElementById("mySidenav").style.width = "250px";

  }



  function closeNav() {

    document.getElementById("mySidenav").style.width = "0";

  }



  function update_pincode(){

    var pincode=jQuery('#update_pin').val();

  jQuery.ajax({

            type: "POST",

            dataType: "json",

            url:woocommerce_params.ajax_url,

            data:{

            'action':'bpdpw_update_pincode',

            'pincode':pincode,

          },

          success: function(data){



           location.reload(true);

          }

        });

  }

  
  function bpdpw_save_pincode(){

    var pincode=jQuery('#pincode').val();

    if(pincode=='undefined' || pincode==''){

      alert('Please fill your picode from where you want to purchage'); return false;

    }

   jQuery.ajax({

      type: "POST",

      url:woocommerce_params.ajax_url,

      data:{

      'action':'bpdpw_save_pincode',

      'pincode':pincode,

    },

    success: function(data){

     location.reload(true);

    }

  });

  }

 //simple product

 

 function bpdpw_save(num_row){ 

    var pincode=jQuery('#pincode_'+num_row).val();
  
     var price=jQuery('#price_'+num_row).val();
  
    var link = jQuery('#post_url').val();
  
    var product_id = jQuery('#post_ID').val();
  
    jQuery.ajax({
  
      type: "POST",
  
      url: link,
  
      data:{
  
      'pincode':pincode,
  
      'action':'bpdpw_data_save',
  
      'price':price,
  
      'product_id':product_id,
  
    },
  
    success: function(data){
      Swal.fire({
          title: "Pincode save",
          text: "Pincode saved successfully!",
          icon: "success",
          button: "ok",
        });
      jQuery('#bpdpw-product-pincode-price .inside').html(data);
  
     //location.reload(true);
  
    }
  
  });
  
  }
  
  
  
  function bpdpw_delete(id,pid){
  
    var link = jQuery('#post_url').val();
  
   jQuery.ajax({
  
      type: "POST",
  
      url: link,
  
      data:{
  
      'action':'bpdpw_delete_data',
  
      'id':id,
  
      'post_id':pid,
  
    },
  
    success: function(data){
      Swal.fire({
          title: "Pincode delete",
          text: "Pincode delete successfully!",
          icon: "success",
          button: "ok",
        });
      jQuery('#bpdpw-product-pincode-price .inside').html(data);
  
    }
  
  
  
  });
  
  }
  

  function bpdpw_save_bulk(){

    var bulk_pincode_start=jQuery('#pin_start_simple').val();
    
    var bulk_pincode_end=jQuery('#pin_end_simple').val();
    
     var bulk_price=jQuery('#bulk_price').val();
    
    var link = jQuery('#post_bulk_url').val();
    
    var product_id = jQuery('#post_ID').val();
    
    console.log(parseInt(bulk_pincode_start)>=parseInt(bulk_pincode_end));
    if(parseInt(bulk_pincode_start)>parseInt(bulk_pincode_end)){
       jQuery('.error_msg').html('<span class="alert alert-success bg-danger" style="background:#fa5246;padding:10px 25px 10px 25px;color:white;">Please enter valid Pincode range</span>');
       Swal.fire({
            title: "Bulk Pincode",
            text: "Something went wrong!",
            icon: "error",
            button: "ok",
          });
       return false;
    }
    if(bulk_pincode_start=='' || bulk_pincode_end=='' || bulk_price==''  ){
      jQuery('.error_msg').html('<span class="alert alert-success bg-danger" style="background:#fa5246;padding:10px 25px 10px 25px;color:white;">Please fill all details</span>');
      Swal.fire({
            title: "Bulk Pincode",
            text: "Something went wrong!",
            icon: "error",
            button: "ok",
          });
      return false;
    }
    
    jQuery.ajax({
    
      type: "POST",
    
      url: link,
    
      data:{
    
      'pincode_start':bulk_pincode_start,
    
      'pincode_end':bulk_pincode_end,
    
      'action':'bpdpw_bulk_data_save',
    
      'price':bulk_price,
    
      'product_id':product_id,
    
    },
    
    success: function(data){
     
       
          Swal.fire({
            title: "Bulk Pincode",
            text: "All pincode saved successfully!",
            icon: "success",
            button: "ok",
          });
      
      jQuery('#bpdpw-product-pincode-price .inside').html(data);
    
    
    
    }
    
    });
    
    }  