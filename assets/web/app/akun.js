var dtTable = null;

loadTabel();
//sel2_aktif("#aktif","#modal-form-web");
sel2_jeniskelamin("#kel", "#modal-form-web");


loadgrup();
function loadgrup() {
    let formVal = {
        vTable: "grup",
        vOrder: "nama_grup ASC",
        vField: "*",
        vCari: {},
    };

    $('#idgrup').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{}];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['nama_grup'] });
            });
        }
        sel2_datalokal("#idgrup", dataparent);
    });
}

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
});

//bersihkan validasi
$(document).on("click keypress", "#formweb input,select", function (e) {
    $(this).validationEngine('hideAll');
});

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//tambahan - mengecek semua ceklist
$("#gantipass").change(function () {
    $("#input-password").hide();
    if ($(this).is(':checked')) {
        $("#input-password").show();
    }
});


//tambahan - mengecek semua ceklist
$("#idfakultas").change(function () {
    loadTabel();
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
            "url": vBase_url + "app/akun/read",
            "dataType": "json",
            "type": "POST",
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [8, "asc"],
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
            "data": "foto",
            "width": "15%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "nama",
            "width": "20%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "tmplahir",
            "width": "15%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "alamat",
            "width": "20%",
        },
        {
            "data": "aktivasi",
            "width": "10%",
        },
        {
            "data": "grup",
            "width": "10%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "namaasli",
            "visible": false,
        },
        {
            "data": "email",
            "visible": false,
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

function resetform() {
    $("#check-password").hide();
    $("#input-password").show();
    //$("#formweb").reset();
    $("#formweb")[0].reset();
    $('#idgrup').val(-1).trigger('change');
}

//menampilkan form modal
function tambahForm() {

    var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    //refreshidparent();
    resetform();

}


$(".btnBatal").click(function () {
    resetform();
});

$(".addPage").click(function () {
    tambahForm();
});

//simpan data dari form modal 
$("#formweb").submit(function (e) {
    e.preventDefault();

    let formVal = $("#formweb").serialize();

    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/akun/simpan", formVal).done(function (vRet) {

            let bgclr = "bg-red";
            if (vRet.status) {
                bgclr = "bg-blue";
                if ($("#formweb #id").val() == "")
                    resetform();
                dtTable.ajax.reload(null, false);
                //dataTree();
                //refreshidparent();
            }
            showNotification(vRet.status, vRet.pesan);
        });
    }
    return false;
});

//fungsi menghapus data 
function fDelete(submitVal, vUrl) {
    if ($("#idPilih").val() !== "")
        if (confirm("Apakah anda yakin? data akan terhapus secara permanen...")) {
            appAjax(vUrl, submitVal).done(function (vRet) {
                if (vRet.status) {
                    dtTable.ajax.reload(null, false);
                    //dataTree();
                    //refreshidparent();
                    resetform();
                }
                showNotification(vRet.status, vRet.pesan);
            });
        }
}

//ganti / edit data  	
$(document).on("click", ".editRow", function (e) {
    e.preventDefault();
    resetform();
    let formVal = {
        vCari: {
            0: { val: $(this).data("id"), fld: "u.id", cond: "where" },
        }
    };

    appAjax("api/cariakun", formVal).done(function (dataLoad) {
        if (dataLoad.status) {
            let dtweb = dataLoad.db[0];
            //console.log(dtweb);
            /*
            let ids = [];
            let is_admin = JSON.parse(dtweb.is_admin);
            let is_mahasiswa = JSON.parse(dtweb.is_mahasiswa);
            let is_pembimbing = JSON.parse(dtweb.is_pembimbing);

            //console.log(is_admin);
            //console.log(is_mahasiswa);
            //console.log(is_pembimbing);

            if (is_admin.status == "1")
                ids.push(is_admin.idgrup);
            if (is_mahasiswa.status == "1")
                ids.push(is_mahasiswa.idgrup);
            if (is_pembimbing.status == "1")
                ids.push(is_pembimbing.idgrup);

            $('#idgrup').val(ids).trigger('change');
            */
            $('#kel').val(dtweb.kel).trigger('change');

            $("#check-password").show();
            $("#input-password").hide();

            $("#formweb #id").val(dtweb.id);
            $("#formweb #email").val(dtweb.email);
            $("#formweb #nik").val(dtweb.nik);
            $("#formweb #tmplahir").val(dtweb.tmplahir);
            $("#formweb #tgllahir").val(dtweb.tgllahir);
            $("#formweb #glrbelakang").val(dtweb.glrbelakang);
            $("#formweb #nama").val(dtweb.nama);
            $("#formweb #glrdepan").val(dtweb.glrdepan);
            $("#formweb #alamat").val(dtweb.alamat);

            var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();

        }
    });
});

//menghapus data dari tombol hapus datatables
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = {
        idTerpilih,
    }
    fDelete(submitVal, "app/fakultas/delete");
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
        fDelete(submitVal, "app/fakultas/delete");
    }
})

//tambahan - refresh datatables
$(".refreshData").click(function () {
    loadTabel();
});