var dtTable = null;
let waktu = new Date();
loadTabel();

//datatables, menampilkan data
function loadTabel() {
    dtTable = $('.table-jadwal').DataTable({
        "autoWidth": false,
        "bDestroy": true,
        "processing": false,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 75, -1],
            ["25", "50", "75", "Semua"]
        ],
        "ajax": {
            "url": vBase_url + "pembimbing/kelompok/read",
            "dataType": "json",
            "type": "POST",
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [1, "desc"],
            [10, "asc"],
            [11, "asc"],
            [8, "asc"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "tahun",
            "width": "10%",
        },
        {
            "data": "temakkn",
            "width": "10%",
        },
        {
            "data": "pelaksanaan",
            "width": "10%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "kelompok",
            "width": "20%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "jumlahpeserta",
            "width": "10%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "proker",
            "width": "20%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "aksi",
            "width": "10%",
            "orderable": false,
            "searchable": false
        },
        {//8
            "data": "namakelompok",
            "visible":false,
        },
        {
            "data": "desa",
            "visible":false,
        },
        {
            "data": "jenis",
            "visible":false,
        },
        {
            "data": "tema",
            "visible":false,
        },
        {
            "data": "tempat",
            "visible":false,
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

function reloadTable() {
    if (dtTable)
        dtTable.ajax.reload(null, false);
}

//menampilkan form modal
$(document).on("click", ".detailAnggota", function (e) {
    var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelompok'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
});

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

