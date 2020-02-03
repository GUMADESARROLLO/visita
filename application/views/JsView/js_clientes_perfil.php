<script type="text/javascript">

$(document).ready( function() {
    generateTablePedidos();
});
function generateTablePedidos() {
    $('#tblClientes').DataTable({
        "destroy": true,
        "order": [[ 1, "asc"]],
        "ordering": true,
        "info": true,
        "bPaginate": true,
        "bfilter": false,
        "searching": true,
        "pagingType": "full_numbers",
        "aaSorting": [
            [0, "asc"]
        ],
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "Todo"]
        ],
        "language": {
            "info": `<p class="font-weight-bold">Se encontraron _TOTAL_ registros</p>`,
            "zeroRecords": `<i class="fas fa-search"></i> Sin resultados`,
            "info": "Registro _START_ a _END_ de _TOTAL_ entradas",
            "infoEmpty": "",
            "paginate": {
                "first":      "Primera",
                "last":       "Última ",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "_MENU_",
            "emptyTable": `Sin resultados encontrado`,
            "search":     "BUSCAR"
        },
        "fnInitComplete": function () {
            $("#tblClientes_length").hide();
            $("#tblClientes_filter").hide();
        }
    });
}
</script>