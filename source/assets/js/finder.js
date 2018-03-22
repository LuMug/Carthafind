$(document).ready(function() {
    var activeSystemClass = $('.list-group-item.active');

    $('#system-search').keyup(function() {
		var finder = this;
        var tableBody = $('.table-list-search tbody');
        var tableRowsClass = $('.table-list-search tbody tr');
        $('.search-sf').remove();

        tableRowsClass.each(function(i, val) {
            var rowText = $(val).text().toLowerCase();
            var inputText = $(finder).val().toLowerCase();

			$('.search-query-sf').remove();
            if(inputText != '')
            {
                tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Stai cercando: "'
                    + $(finder).val()
                    + '"</strong></td></tr>');
            }


            if(rowText.indexOf(inputText) == -1)
            {
                tableRowsClass.eq(i).hide();
            }
            else
            {
                tableRowsClass.eq(i).show();
            }
        });

        if(tableRowsClass.children(':visible').length == 0)
        {
            tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">Nessun risultato.</td></tr>');
        }
    });
});
