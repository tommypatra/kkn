var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();
let waktumysql = tgl_2+':'+waktu.getHours()+':'+waktu.getMinutes()+':'+waktu.getSeconds();

sel2_publish("#publish");

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    format: 'YYYY-MM-DD HH:mm:ss',
    time: true,
});


//reset nilai form
function resetform() {
    $("#idupload").val("");
    $("#formupload")[0].reset();
    $("#waktu").val(waktumysql);
    $("#publish").val("").trigger("change");
}

loadTabel();

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
            "url": vBase_url + "app/upload/read",
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
            [6, "desc"],
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
            "data": "path",
            "width": "20%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "judul",
            "width": "35%",
        },
        {
            "data": "keterangan",
            "width": "20%",
        },
        {
            "data": "nama",
            "width": "20%",
        },
        {
            "data": "waktu",
            "width": "10%",
        },
        {
            "data": "publish",
            "width": "10%",
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

//end filter data
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
        0: { val: $(this).data("id"), fld: "f.id", cond: "where" },
    };
    appAjax("app/upload/cari", cari).done(function (vRet) {
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
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/upload/delete");
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
        fDelete(submitVal, "app/upload/delete");
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
    $("#idupload").val(db['idupload']);
    $("#judul").val(db['judul']);
    $("#waktu").val(db['waktu']);
    $("#publish").val(db['publish']).trigger("change");

};

//bersihkan validasi
$(document).on("click keypress", "#formoutput input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan
$("#formupload").submit(function (e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('idupload', $("#idupload").val()); 
    formData.append('judul', $("#judul").val()); 
    formData.append('waktu', $("#waktu").val()); 
    formData.append('publish', $("#publish").val()); 
    formData.append('keterangan', $("#keterangan").val()); 
    formData.append('file', $('#path').get(0).files[0]); 

    //formVal=formVal+"&berita="+$(".ql-editor").html();
    if ($(this).validationEngine('validate')) {
        $.ajax({
            url : vBase_url+"app/upload/simpan",
            type : "post",
            dataType : "json",
            data : formData,                         
            cache : false,
            contentType : false,
            processData : false,
            success : function(vRet){
                if(vRet.status){
                    dtTable.ajax.reload(null, false);
                }
                showNotification(vRet.status, vRet.pesan);
            }
        });
    }
});

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

