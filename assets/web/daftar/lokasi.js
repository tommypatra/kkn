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
            "url": vBase_url + "web/read_lokasi",
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
            [1, "asc"],
            [2, "asc"],
            [3, "asc"],
            [4, "asc"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "provinsi",
            "width": "25%",
        },
        {
            "data": "kabupaten",
            "width": "25%",
        },
        {
            "data": "kecamatan",
            "width": "25%",
        },
        {
            "data": "desa",
            "width": "25%",
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