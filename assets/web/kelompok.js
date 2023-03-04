var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();

//inisiasi select2
sel2_tahun("#setuptahun");
sel2_datalokal("#idlokasi", null, false,"#modal-form-kelompok");
sel2_datalokal("#idpembimbing_kkn", null, false,"#modal-form-kelompok");
sel2_datalokal("#idjadwalkkn", null, false);


$("#setuptahun").val(waktu.getFullYear()).trigger("change");
carijadwal($("#setuptahun").val());
loadTabel();
//end inisiasi select2

$('#setuptahun').on('select2:select', function (e) {
    carijadwal($(this).val());
});


$('#idjadwalkkn').on('select2:select', function (e) {
    let id = $(this).val();
    loadTabel();
    loadpembimbing();
    loadlokasi();
});

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
            "url": vBase_url + "app/kelompok/read",
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
            [3, "ASC"],
            [4  , "ASC"],
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
            "data": "namakelompok",
            "width": "10%",
        },
        {
            "data": "vlokasi",
            "width": "15%",
        },
        {
            "data": "nama",
            "width": "15%",
        },
        {
            "data": "kel",
            "width": "15%",
        },
        {
            "data": "kontak",
            "width": "15%",
            "orderable": false,
            "searchable": false
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
    let idjadwal = $("#idjadwalkkn").val();
    let idsk_pembimbing = $("#idsk_pembimbing").val();
    if(idjadwal==""){
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    }else if(idsk_pembimbing==""){
        showNotification(false, ["Belum ada SK Pembimbing"]);
    }else{
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelompok'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
    } 
});

//untuk ganti, loadlokasi dari paramater array db
function loaddata(db) {
    resetform();
    console.log(db);
    $("#idkelompok").val(db['idkelompok']);
    $("#namakelompok").val(db['namakelompok']);
    $("#idlokasi").val(db['idlokasi']).trigger("change");
    $("#idpembimbing_kkn").val(db['idpembimbing_kkn']).trigger("change");
    $("#keterangan").val(db['keterangan']);
};

//ganti dari datatable
$(document).on("click", ".editRow", function (e) {
    e.preventDefault();
    let cari = {
        0: { val: $(this).data("id"), fld: "k.id", cond: "where" },
    };
    appAjax("app/kelompok/cari", cari).done(function (vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelompok'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loaddata(vRet.db[0]);
        }
    });
});

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/kelompok/delete");
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
        fDelete(submitVal, "app/kelompok/delete");
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
$(document).on("click keypress", "#formkelompok input", function (e) {
    $(this).validationEngine('hideAll');
});



//simpan kelompok
$("#formkelompok").submit(function (e) {
    e.preventDefault();
    let formVal= $(this).serialize();
    if ($(this).validationEngine('validate') && confirm("apakah anda yakin simpan data?")) {
        appAjax("app/kelompok/simpan", formVal).done(function (vRet) {
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
    $("#formkelompok")[0].reset();
    $("#keterangan").val("");
}

//jadwal kkn di select2
function carijadwal(tahun) {
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

//load pembimbing
function loadpembimbing() {
    let vselector = "#idpembimbing_kkn";
    let idkkn=$("#idjadwalkkn").val();
    let formVal = {
        0:{cond:"where",fld:"sp.idkkn",val:idkkn},
    };
    appAjax("app/pembimbing/cari", formVal).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option(v.nama, v.idpembimbing_kkn, false, false);
                $(vselector).append(newOption).trigger('change');
            });
        }
    });
};

//load wilayah
function loadlokasi() {
    let vselector = "#idlokasi";
    let idkkn=$("#idjadwalkkn").val();
    let formVal = {
        0:{cond:"where",fld:"l.idkkn",val:idkkn},
    };
    appAjax("app/lokasi/cari", formVal).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option(v.kecamatan+'/'+v.desa, v.id, false, false);
                $(vselector).append(newOption).trigger('change');
            });
        }
    });
};
