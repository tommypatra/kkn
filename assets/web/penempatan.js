var dtTable = null;
var dtTablePeserta = null;
var waktu = new Date();
var idkkn=null;
var idkelompok=null;
var idpenempatan=null;

//inisiasi select2
sel2_tahun("#setuptahun");
sel2_datalokal("#idjadwalkkn", null, false);
sel2_datalokal("#idpesertaganti", null, false);
sel2_datalokal("#idkelompokganti", null, false,"#modal-form-kelompok");

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

//tambahan - mengecek semua ceklist
$(".cekSemua2").change(function () {
    $(".cekbaris2").prop('checked', $(this).prop("checked"));
});

//ganti jabatan
$(document).on("click", ".table-jadwal .gantiJabatan", function (e) {
    idpenempatan=$(this).data("id");
    $('#idjabatanganti').val("");
    var myModal = new bootstrap.Modal(document.getElementById('modal-form-jabatan'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
});   

//update jabatan
$("#formjabatan").submit(function (e) {
    e.preventDefault();
    let formVal= $(this).serialize();
    formVal=formVal+"&idpenempatan="+idpenempatan;
    if ($(this).validationEngine('validate')) 
        appAjax("app/penempatan/updatejabatan", formVal).done(function (vRet) {
            showNotification(vRet.status, vRet.pesan);
            if (vRet.status) {
                dtTable.ajax.reload(null, false);

                const mymodal = document.querySelector('#modal-form-jabatan');
                const modal = bootstrap.Modal.getInstance(mymodal);    
                modal.hide();
            
            }
        });
});

//bagi otomatis
$(document).on("click", ".bagiOtomatis", function (e) {
    let idjadwal = $("#idjadwalkkn").val();
    if(idjadwal==""){
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    }else{
        window.location.href = vBase_url+"app/autopembagian/"+idjadwal;
    }
});

//ganti jabatan
$(document).on("click", ".gantiKelompok", function (e) {

    let idjadwal = $("#idjadwalkkn").val();
    if(idjadwal==""){
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    }else{
        namapeserta=$(this).data("namapeserta");
        idpeserta=$(this).data("idpeserta");
        loadkelompok();

        $("#idpesertaganti").empty();
        var newOption = new Option(namapeserta, idpeserta, false, false);
        $("#idpesertaganti").append(newOption).trigger('change');

        //$('#idkelompokganti').val("");
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelompok'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
    }    
});   

//bersihkan validasi
$(document).on("click keypress", "#formkelompok", function (e) {
    $(this).validationEngine('hideAll');
});

//bersihkan validasi
$(document).on("click keypress", "#formjabatan", function (e) {
    $(this).validationEngine('hideAll');
});


//update kelompok
$("#formkelompok").submit(function (e) {
    e.preventDefault();
    let formVal= $(this).serialize();
    if ($(this).validationEngine('validate')) 
        appAjax("app/penempatan/updatekelompok", formVal).done(function (vRet) {
            showNotification(vRet.status, vRet.pesan);
            if (vRet.status) {
                dtTable.ajax.reload(null, false);

                const mymodal = document.querySelector('#modal-form-kelompok');
                const modal = bootstrap.Modal.getInstance(mymodal);    
                modal.hide();
            
            }
        });
});

// --------------------SELECT 2------------------------
$("#idpesertaganti").select2({
    minimumInputLength: 3,
    dropdownAutoWidth: true,
    dropdownParent: "#modal-form-kelompok",
    delay: vDelay,
    placeholder: '- cari -',
    ajax: {
        dataType: 'json',
        url: vBase_url + 'app/penempatan/cari',
        timeout: vTimeout,
        type: 'post',
        data: function (params) {
            return {
                vField: "p.id as id,CONCAT(u.nama,' (NIM. ',mhs.nim,')') as text",
                vCari: { 
                    0: { cond: 'where', val: $("#idjadwalkkn").val(), fld: 'pn.idkkn' }, 
                    1: { cond: 'like', val: params.term, fld: 'u.nama' }, 
                },
            }
        },
        processResults: function (data, params) {
            return {
                results: data.db,
            };
        },
    },
});

//update kelompok
function loadkelompok() {
    let vselector="#idkelompokganti";
    let formVal = {"idkkn":$("#setupjadwal #idjadwalkkn").val()};
    appAjax("app/penempatan/loadkelompok", formVal).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option('Kelompok '+v.namakelompok+' ('+v.nama+', lokasi : '+v.desa+', '+v.jumlah+' mhs)', v.idkelompok, false, false);
                $(vselector).append(newOption).trigger('change');
            });
        }
    });
};

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
            "url": vBase_url + "app/penempatan/read",
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
            [4, "ASC"],
            [3, "ASC"],
        ],
        "columns": [
        {
            "data": "cek",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "no",
            "searchable": false
        },
        {
            "data": "fldview",
            "width": "40%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "peserta",
            "width": "40%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
        // untuk pencarian
        {
            "data": "dsnpembimbing",
            "visible": false,
        },    
        {
            "data": "kecamatan",
            "visible": false,
        },    
        {
            "data": "desa",
            "visible": false,
        },    
        {
            "data": "kabupaten",
            "visible": false,
        },    
        {
            "data": "provinsi",
            "visible": false,
        },    
        {
            "data": "namakelompok",
            "visible": false,
        },    
        {
            "data": "dsnpembimbing",
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

//datatables, menampilkan data
function loadPeserta() {
    dtTablePeserta = $('.table-peserta').DataTable({
        "autoWidth": false,
        "bDestroy": true,
        "processing": false,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 75, -1],
            ["25", "50", "75", "Semua"]
        ],
        "ajax": {
            "url": vBase_url + "app/penempatan/readpeserta",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idkkn = $("#setupjadwal #idjadwalkkn").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-12"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        "order": [
            [1, "ASC"],
        ],
        "columns": [
        {
            "data": "cek",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "no",
            "searchable": false
        },
        {
            "data": "fldview",
            "width": "60%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "selectjabatan",
            "width": "30%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
        // untuk pencarian
        {
            "data": "nama",
            "visible": false,
        },
        {
            "data": "nim",
            "visible": false,
        },    
        {
            "data": "desa",
            "visible": false,
        },
        {
            "data": "prodi",
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
function modalpeserta(){
    let idjadwal = $("#idjadwalkkn").val();
    if(idjadwal==""){
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    }else{
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-peserta'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
        loadPeserta();
    } 
};

//tombol untuk menampilkan modal verifikasi
$(document).on("click", ".penempatanRow", function(e) {
    e.preventDefault();
    idkkn = $(this).data("idkkn");
    idkelompok = $(this).data("idkelompok");
    modalpeserta();
});

//aksi ketika pilihan verifikasi telah dipilih

function insertpeserta(formVal,hide=false){
    appAjax("app/penempatan/insertpeserta", {"formVal":formVal}).done(function(vRet) {
        if(vRet.status){
            if(dtTable)
                dtTable.ajax.reload(null, false);
            if(dtTablePeserta)
                dtTablePeserta.ajax.reload(null, false);

            if(hide){
                const mymodal = document.querySelector('#modal-form-peserta');
                const modal = bootstrap.Modal.getInstance(mymodal);    
                modal.hide();
            }     
        }
        showNotification(vRet.status, vRet.pesan);
    });
};

//simpan dari pilih datatable
$(document).on("click", ".insertPeserta", function (e) {
    e.preventDefault();
    let nourut=$(this).data("pilih");
    let formVal = { 
        0:{
            "idkkn": idkkn,
            "idkelompok": idkelompok,
            "idjabatan": $("#idjabatan"+nourut).val(),
            "idpeserta": $(this).data("idpeserta"),
        }
    };
    insertpeserta(formVal);
});

//simpan semua tercek dari datatable
$(document).on("click", ".btn-simpan-penempatan", function (e) {
    e.preventDefault();
    let formVal = [];
    $('.data-peserta input[type=checkbox]').each(function (index, value) {
        if(this.checked){
            var toPush = {};
            toPush["idkkn"] = idkkn;
            toPush["idkelompok"] = idkelompok;
            toPush["idjabatan"] = $("#idjabatan"+(index+1)).val();
            toPush["idpeserta"] = $(this).val();
            formVal.push(toPush);
        }            
        //sList += "(" + $(this).val() + "-" + (this.checked ? "checked" : "not checked") + ")";
    });
    insertpeserta(formVal,true);
});

//hapus 1 row dari datatable
$(document).on("click", ".deletePenempatan", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/penempatan/delete");
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
        fDelete(submitVal, "app/penempatan/deletepenempatan");
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

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});


function loadjabatan(){
    let vselector="#idjabatan";
    let formVal = {};
    appAjax("app/penempatan/loadjabatan", formVal).done(function (vRet) {
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