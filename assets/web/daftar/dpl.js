var dtTable = null;
loadTabel();

//datatables, menampilkan data
function loadTabel() {
    dtTable = $('.table-data').DataTable({
        "autoWidth": false,
        "bDestroy": true,
        "processing": false,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 75, -1],
            ["25", "50", "75", "Semua"]
        ],
        "ajax": {
            "url": vBase_url + "web/read_dpl",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idkkn = $("#idkkn").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [4, "asc"],
            [6, "asc"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "detdpl",
            "width": "50%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "kel",
            "width": "10%",
        },
        {
            "data": "detkelompok",
            "width": "35%",
            "orderable": false,
            "searchable": false
        },
        {//4
            "data": "nama",
            "visible": false,
        },
        {//5
            "data": "nip",
            "visible": false,
        },
        {//6
            "data": "namakelompok",
            "visible": false,
        },
        {//7
            "data": "desa",
            "visible": false,
        },
        {
            "data": "email",
            "visible": false,
        },
        ],
        initComplete: function (e) {
            var api = this.api();
            $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function (e) {
                if (e.keyCode == 13) {
                    api.search(this.value).draw();
                }
            });
        },
    });
}

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});