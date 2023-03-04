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
            "url": vBase_url + "web/read_dokumen",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.dokumen = $("#dokumen").val();
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
            [2, "desc"],
            [3, "asc"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false,
            "width": "3%",
        },
        {
            "data": "dokumen",
            "width": "97%",
        },
        {//2
            "data": "waktu",
            "visible": false,
        },
        {
            "data": "judul",
            "visible": false,
        },
        {
            "data": "keterangan",
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