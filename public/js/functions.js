function dropAutoCompleteWithUf(idCampo, route, objUF) {

    if( !(objUF instanceof jQuery)){
        console.log('not defined');
        return;
    }

    dropAutoComplete(idCampo, route, objUF)
}

function dropAutoComplete(idCampo, route, objUF) {

    var idCampoAux = idCampo + '_aux';

    if(!objUF){
        objUF =  $();
    }

    widgetInst = $('#' + idCampoAux).autocomplete({
        minLength: 3,
        delay: 1000,
        html:true,
        source: function( request, response ) {
            $.ajax({
                url: route,
                dataType: "json",
                data: {
                    term : request.term,
                    uf_id : objUF.val()
                },
                success: function(data) {
                    var array = $.map(data, function (item) {
                        return {
                            value: item.id,
                            label: item.label,
                            data : item
                        }
                    });
                    response(array)
                }
            });
        },
        select: function (event, ui) {

            var data = ui.item.data;

            if(data){
                $("#" + idCampoAux).val(data.label);
                $("#" + idCampo).val(data.id);
            }

            event.preventDefault();
        }
    }).data('ui-autocomplete');

    if(widgetInst){
        widgetInst._renderItem  = function (ul, item) {

            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
        };
    }
}