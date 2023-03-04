var dtTable = null;
var waktu = new Date();
var idkkn=null;

//inisiasi select2
sel2_tahun("#setuptahun");
sel2_datalokal("#idjadwalkkn", null, false);

$("#setuptahun").val(waktu.getFullYear()).trigger("change");
loadjadwal($("#setuptahun").val());
loadTabel();
//end inisiasi select2

$('#setuptahun').on('select2:select', function (e) {
    loadjadwal($(this).val());
});

$('#idjadwalkkn').on('select2:select', function (e) {
    let id = $(this).val();
    loadTabel();
});

//datatables, menampilkan data
function loadTabel() {
    let titleprint = 'Nilai '+$("#idjadwalkkn option:selected").text()+' - '+$("#setuptahun option:selected").text(),

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
            {
                extend: 'copyHtml5',
                title : titleprint,
                exportOptions: {
                    columns: [0,8,9,11,12,13,14,15,16,17]
                }
            },
            {
                extend: 'excelHtml5',
                title : titleprint,
                exportOptions: {
                    columns: [0,8,9,11,12,13,14,15,16,17]
                }
            },
            {
                extend: 'print',
                title : titleprint,
                exportOptions: {
                    columns: [0,8,9,11,12,13,14,15,16,17]
                }
            },        
        ],
        "order": [
            [7, "ASC"],
            [8, "ASC"],
            [10, "ASC"],
            [11, "ASC"],
        ],
        "columns": [
            { //0
                "data": "no",
                "orderable": false,
                "searchable": false,
                "width": "3%",
            },
            {//1
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
            {//4
                "data": "nilai_angka",
                "width": "8%",
            },
            {
                "data": "nilai_input",
                "width": "10%",
                "searchable": false
            },
            {
                "data": "nilai_akhir",
                "width": "8%",
            },
            {//7
                "data": "namakelompok",
                visible: false,        
            },
            {//8
                "data": "dpl_nama",
                visible: false,        
            },
            {//9
                "data": "lokasi",
                visible: false,        
                "orderable": false,
                "searchable": false
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
        $('.table-data').DataTable().ajax.reload(null, false);
}

//fungsi refresh
$(".refreshData").click(function () {
    if (dtTable)
        $('.table-data').DataTable().ajax.reload(null, false);
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

var nilailama=null;
$(document).on("focusin",".nilaimhs",function (e){
    nilailama=$(this).val();
});

$(document).on("blur",".nilaimhs",function (e){
    updatenilai($(this).data("idnilai"),$(this).data("idpenempatan"),$(this).data("idmst_penilaian"),$(this).val());
});

$(document).on("keyup",".nilaimhs",function (e){
    if(e.keyCode == 13){
        updatenilai($(this).data("idnilai"),$(this).data("idpenempatan"),$(this).data("idmst_penilaian"),$(this).val());
    };    
});

function updatenilai(idnilai,idpenempatan,idmst_penilaian,nilai){
    if(nilai!=nilailama){
        nilailama=nilai;
        var formData = {
                "idnilai":idnilai,
                "idpenempatan":idpenempatan,
                "idmst_penilaian":idmst_penilaian, 
                "nilai_angka":nilai,
        }                
    
        if (nilai>=0 && nilai<=100) {
            appAjax("app/penilaian/simpan", formData).done(function(vRet) {
                showNotification(vRet.status,vRet.pesan);    
                if (vRet.status) {
                    if (dtTable)
                        $('.table-data').DataTable().ajax.reload(null, false);
                }
            });
        }else{
            showNotification(false,["masukan nilai antara 0 sd 100"]);    
        }
    }
}    