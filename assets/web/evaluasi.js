var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();


//inisiasi select2
sel2_tahun("#setuptahun");
sel2_tujuan("#tujuan");

sel2_datalokal("#idjadwalkkn", null, false);

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
            "url": vBase_url + "app/evaluasi/read",
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
            [7, "asc"],
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
            "data": "tujuan",
            "width": "20%",
        },
        {
            "data": "judul",
            "width": "30%",
        },
        {
            "data": "link",
            "width": "20%",
        },
        {
            "data": "keterangan",
            "width": "40%",
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "urut",
            "visible": false,
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
    resetform();
    let idjadwal = $("#idjadwalkkn").val();
    if (idjadwal != "") {
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-output'), {
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
        0: { val: $(this).data("id"), fld: "e.id", cond: "where" },
    };
    appAjax("app/evaluasi/cari", cari).done(function (vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-output'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loadevaluasi(vRet.db[0]);
        }
    });
});

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/evaluasi/delete");
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
        fDelete(submitVal, "app/evaluasi/delete");
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
function loadevaluasi(db) {
    resetform();
    $('#tujuan').val(db['tujuan']).trigger("change");
    $("#id").val(db['id']);
    $("#judul").val(db['judul']);
    $("#link").val(db['link']);
    $("#keterangan").val(db['keterangan']);
};

//bersihkan validasi
$(document).on("click keypress", "#formevaluasi input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan jadwal
$("#formevaluasi").submit(function (e) {
    e.preventDefault();
    let formVal= {
        tujuan: $('#tujuan').val(),
        judul: $('#judul').val(),
        link: $('#link').val(),
        idkkn: $('#idjadwalkkn').val(),
        id: $("#id").val(),
        keterangan: $("#keterangan").val(),
    };
    if ($(this).validationEngine('validate')) {
        appAjax("app/evaluasi/simpan", formVal).done(function (vRet) {
            showNotification(vRet.status, vRet.pesan);
            if($("id").val()=="")
                resetform();
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
    $("#id").val("");
    $('#tujuan').val("").trigger("change");
    $("#formevaluasi")[0].reset();
    $("#keterangan").val("");
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
