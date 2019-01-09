
var content = document.querySelector('#content');


page.base('/');

// regular pages

page('/', function(){
    console.log('index');
});

page('manutencao/empresas', function(ctx){
    console.log('Carregando empresas');
    $("#content").load( "/php/views/manutencao/empresas/", function() {
            // Add custom buttons
            var dataTableButtons =  '<div class="dataTables_buttons hidden-sm-down actions">' +
                                        '<span class="actions__item zmdi zmdi-print" data-table-action="print" />' +
                                        '<span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" />' +
                                        '<div class="dropdown actions__item">' +
                                            '<i data-toggle="dropdown" class="zmdi zmdi-download" />' +
                                            '<ul class="dropdown-menu dropdown-menu-right">' +
                                                '<a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a>' +
                                                '<a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a>' +
                                            '</ul>' +
                                        '</div>' +
                                    '</div>';

            // Initiate data-table
            $('#data-table').DataTable({
                autoWidth: false,
                responsive: true,
                lengthMenu: [[15, 30, 45, -1], ['15 Rows', '30 Rows', '45 Rows', 'Everything']], //Length select
                language: {
                    searchPlaceholder: "Search for records..." // Search placeholder
                },
                dom: 'Blfrtip',
                buttons: [ // Data table buttons for export and print
                    {
                        extend: 'excelHtml5',
                        title: 'Export Data'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Export Data'
                    },
                    {
                        extend: 'print',
                        title: 'Material Admin'
                    }
                ],
                "initComplete": function(settings, json) {
                    $(this).closest('.dataTables_wrapper').prepend(dataTableButtons); // Add custom button (fullscreen, print and export)
                }
            });

            // Data table button actions
            $('body').on('click', '[data-table-action]', function (e) {
                e.preventDefault();

                var exportFormat = $(this).data('table-action');

                if(exportFormat === 'excel') {
                    $(this).closest('.dataTables_wrapper').find('.buttons-excel').trigger('click');
                }
                if(exportFormat === 'csv') {
                    $(this).closest('.dataTables_wrapper').find('.buttons-csv').trigger('click');
                }
                if(exportFormat === 'print') {
                    $(this).closest('.dataTables_wrapper').find('.buttons-print').trigger('click');
                }
                if(exportFormat === 'fullscreen') {
                    var parentCard = $(this).closest('.card');

                    if(parentCard.hasClass('card--fullscreen')) {
                        parentCard.removeClass('card--fullscreen');
                        $('body').removeClass('data-table-toggled');
                    }
                    else {
                        parentCard.addClass('card--fullscreen')
                        $('body').addClass('data-table-toggled');
                    }
                }
            });
    });
});

page({
        hashbang:true
      });