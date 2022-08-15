$(document).ready(function() {
    $(document).on('click', '.taglist_checkbox', function () {

        var ids = [];

        var counter = 0;
        $('.taglist_checkbox').each(function () {
            if ($(this).is(":checked")) {
                ids.push($(this).attr('id'));
                counter++;
            }
        });
        if (counter == 0) {
          $('.result_tags').empty();
          $('.result_tags').append('No Data Found');
        } else {
          fetchDocuments(ids);
        }
    });
});

function fetchDocuments(id) {
$('.result_tags').empty();

$.ajax({
    type: 'GET',
    url: 'get_document_based_on_tag/' + id,
    success: function (response) {
        var response = JSON.parse(response);
        
        if (response.length == 0) {
          $('.result_tags').append('No Data Found');
        } else {
          response.forEach(element => {
                    var url = '{{ url("/download","file") }}';
                    url = url.replace('file', element.file);
                    $('.result_tags').append(`<p>${element.name}
                      ${element.year}
                      <a href="`+url+`"><i class="fa fa-file-pdf-o" style="color:red"></i></a>
                      </p>`);
                });
        }
    }
});
}