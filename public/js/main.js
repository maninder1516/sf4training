$(document).ready(function() {
    console.log('Hello JS');

    // $("form[name='user']").submit(function(event) {
    //     event.preventDefault();

    //     alert('Validate Here');
    //     // event.stopPropagation();
    // });

    
    $("#user_submit").click(function(event) { 
        event.preventDefault();       
        alert('Form is submitting....');
        if(isValid()) {

            $("form[name='user']").submit();
            return true;
        }
        // event.preventDefault();
        // event.stopPropagation();
        //$("form[name='user']").submit();
    });

    function isValid() {        
        const userName = $('#user_name').val();
        if (!userName) {  // Not Empty
            alert('Enter your name!');
            return false;
        }
        // Alphabetic characters only
        if (!userName.match(/^[a-zA-Z\s]+$/)) {  
            alert('Alphabetic characters only!');
            return false;
        }

        // const emailInput = document.getElementById('email');
        // const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        // if (!emailInput.value.match(emailRegex)) {
        //   alert('Invalid email address.');
        // }

        


        return true;
    };

    // $('.usr_hover').hover( function(){
    //     $(this).css('background-color', '#F00');
    // },
    // function(){
    //     $(this).css('background-color', '#000');
    // });

    // Date picker    
    $('#product_make_date').datetimepicker({
        format: 'L'
    });
});