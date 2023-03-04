var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();


sel2_statuspeg("#statuspeg","#modal-form");
sel2_publish("#aktivasi","#modal-form");

loadTabel();

$('#iduser').select2({
    minimumInputLength: 5,
    dropdownAutoWidth: true,
    dropdownParent: "#modal-form",
    allowClear: true,
    placeholder: 'masukkan nama pengguna',
    ajax: {
        dataType: 'json',
        url: vBase_url+'app/pembimbinggrup/cariakun',
        delay: 800,
        type: "post",
        data: function(params) {
            return {
                cari: params.term
            };
        },
        processResults: function (data, page) {
            return {
                results: data
            };
        },
    }
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
            "url": vBase_url + "app/pembimbinggrup/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                //d.filterTables = $("#filterTables").serializeArray();
                //d.setupjadwal = $("#setupjadwal").serializeArray();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            {
                extend: 'copyHtml5',
                //title : titleprint,
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                }
            },
            {
                extend: 'excelHtml5',
                //title : titleprint,
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                }
            },
            {
                extend: 'print',
                //title : titleprint,
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                }
            },        
        ],        
        "order": [
            [2, "asc"],
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
            "data": "nama",
            "width": "30%",
        },
        {
            "data": "nip",
            "width": "15%",
        },
        {
            "data": "email",
            "width": "20%",
        },
        {
            "data": "statuspeg",
            "width": "10%",
        },
        {
            "data": "aktivasi",
            "width": "20%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "status",
            "width": "20%",
            "orderable": false,
            "searchable": false
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


function reloadTable() {
    if (dtTable)
        dtTable.ajax.reload(null, false);
}

//menampilkan form modal
$(".addPage").click(function () {
    resetform();
    var myModal = new bootstrap.Modal(document.getElementById('modal-form'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
});

//ganti dari datatable
$(document).on("click", ".editRow", function (e) {
    e.preventDefault();
    let cari = {
        0: { val: $(this).data("id"), fld: "h.id", cond: "where" },
    };
    appAjax("app/pembimbinggrup/cari", cari).done(function (vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loadoutput(vRet.db[0]);
        }
    });
});

//hapus 1 row dari datatable

$(document).on("click", ".setPembimbing", function (e) {
    let submitVal = {
        id:$(this).data("id"),
    };
    appAjax("app/pembimbinggrup/setPembimbing", submitVal).done(function (vRet) {
        if (vRet.status) {
            dtTable.ajax.reload(null, false);
        }
        showNotification(vRet.status, vRet.pesan);
    });
});

$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/pembimbinggrup/delete");
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
        fDelete(submitVal, "app/pembimbinggrup/delete");
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
function loadoutput(db) {
    resetform();
    $("#idhakakses").val(db['idhakakses']);
    $("#idpembimbing").val(db['idpembimbing']);
    
    $("#idgrup").val(db['idgrup']);
    $("#nip").val(db['nip']);
    $('#aktivasi').val(db['aktivasi']).trigger("change");
    $('#statuspeg').val(db['statuspeg']).trigger("change");

    var newOption = new Option(db['nama']+' ('+db['email']+')', db['id'], true, true);
    $('#iduser').append(newOption).trigger('change');
};

//bersihkan validasi
$(document).on("click keypress", "#form input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan jadwal
$("#form").submit(function (e) {
    e.preventDefault();
    let formVal= {
        iduser: $('#iduser').val(),
        idhakakses: $('#idhakakses').val(),
        idpembimbing: $('#idpembimbing').val(),
        idgrup: $("#idgrup").val(),
        statuspeg: $("#statuspeg").val(),
        nip: $("#nip").val(),
        aktivasi: $("#aktivasi").val(),
    };
    if ($(this).validationEngine('validate')) {
        appAjax("app/pembimbinggrup/simpan", formVal).done(function (vRet) {
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
    $('#iduser').empty().trigger("change");
    $('#iduser').val("").trigger("change");
    $("#idhakakses").val("");
    $("#nip").val("");
    $('#statuspeg').val("").trigger("change");
    $('#aktivasi').val("").trigger("change");
    $("#form")[0].reset();
}