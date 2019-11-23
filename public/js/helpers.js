function loadUrlInModal(url) {
    var modal = 
    `<div id="urlModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New</h4>
            </div>
            <div class="modal-body">
                <p class="text-center">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
        </div>
    </div>`;

    $('body').append(modal);
    $('#urlModal').modal('show');
    $( "#urlModal .modal-body" ).load( url + "?without_layout=true", function() {
        $("#urlModal form").removeAttr('pjax-container');
        $("#urlModal form").addClass('form-in-modal');
    });

    $("#urlModal").on('hidden.bs.modal', function(){
        $("#urlModal").modal('hide');
        $("#urlModal").remove();
    });

}

$(document).on('submit', '.form-in-modal', function(e){
    
    paramObj = {
        formId: "#urlModal form",
    };

    e.preventDefault();
    
    var formData = new FormData(document.querySelector(paramObj.formId))
    if(formData == null) {
        return;
    }
    
    $(paramObj.formId + " [type='submit']").prop('disabled', true);
    
    $.ajax({
        url: $(paramObj.formId).attr('action'),
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
    }).done(function(data){
        if(data.status == true) {
            Swal.fire(
                'Success',
                data.message,
                'success'
            )
            console.log($(paramObj.formId).closest('.modal'));
            $(paramObj.formId).closest('.modal').modal('hide');
            
        } else {
            Swal.fire(
                'Error',
                data.message,
                'error'
            )
        }
    }).fail(function(error){
        ajaxErrorSweetAlert(error);
    }).always(function() {
        $(paramObj.formId + " [type='submit']").prop('disabled', false);
    });
});

function ajaxErrorSweetAlert(jqXhr) {
    if( jqXhr.status === 422 ) {


        //process validation errors here.
        errors = jqXhr.responseJSON.errors; //this will get the errors response data.
        //show them somewhere in the markup
        //e.g
        errorsHtml = '<div class="alert alert-danger text-left"><ul>';

        $.each( errors, function( key, value ) {
            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
        });
        errorsHtml += '</ul></di>';
        
        Swal.fire({
            title: '<strong>Error</strong>',
            type: 'error',
            html: errorsHtml,
        })

        
    } else {
        Swal.fire({
            title: '<strong>Error</strong>',
            type: 'error',
            text: 'Error',
        })
    }
}

