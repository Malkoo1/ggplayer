$(".contact_form #send_message").on('click', function(){
    var name 		= $(".contact_form #name").val();
    var email 		= $(".contact_form #email").val();
    var subject 	= $(".contact_form #subject").val();
    var message 	= $(".contact_form #message").val();
    var success     = $(".contact_form .returnmessage").data('success');

    // alert('hello');

    $(".contact_form .returnmessage").empty(); //To empty previous error/success message.
    //checking for blank fields
    if(name===''||email===''||message===''){
        $('.contact_form div.empty_notice').slideDown(500).delay(2000).slideUp(500);
    }
    else{
        // Returns successful data submission message when the entered information is stored in database.
        $.post("modal/contact.php",{ ajax_name: name, ajax_email: email, ajax_subject: subject, ajax_message:message}, function(data) {

            $(".contact_form .returnmessage").append(data);//Append returned message to message paragraph


            if($(".contact_form .returnmessage span.contact_error").length){
                $(".contact_form .returnmessage").slideDown(500).delay(2000).slideUp(500);
            }else{
                $(".contact_form .returnmessage").append("<span class='contact_success'>"+ success +"</span>");
                $(".contact_form .returnmessage").slideDown(500).delay(4000).slideUp(500);
            }

            if(data===""){
                $("#contact_form")[0].reset();//To reset form fields on success
            }

        });
    }
    return false;
});
