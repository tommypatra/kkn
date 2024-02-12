var dtTable = null;
var waktu = new Date();
var idkkn=null;
var idpendaftar=null;
var iduser=null;

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

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});


//ganti status
$("#statusmhs").change(function () {
    loadTabel();
});

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
            "url": vBase_url + "app/pendaftar/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idkkn = $("#setupjadwal #idjadwalkkn").val();
                d.statumhs = $("#statusmhs").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print',
            {
                text: 'Statistik',
                action: function (e, dt, node, config) {
                    window.open(vBase_url+'app/pendaftar/statistik/'+$('#idjadwalkkn').val()+'/'+$('#statusmhs').val(),'_blank');
                }
            }            
        ],
        "order": [
            [8, "ASC"],
            [7, "ASC"],
            [9, "ASC"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "foto",
            "width": "5%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "nama",
            "width": "30%",
        },
        {
            "data": "fakultas",
            "width": "15%",
        },
        {
            "data": "kel",
            "width": "5%",
        },
        {
            "data": "kontak",
            "width": "10%",
            "orderable": false,
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
        {//7
            "data": "idfakultas",
            "visible":false,
            "searchable": false
        },
        {
            "data": "urut",
            "visible":false,
            "searchable": false
        },
        {
            "data": "nim",
            "visible":false,
            "searchable": false
        }
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

//menampilkan form modal
$(".addPage").click(function () {
    resetform();
    let idjadwal = $("#idjadwalkkn").val();
    let idsk_pembimbing = $("#idsk_pembimbing").val();
    if(idjadwal==""){
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    }else{
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-administrasi'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
    } 
});

//refresh upload berkas
function loadberkas(){
    let formVal={
        "idkkn":idkkn,
        "idpendaftar":idpendaftar,
    };
    $("#fidkkn").val(idkkn);
    $("#fidpendaftar").val(idpendaftar);
    
    appAjax("api/berkaspendaftar_administrasi", formVal).done(function(vRet) {
        $(".simpanVerifikasi").hide();
        if(!$('#modal-form-kelengkapan').is(':visible')){
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelengkapan'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            // if(vRet.berkas_db.length>0)
                $(".simpanVerifikasi").show();
            // else
            //     alert("Tidak bisa simpan verifikasi karena administrasi KPM ini belum dilengkapi oleh admin!");
        }
        $(".fmahasiswa").html(vRet.mhs_html);
        $(".fkelengkapan").html(vRet.berkas_html);
    });
}

//tombol untuk menampilkan modal verifikasi
$(document).on("click", ".verRow", function(e) {
    e.preventDefault();
    idkkn=$(this).data("idkkn");
    iduser=$(this).data("iduser");   
    idpendaftar=$(this).data("idpendaftar");
    idpeserta=$(this).data("idpeserta");
    $("#statuspeserta").val('0').change();
    if(idpeserta)
        $("#statuspeserta").val('1').change();
    loadberkas(idpeserta);
});

function loaddataform(db){
    resetform();
    $("#idadministrasi").val(db['id']);
    $("#upload_file").val(db['upload_file']).trigger("change");
    $("#upload_type").val(db['upload_type']).trigger("change");
    $("#upload_size").val(db['upload_size']).trigger("change");
    $("#namaadministrasi").val(db['namaadministrasi']);
    $("#keterangan").val(db['keterangan']); 
}

//aksi ketika pilihan verifikasi telah dipilih
/*
$(document).on("click", ".verBerkas", function (e) {
    e.preventDefault();
    let formVal = { 
        "idupload": $(this).data("idupload"),
        "idkkn": idkkn,
        "idpendaftar": idpendaftar,
        "idadministrasi": $(this).data("idadministrasi"),
        "status": $(this).val(),
    };
    appAjax("app/pendaftar/update_verifikasiberkas", formVal).done(function(vRet) {
        if(vRet.status){
            //$(this).data("idupload",vRet.id);
            //alert(vRet.id);
            loadberkas();
        }
    });

});
*/

$("#formverifikasi").submit(function (e) {
    e.preventDefault();
    let formVal = { 
        "idkkn": idkkn,
        "iduser": iduser,
        "idpendaftar": idpendaftar,
        "dataform": $(this).serialize(),
    };
    if ($(this).validationEngine('validate'))
        if(confirm("apakah anda yakin simpan data?")) {
            appAjax("app/pendaftar/update_verifikasiberkas", formVal).done(function(vRet) {
                if(vRet.status){
                    dtTable.ajax.reload(null, false);
                }
                showNotification(vRet.status, vRet.pesan);
            });
        }
});



//ketika modal ditutup
/*
$('#modal-form-kelengkapan').on('hidden.bs.modal', function () {
    let formVal = { 
        "idkkn": idkkn,
        "idpendaftar": idpendaftar,
    };
    appAjax("app/pendaftar/update_status", formVal).done(function(vRet) {
        if(vRet.status){
        //    dtTable.ajax.reload(null, false);
        }
    });
})
*/

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let submitVal = { 
        "idpeserta": $(this).data("idpeserta"), 
        "idpendaftar": $(this).data("idpendaftar"), 
    };
    let vUrl="app/pendaftar/delete";
    if (confirm("Apakah anda yakin? data akan terhapus secara permanen...")) {
        appAjax(vUrl, submitVal).done(function (vRet) {
            if (vRet.status) {
                dtTable.ajax.reload(null, false);
            }
            showNotification(vRet.status, vRet.pesan);
        });
    }
});

//menghapus banyak data dari ceklist datatables 	
$(".hapusTerpilih").click(function () {
    let idTerpilih = [];
    $('.cekbaris').each(function (i) {
        if ($(this).is(':checked')) {
            idTerpilih.push($(this).val());
        }
    });
    if (idTerpilih.length > 0) {
        let submitVal = { "idTerpilih": idTerpilih };
        fDelete(submitVal, "app/administrasi/delete");
    }
})

//bersihkan validasi
$(document).on("click keypress", "#formadministrasi input", function (e) {
    $(this).validationEngine('hideAll');
});

//bersihkan validasi
$(document).on("click keypress", "#formverifikasi input", function (e) {
    $(this).validationEngine('hideAll');
}); 

$(".hapusVerifikasi").click(function () {
    let formVal= {idpendaftar:$("#fidpendaftar").val()};
    if(confirm("apakah anda yakin?")){
        appAjax("app/pendaftar/hapus_verifikasiberkas", formVal).done(function (vRet) {
            showNotification(vRet.status, vRet.pesan);
            if (vRet.status) {
                dtTable.ajax.reload(null, false);
            }
        });
    }
});

//simpan administrasi
$("#formadministrasi").submit(function (e) {
    e.preventDefault();
    let formVal= $(this).serialize();
    formVal=formVal+"&idkkn="+$("#idjadwalkkn").val();
    if ($(this).validationEngine('validate')) 
        if(confirm("apakah anda yakin simpan data?")){
            appAjax("app/administrasi/simpan", formVal).done(function (vRet) {
                showNotification(vRet.status, vRet.pesan);
                if (vRet.status) {
                    dtTable.ajax.reload(null, false);
                }
            });
        }
});

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

//reset nilai form
function resetform() {
    $("#formadministrasi")[0].reset();
    $("#keterangan").val("");
    $("#idadministrasi").val("");
    $("#upload_file").val("").trigger("change");
    $("#upload_type").val("").trigger("change");
    $("#upload_size").val("").trigger("change");
    resetselect2();
}

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