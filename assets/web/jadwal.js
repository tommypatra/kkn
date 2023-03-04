/*
$('.editorweb').summernote({
    height: 120,
    toolbar: [
        ['all', ['bold', 'italic', 'underline', 'strikethrough', 'ul', 'ol', 'link']]
    ],
});
*/

sel2_semester("#semester");
sel2_tahun("#tahun");
sel2_jeniskkn("#jenis");

sel2_semester("#flt_semester");
sel2_tahun("#flt_tahun");
sel2_jeniskkn("#flt_jenis");


let waktu = new Date(); 
let tgl_1 = waktu.getDate() + "-"+ (waktu.getMonth()+1)  + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-"+ (waktu.getMonth()+1)  + "-" + waktu.getDate();

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
});

$(".card .togglefilter").click(function(e){
    $(".card .filter").slideToggle();    
});

//datatables, menampilkan data 
var dtTable = $('.table').DataTable({
    "autoWidth": false,
    "processing": false,
    "serverSide": true,
    "lengthMenu": [
        [25, 50, 75, -1],
        ["25", "50", "75", "Semua"]
    ],
    "ajax": {
        "url": vBase_url + "app/jadwal/read",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {
            d.filterTables = $("#filterTables").serializeArray();
        },
        "dataSrc": function(json) {
            return json.data;
        },
    },
    dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
    buttons: [
        'copy', 'excel', 'print'
    ],
    "order": [
        [2, "desc"],
        [4, "desc"],
        [3, "desc"],
    ],
    "columns": [{
            "data": "cek",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "tahun",
            "width": "5%",
        },
        {
            "data": "tema",
            "width": "25%",
        },
        {
            "data": "jenis",
            "width": "10%",
        },
        {
            "data": "tempat",
            "width": "20%",
        },
        {
            "data": "pendaftaran",
            "orderable": false,
            "searchable": false,
            "width": "10%",
        },
        {
            "data": "pelaksanaan",
            "orderable": false,
            "searchable": false,
            "width": "10%",
        },
        {
            "data": "publishkelompok",
            "orderable": false,
            "searchable": false,
            "width": "10%",
        },
        {
            "data": "laporan",
            "orderable": false,
            "searchable": false,
            "width": "10%",
        },
        {
            "data": "penilaian",
            "orderable": false,
            "searchable": false,
            "width": "10%",
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
    ],
    initComplete: function(e) {
        var api = this.api();
        $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
                api.search(this.value).draw();
            }
        });
    },
});

//filter data
$("#filterTables").submit(function(e) {
    e.preventDefault();
    dtTable.ajax.reload(null, false);
});


//menampilkan form modal
$(".addPage").click(function() {
    resetform();
    var myModal = new bootstrap.Modal(document.getElementById('modal-form-jadwal'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
});

//ganti dari datatable
$(document).on("click", ".editRow", function(e) {
    e.preventDefault();
    let cari= {
        0:{val: $(this).data("id"),fld: "id",cond: "where"},
    };
    appAjax("app/jadwal/cari", cari).done(function(vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-jadwal'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loadjadwal(vRet.db[0]);
        }
    });
});

//hapus dari datatable
$(document).on("click", ".deleteRow", function(e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal={"idTerpilih":idTerpilih};
    fDelete(submitVal, "app/jadwal/delete");
});

//menghapus data dari ceklist datatables 	
$(".hapusTerpilih").click(function() {
    let idTerpilih = [];
    $('.cekbaris').each(function(i) {
        if ($(this).is(':checked')) {
            idTerpilih.push($(this).val());
        }
    });
    if (idTerpilih.length > 0) {
        let submitVal={"idTerpilih":idTerpilih};
        fDelete(submitVal, "app/jadwal/delete");
    }
})


//fungsi menghapus data 
function fDelete(submitVal, vUrl) {
    if (confirm("Apakah anda yakin? data akan terhapus secara permanen...")) {
        appAjax(vUrl, submitVal).done(function(vRet) {
            if (vRet.status) {
                dtTable.ajax.reload(null, false);
            }
            showNotification(vRet.status,vRet.pesan);    
        });
    }
}


//bersihkan loadjadwal dari paramater array db
function loadjadwal(db){
    resetform();
    $("#idjadwal").val(db['id']);
    $("#tahun").val(db['tahun']).trigger("change");
    $("#semester").val(db['semester']).trigger("change");
    $("#jenis").val(db['jenis']).trigger("change");
    $("#tema").val(db['tema']);
    $("#tempat").val(db['tempat']);
    $("#slug").val(db['slug']);
    $("#angkatan").val(db['angkatan']);
    $("#no_sk").val(db['no_sk']);
    $("#tgl_sk").val(db['tgl_sk']);
    $("#daftarmulai").val(db['daftarmulai']);
    $("#daftarselesai").val(db['daftarselesai']);
    $("#kknmulai").val(db['kknmulai']);
    $("#kknselesai").val(db['kknselesai']);
    $("#bagikelompok").val(db['bagikelompok']);
    $("#tamulai").val(db['tamulai']);
    $("#taselesai").val(db['taselesai']);
    $("#nilaimulai").val(db['nilaimulai']);
    $("#nilaiselesai").val(db['nilaiselesai']);
    $("#keterangan").val(db['keterangan']);
};

//bersihkan validasi
$(document).on("click keypress","#formjadwal input",function(e) {
    $(this).validationEngine('hideAll');
});

//simpan jadwal
$("#formjadwal").submit(function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    if ($(this).validationEngine('validate')) {
        appAjax("app/jadwal/simpan", formVal).done(function(vRet) {
            showNotification(vRet.status,vRet.pesan);    
            if (vRet.status) {
                resetform();
                $('#modal-form-jadwal').modal('toggle');
                dtTable.ajax.reload(null, false);
            }
        });
    }
});

$(".refreshData").click(function(){
    dtTable.ajax.reload(null, false);
});

function resetform(){
    $("#formjadwal")[0].reset();
    $(".vselect2").val("").trigger("change");
    $(".datepicker").val(tgl_2);
    $("#keterangan").val("");
    $("#idjadwal").val("");
    //$("#keterangan").summernote("code", "");
}