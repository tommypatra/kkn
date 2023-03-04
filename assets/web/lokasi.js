var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();

$('.appcurrency').mask('000.000.000.000.000.000', {
    reverse: true
});

//inisiasi select2
sel2_tahun("#setuptahun");

sel2_datalokal("#idjadwalkkn", null, false);
sel2_datalokal("#idkabupaten", null, false, "#modal-form-lokasi");
sel2_datalokal("#idkecamatan", null, false, "#modal-form-lokasi");
sel2_datalokal("#iddesa", null, false, "#modal-form-lokasi");

$("#setuptahun").val(waktu.getFullYear()).trigger("change");
carijadwal($("#setuptahun").val());
loadTabel();


//end inisiasi select2
$('#idjadwalkkn').on('select2:select', function (e) {
    let id = $(this).val();
    loadTabel();
});

$('#setuptahun').on('select2:select', function (e) {
    carijadwal($("#setuptahun").val());
});

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});



//datatables, menampilkan data
function loadTabel() {
    dtTable = $('.table').DataTable({
        "autoWidth": false,
        "bDestroy": true,
        "processing": false,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 75, -1],
            ["25", "50", "75", "Semua"]
        ],
        "ajax": {
            "url": vBase_url + "app/lokasi/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.filterTables = $("#filterTables").serializeArray();
                d.setupjadwal = $("#setupjadwal").serializeArray();
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
            "data": "desa",
            "width": "15%",
        },
        {
            "data": "kecamatan",
            "width": "15%",
        },
        {
            "data": "kabupaten",
            "width": "15%",
        },
        {
            "data": "fpergi",
            "width": "15%",
        },
        {
            "data": "fpulang",
            "width": "15%",
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

//filter data
$(".card .togglefilter").click(function (e) {
    $(".card .filter").slideToggle();
});

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
    if (idjadwal != "") {
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-lokasi'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
    } else {
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    }
});

//ganti dari datatable
$(document).on("click", ".editRow", function (e) {
    e.preventDefault();
    let cari = {
        0: { val: $(this).data("id"), fld: "l.id", cond: "where" },
    };
    appAjax("app/lokasi/cari", cari).done(function (vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-lokasi'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loadlokasi(vRet.db[0]);
        }
    });
});

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/lokasi/delete");
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
        fDelete(submitVal, "app/lokasi/delete");
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

//untuk ganti, loadlokasi dari paramater array db
function loadlokasi(db) {
    resetform();
    $("#idlokasi").val(db['id']);
    
    var idkabupaten = $("#idkabupaten").val();
    var idkecamatan = $("#idkecamatan").val();

    var newOption = new Option(db['provinsi'], db['kodewilayah_prov'], true, true);
    $('#idprovinsi').append(newOption).trigger('change');
    var idprovinsi = $("#idprovinsi").val();

    cariwilayah("#idkabupaten", $("#idprovinsi").val(), null, null, db['kodewilayah_kab']);
    cariwilayah("#idkecamatan", $("#idprovinsi").val(), $("#idkabupaten").val(), null, db['kodewilayah_kec']);
    cariwilayah("#iddesa", $("#idprovinsi").val(), $("#idkabupaten").val(),$("#idkecamatan").val(), db['idwilayah_desa']);
    /*
    var newOption = new Option(db['kecamatan'], db['kodewilayah_kec'], true, true);
    $('#idkecamatan').append(newOption).trigger('change');

    var newOption = new Option(db['desa'], db['idwilayah_desa'], true, true);
    $('#iddesa').append(newOption).trigger('change');
    */
    $("#pulang").val(db['fpulang']);
    $("#pergi").val(db['fpergi']);
    $("#keterangan").val(db['keterangan']);
};

