function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    )
}

function loadUrlInModal(url) {
    var uniqueId = uuidv4();
    var modalId = "urlModal_" + uniqueId;
    var modal = 
    `<div id="`+modalId+`" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New</h4>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow: auto;">
                <p class="text-center">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
        </div>
    </div>`;

    $('body').append(modal);
    $('#'+modalId).modal('show');
    $( "#" + modalId + " .modal-body" ).load( url + "?without_layout=true", function() {
        $("#" + modalId + " form").removeAttr('pjax-container');
        $("#" + modalId + " form").addClass('form-in-modal');
    });

    $("#" + modalId + "").on('hidden.bs.modal', function(){
        $("#" + modalId + "").modal('hide');
        $("#" + modalId + "").remove();
    });

}

$(document).on('submit', '.form-in-modal', function(e){
    
    var modalId = $(this).closest('.modal').attr('id');

    paramObj = {
        formId: "#" + modalId + " form",
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

