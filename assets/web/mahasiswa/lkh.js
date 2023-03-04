var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();

if($('#uraian').length){
    var quill = new Quill('#uraian', {
        //modules: {
            //syntax: true,
            //toolbar: '#editorberita'
        //},
        placeholder: 'uraian kegiatan anda disini...',
        theme: 'snow'    
    });    
}

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    format: 'YYYY-MM-DD HH:mm:ss',
    time: true,
});

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
            "url": vBase_url + "mahasiswa/lkh/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idpenempatan = $("#idpenempatan").val();
                d.ketkkn = $("#ketkkn").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            {
                extend: 'print',
                title: $("#titleweb").val(),
                exportOptions: {
                    stripHtml : false,
                    columns: [1,2,3,4,5]
                }
            },
            /*
            {
                extend: 'excelHtml5',
                title: $("#titleweb").val(),
                exportOptions: {
                    stripHtml : false,
                    columns: [1,2,3,4,5]
                }
            },
            {
                extend: 'pdfHtml5',
                title: $("#titleweb").val(),
                exportOptions: {
                    stripHtml : false,
                    columns: [1,2,3,4,5]
                }
            },
            */
            //'colvis'        
        ],


        "order": [
            [5, "asc"],
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
            "width": "70%",
        },
        {
            "data": "estbiaya",
            "width": "10%",
        },
        {
            "data": "foto",
            "width": "10%",
        },
        {
            "data": "waktu",
            "width": "10%",
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "jummhs",
            "visible": false,
        },
        {
            "data": "jummasyarakat",
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
    let formVal = {
        vTable: "aktifitas",
        vOrder: "waktu ASC",
        vField: "*",
        vCari: {
            0: { val: $(this).data("id"), fld: "id", cond: "where" },
        },
    };
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        if (vRet.status) {
            var myModal = new bootstrap.Modal(document.getElementById('modal-form'), {
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
    fDelete(submitVal, "mahasiswa/lkh/delete");
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
        fDelete(submitVal, "mahasiswa/lkh/delete");
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
function loaddata(db) {
    resetform();
    $("#id").val(db['id']);
    $("#uraian .ql-editor").html(db['uraian'])
    $("#waktu").val(db['waktu']);
};

//bersihkan validasi
$(document).on("click keypress", "#formweb input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan jadwal
$("#formweb").submit(function (e) {
    e.preventDefault();
    let formVal= {
        idpenempatan: $('#idpenempatan').val(),
        waktu: $('#waktu').val(),
        id: $("#id").val(),
        uraian: $("#uraian .ql-editor").html(),
    };
    if ($(this).validationEngine('validate')) {
        appAjax("mahasiswa/lkh/simpan", formVal).done(function (vRet) {
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
    $("#formweb")[0].reset();
    $("#uraian .ql-editor").html("")
}