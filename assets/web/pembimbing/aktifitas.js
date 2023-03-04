var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();
let waktumysql = tgl_2+':'+waktu.getHours()+':'+waktu.getMinutes()+':'+waktu.getSeconds();


if($('#editoraktifitas').length){
    var quill = new Quill('#editoraktifitas', {
        placeholder: 'aktifitas DPL',
        theme: 'snow'    
    });    
}

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    format: 'YYYY-MM-DD HH:mm:ss',
    time: true,
});

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});


loadTabel();

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
            "url": vBase_url + "pembimbing/aktifitas/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idkelompok = $("#idkelompok").val();
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
            [3, "desc"],
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
            "data": "uraian",
            "width": "60%",
        },
        {
            "data": "waktu",
            "width": "10%",
        },
        {
            "data": "dokumentasi",
            "width": "25%",
        },
        {
            "data": "aksi",
            "width": "20%",
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
$(document).on("click", ".detailAnggota", function (e) {
    var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelompok'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
});

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

//reset nilai form
function resetform() {
    $("#id").val("");
    $("#forminput")[0].reset();
    $("#waktu").val(waktumysql);
    $(".ql-editor").html("");
    $("#imgpreview").html("");
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
        0: { val: $(this).data("id"), fld: "adp.id", cond: "where" },
    };
    appAjax("pembimbing/aktifitas/cari", cari).done(function (vRet) {
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

//bersihkan validasi
$(document).on("click keypress", "#forminput input", function (e) {
    $(this).validationEngine('hideAll');
});


//simpan
$("#forminput").submit(function (e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('id', $("#id").val()); 
    formData.append('idkelompok', $("#idkelompok").val()); 
    formData.append('waktu', $("#waktu").val()); 
    formData.append('file', $('#path').get(0).files[0]); 
    formData.append('uraian', $(".ql-editor").html()); 
  

    //formVal=formVal+"&berita="+$(".ql-editor").html();
    if ($(this).validationEngine('validate')) {
        $.ajax({
            url : vBase_url+"pembimbing/aktifitas/simpan",
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
                resetform();
                showNotification(vRet.status, vRet.pesan);
            }
        });
    }
});


//untuk load data dari db
function loadoutput(db) {
    resetform();
    $("#id").val(db['id']);
    $("#waktu").val(db['waktu']);
    $(".ql-editor").html(db['uraian']);
    if(db['thumbnail']){
        let vimg=vBase_url+db['thumbnail']+"?"+Math.random()*100;
        $("#imgpreview").html("<img src='"+vimg+"' width='100%'>");
    }
};

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "pembimbing/aktifitas/delete");
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
        fDelete(submitVal, "pembimbing/aktifitas/delete");
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
