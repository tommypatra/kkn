var dtTable = null;
var dtTablePeserta = null;
var waktu = new Date();
var idkkn=null;

//inisiasi select2
sel2_tahun("#setuptahun");
sel2_datalokal("#idjadwalkkn", null, false);

$("#setuptahun").val(waktu.getFullYear()).trigger("change");
loadjadwal($("#setuptahun").val());
//loadTabel();
//end inisiasi select2

$('#setuptahun').on('select2:select', function (e) {
    $(".menu-navigasi").hide();
    loadjadwal($(this).val());
});

$('#idjadwalkkn').on('select2:select', function (e) {
    let id = $(this).val();
    reloadmenu(id);
});

function reloadmenu(id){
    $(".menu-navigasi").show();
    //url-posko
    $(".url-posko").attr("href",vBase_url+"web/lokasiposko/"+id);
    $(".url-posko").attr("target","_blank");
    //url-posko
    $(".url-wilayah").attr("href",vBase_url+"web/lokasi/"+id);
    $(".url-wilayah").attr("target","_blank");
    //url-dpl
    $(".url-dpl").attr("href",vBase_url+"web/dpl/"+id);
    $(".url-dpl").attr("target","_blank");
    //url-peserta
    $(".url-peserta").attr("href",vBase_url+"web/peserta/"+id);
    $(".url-peserta").attr("target","_blank");
}

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
            "url": vBase_url + "app/penilaian/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idkkn = $("#setupjadwal #idjadwalkkn").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [8, "ASC"],
            [9, "ASC"],
            [10, "ASC"],
            [11, "ASC"],
        ],
        "columns": [
            {
                "data": "no",
                "orderable": false,
                "searchable": false,
                "width": "3%",
            },
            {
                "data": "dpl_nama",
                "width": "15%",
            },
            {
                "data": "kelompok",
                "width": "20%",
                "orderable": false,
                "searchable": false
            },
            {
                "data": "datamhs",
                "width": "35%",
                "orderable": false,
                "searchable": false
            },
            {
                "data": "nilai_angka",
                "width": "8%",
            },
            {
                "data": "nilai_input",
                "width": "10%",
            },
            {
                "data": "nilai_akhir",
                "width": "8%",
            },
            {//8
                "data": "namakelompok",
                visible: false,        
            },
            {//9
                "data": "dpl_nama",
                visible: false,        
            },
            {//10
                "data": "urut",
                visible: false,        
            },
            {//11
                "data": "nama",
                visible: false,        
            },
            {
                "data": "nim",
                visible: false,       
            },
            {
                "data": "prodi",
                visible: false,
            },
            {
                "data": "jabatan",
                visible: false,
            },
            {
                "data": "nilai_angka",
                visible: false,
            },
            {
                "data": "nilai_angka2",
                visible: false,
            },
            {
                "data": "nilai_akhir",
                visible: false,
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

$("#setupjadwal").submit(function (e) {
    e.preventDefault();
    reloadTable();
});
//end filter data

function reloadTable() {
    if (dtTable)
        dtTable.ajax.reload(null, false);
}

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

//jadwal kkn di select2
function loadjadwal(tahun) {
    let vselector="#idjadwalkkn";
    let formVal = {
        0: { val: tahun, fld: "tahun", cond: "where" },
    };
    appAjax("app/jadwal/cari", formVal).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option(v.tema+' ('+v.jenis+')', v.id, false, false);
                $(vselector).append(newOption).trigger('change');
            });
        }
    });
}