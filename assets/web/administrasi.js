var dtTable = null;
let waktu = new Date();

//inisiasi select2
sel2_tahun("#setuptahun");
sel2_datalokal("#idjadwalkkn", null, false);
sel2_aktif("#upload_file");
sel2_jenisupload("#upload_type");
sel2_ukuranupload("#upload_size");

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

$('#upload_file').on('select2:select', function (e) {
    resetselect2()
});

function resetselect2(){
    $("#formadministrasi").validationEngine('hideAll');
    let nilai = $("#upload_file").val();
    $('#upload_type').removeClass('validate[required]');
    $('#upload_size').removeClass('validate[required]');        
    if(nilai=="y"){
        $('#upload_type').addClass('validate[required]');
        $('#upload_size').addClass('validate[required]');        
    }
}

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
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
            "url": vBase_url + "app/administrasi/read",
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
            [2, "ASC"],
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
            "data": "namaadministrasi",
            "width": "30%",
        },
        {
            "data": "upload_file",
            "width": "10%",
        },
        {
            "data": "upload_type",
            "width": "10%",
        },
        {
            "data": "upload_size",
            "width": "10%",
        },
        {
            "data": "keterangan",
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
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

$("#filterTables").submit(function (e) {
    e.preventDefault();
    reloadTable();
});

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


//ganti dari datatable
$(document).on("click", ".editRow", function(e) {
    e.preventDefault();
    let cari= {
        0:{val: $(this).data("id"),fld: "id",cond: "where"},
    };
    appAjax("app/administrasi/cari", cari).done(function(vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-administrasi'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loaddataform(vRet.db[0]);
        }
    });
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

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/administrasi/delete");
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

//fungsi menghapus data 
function fDelete(submitVal, vUrl) {
    if (confirm("Apakah anda yakin? data akan terhapus secara permanen...")) {
        appAjax(vUrl, submitVal).done(function (vRet) {
            if (vRet.status) {
                dtTable.ajax.reload(null, false);
            }
            showNotification(vRet.status, vRet.pesan);
        });
    }
}

//bersihkan validasi
$(document).on("click keypress", "#formadministrasi input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan administrasi
$("#formadministrasi").submit(function (e) {
    e.preventDefault();
    let formVal= $(this).serialize();
    formVal=formVal+"&idkkn="+$("#idjadwalkkn").val();
    if ($(this).validationEngine('validate') && confirm("apakah anda yakin simpan data?")) {
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