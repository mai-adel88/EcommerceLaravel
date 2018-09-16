	//check all items 
	function check_all()
    { //item_checkbox , check_all
      $('input[class="item_checkbox"]:checkbox').each(function(){
        if($('input[class="check_all"]:checkbox:checked').length==0){
          $(this).prop('checked',false);
        }else{
          $(this).prop('checked',true);
        }
      });
    }

    //delete all after check all
    function delete_all()
    {
        $(document).on('click','.del_all',function() { //when click on this button->do submition to this form
            $('#form_data').submit();
        }
    	$(document).on('click','.delBtn',function() {
    		 //do filter on checked input and count the length of it
    		var check_item = $('input[class="item_checkbox"]:checkbox').filter(":checked").length;   

    		if(check_item >0){
    			$('.record_count').text(check_item); //appear num of checked items 
    			$('.not_empty_record').removeClass('hidden'); //appear this alert
    			$('.empty_record').addClass('hidden');//add hidden class to hidden this alert
    		}else{
    			$('.record_count').text(check_item);
    			$('.not_empty_record').addClass('hidden');
    			$('.empty_record').removeClass('hidden');
    		}

    		$('#multipleDelete').modal('show'); //modal's id 
    	});
    }