<script>
    jQuery(document).ready(function(){

        $("<?=$validator['selector']; ?>").validate({
            errorElement: 'div',
            errorClass: 'uk-alert uk-alert-danger',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length ||
                    element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.insertAfter(element.parent());
                    // else just place the validation message immediatly after the input
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                //$(element).closest('.form-group').addClass('has-error'); // add the Bootstrap error class to the control group
                $(element).addClass('uk-form-danger'); // add the UIKit error class

                //Add has error to tab
                var tab_name = $(element).closest('.tab-pane').attr('id');
                $('li.'+tab_name).addClass('has-error');
            },

            /*
             // Uncomment this to mark as validated non required fields
             unhighlight: function(element) {
             $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
             },
             */
            success: function(element) {
                //$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // remove the Boostrap error class from the control group
                $(element).parent().find('input').removeClass('uk-form-danger');
                $(element).removeClass('uk-alert').removeClass('uk-alert-danger').addClass('uk-form-success'); // remove the UIKit error class

                //Delete has error to tab
                //Ищем, нет ли ошибки в любом другом поле этого таба
                //var tab_name = $(element).closest('.tab-pane').attr('id');
                //$('li.'+tab_name).removeClass('has-error');
            },

            focusInvalid: true, // do not focus the last invalid input
            <?php if (Config::get('jsvalidation.focus_on_error')): ?>
            invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())
                    return;

                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, <?php echo Config::get('jsvalidation.duration_animate') ?>);
                $(validator.errorList[0].element).focus();

            },
            <?php endif; ?>

            rules: <?php echo json_encode($validator['rules']); ?>,

            ignore: ""  // validate all fields including form hidden input
        })
    })
</script>