//bersihkan validasi
$(document).on("click keypress", "#formlokasi input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan jadwal
$("#formlokasi").submit(function (e) {
    e.preventDefault();
    let formVal= {
        idlokasi: $('#idlokasi').val(),
        idprovinsi: $('#idprovinsi').val(),
        idkabupaten: $('#idkabupaten').val(),
        idkecamatan: $('#idkecamatan').val(),
        iddesa: $('#iddesa').val(),
        idkkn: $("#idjadwalkkn").val(),
        keterangan: $("#keterangan").val(),
        pergi: $('#pulang').cleanVal(),
        pulang: $('#pergi').cleanVal(),
    };
    if ($(this).validationEngine('validate')) {
        appAjax("app/lokasi/simpan", formVal).done(function (vRet) {
            showNotification(vRet.status, vRet.pesan);
            if (vRet.status) {
                //resetform();
                //$('#modal-form-lokasi').modal('toggle');
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
    $("#formlokasi")[0].reset();
    $("#iddesa").val("").trigger("change");
    $("#keterangan").val("");
    //$("#keterangan").summernote("code", "");
}


// --------------------SELECT 2------------------------
$("#idprovinsi").select2({
    minimumInputLength: 3,
    dropdownParent: "#modal-form-lokasi",
    dropdownAutoWidth: true,
    delay: vDelay,
    placeholder: '- cari -',
    ajax: {
        dataType: 'json',
        url: vBase_url + 'master/provinsi/cari',
        timeout: vTimeout,
        type: 'post',
        data: function (params) {
            return {
                vField: "kode as id,provinsi as text",
                vCari: { 0: { cond: 'like', val: params.term, fld: 'provinsi' }, },
            }
        },
        processResults: function (data, params) {
            return {
                results: data.db,
            };
        },
    },
});

$('#idprovinsi').on('select2:select', function (e) {
    cariwilayah("#idkabupaten", $("#idprovinsi").val(), null, null, false);
});

$('#idkabupaten').on('select2:select', function (e) {
    cariwilayah("#idkecamatan", $("#idprovinsi").val(), $("#idkabupaten").val(), null, false);
});

$('#idkecamatan').on('select2:select', function (e) {
    cariwilayah("#iddesa", $("#idprovinsi").val(), $("#idkabupaten").val(), $("#idkecamatan").val(), false);
});

function cariwilayah(vselector, idprovinsi = null, idkabupaten = null, idkecamatan = null, pilihdef = null) {
    vurl = "master/kabupaten/cari";
    cari = {
        vField: "kode as id,kabupaten as text",
        vCari: {
            0: { val: idprovinsi, fld: "kodewilayah_prov", cond: "where" },
        }
    };

    if (idprovinsi && idkabupaten) {
        vurl = "master/kecamatan/cari";
        cari = {
            vField: "kode as id,kecamatan as text",
            vCari: {
                0: { val: idprovinsi, fld: "kodewilayah_prov", cond: "where" },
                1: { val: idkabupaten, fld: "kodewilayah_kab", cond: "where" },
            }
        };
    }

    if (idprovinsi && idkabupaten && idkecamatan) {
        vurl = "master/desa/cari";
        cari = {
            vField: "id,desa as text",
            vCari: {
                0: { val: idprovinsi, fld: "kodewilayah_prov", cond: "where" },
                1: { val: idkabupaten, fld: "kodewilayah_kab", cond: "where" },
                2: { val: idkecamatan, fld: "kodewilayah_kec", cond: "where" },
            }
        };
    }

    appAjax(vurl, cari, false).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", true, true);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option(v.text, v.id, false, false);
                $(vselector).append(newOption).trigger('change');
            });

            if(pilihdef)
                $(vselector).val(pilihdef).trigger('change');
        }
    });
}

//jadwal kkn di select2
function carijadwal(tahun) {
    let vselector="#idjadwalkkn";
    let cari = {
        0: { val: tahun, fld: "tahun", cond: "where" },
    };
    appAjax("app/jadwal/cari", cari).done(function (vRet) {
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
