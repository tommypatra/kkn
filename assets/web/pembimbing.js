var dtTable = null;
let waktu = new Date();
let tgl_1 = waktu.getDate() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-" + (waktu.getMonth() + 1) + "-" + waktu.getDate();

$('.appcurrency').mask('000.000.000.000.000.000', {
    reverse: true
});

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
});

//inisiasi select2
sel2_tahun("#setuptahun");
sel2_datalokal("#iduser_pembimbing", null, false, "#modal-form-pembimbing");
sel2_datalokal("#idjadwalkkn", null, false);

$("#setuptahun").val(waktu.getFullYear()).trigger("change");
carijadwal($("#setuptahun").val());
loadTabel();
loadpembimbing();
//end inisiasi select2

$('#setuptahun').on('select2:select', function (e) {
    carijadwal($(this).val());
});


$('#idjadwalkkn').on('select2:select', function (e) {
    let id = $(this).val();
    loadSk();
    //loadTabel();
});

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//simpan sk-pembimbing
$("#formskpembimbing").submit(function (e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    formVal = formVal + "&idkkn=" + $("#idjadwalkkn").val();
    if ($(this).validationEngine('validate') && confirm("apakah anda yakin simpan data?")) {
        appAjax("app/pembimbing/simpansk", formVal).done(function (vRet) {
            showNotification(vRet.status, vRet.pesan);
            if (vRet.status) {
                loadSk();
            }
        });
    }
});

$(".btn-upload").click(function (e) {
    e.preventDefault();
    if ($("#idsk_pembimbing").val() != "") {
        var myModal = new bootstrap.Modal(document.getElementById('fModalUpload'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
    } else {
        showNotification(false, ["Simpan SK terlebih dahulu"]);
    }
});

// DropzoneJS upload
Dropzone.autoDiscover = false

// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
var previewNode = document.querySelector("#template");
previewNode.id = ""
var previewTemplate = previewNode.parentNode.innerHTML
previewNode.parentNode.removeChild(previewNode)

var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: vBase_url + "app/pembimbing/uploadsk", // Set the url
    thumbnailWidth: 80,
    maxFilesize: vMaxSize,
    uploadMultiple: false,
    maxFiles: 1,
    thumbnailHeight: 80,
    parallelUploads: 20,
    //acceptedFiles: 'image/*,application/pdf',
    acceptedFiles: 'application/pdf',
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
    init: function () {
        this.on("error", function (file, message) {
            alert(message);
            this.removeFile(file);
        });
    }
})

myDropzone.on("addedfile", function (file) {
    file.previewElement.querySelector(".start").onclick = function () {
        myDropzone.enqueueFile(file)
    }
})

// Update the total progress bar
myDropzone.on("totaluploadprogress", function (progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
})

myDropzone.on("success", function (file, response) {
    $('#fModalUpload').modal('toggle');
    let vRet = jQuery.parseJSON(response);
    if (vRet.status) {
        loadSk();
    }
    showNotification(vRet.status, vRet.pesan);
});

myDropzone.on("sending", function (file, xhr, formData) {
    formData.append("multi", false);
    formData.append("berkas", "skpembimbing");
    formData.append("idkkn", $("#idjadwalkkn").val());
    formData.append("idsk", $("#idsk_pembimbing").val());
    document.querySelector("#total-progress").style.opacity = "1"
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
})

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function (progress) {
    document.querySelector("#total-progress").style.opacity = "0";
})

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions .start").onclick = function () {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
}
document.querySelector("#actions .cancel").onclick = function () {
    myDropzone.removeAllFiles(true)
}
// DropzoneJS upload End


//----------------- Form SK Pembimbing -----------------------

//clear validatiaon form sk
$(document).on("click keypress", "#formskpembimbing input", function (e) {
    $(this).validationEngine('hideAll');
});

//clear form sk
function resetFormSk() {
    $("#formskpembimbing")[0].reset();
    $("#formskpembimbing input[type='hidden']").val("");
    $("#formskpembimbing #detupload").html("");
}

//load SK
function loadSk() {
    resetFormSk();
    let cari = {
        0: { val: $("#idjadwalkkn").val(), fld: "idkkn", cond: "where" },
    };
    appAjax("app/pembimbing/cari_sk", cari).done(function (vRet) {
        if (vRet.status) {
            $("#formskpembimbing #idsk_pembimbing").val(vRet.db[0]['id']);
            $("#formskpembimbing #sk_no").val(vRet.db[0]['sk_no']);
            $("#formskpembimbing #sk_tgl").val(vRet.db[0]['sk_tgl']);
            let fileupload = vBase_url + "file/read/" + vRet.db[0]['id'] + "/sk_pembimbing";
            if (vRet.db[0]['path'] !== null)
                $("#detupload").html("<a href='" + fileupload + "' target='_blank'>SK Pembimbing</a>");

            loadTabel();
        }
    });
}

//----------------- end Form SK Pembimbing -----------------------


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
            "url": vBase_url + "app/pembimbing/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idsk = $("#formskpembimbing #idsk_pembimbing").val();
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
            [4, "desc"],
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
            "width": "10%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "nama",
            "width": "40%",
        },
        {
            "data": "kel",
            "width": "5%",
        },
        {
            "data": "kontak",
            "width": "15%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "keterangan"
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
    let idsk_pembimbing = $("#idsk_pembimbing").val();
    if (idjadwal == "") {
        showNotification(false, ["Pilih KKN Terlebih dahulu"]);
    } else if (idsk_pembimbing == "") {
        showNotification(false, ["Belum ada SK Pembimbing"]);
    } else {
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-pembimbing'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
    }
});

//hapus 1 row dari datatable
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = { "idTerpilih": idTerpilih };
    fDelete(submitVal, "app/pembimbing/delete");
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
        fDelete(submitVal, "app/pembimbing/delete");
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
$(document).on("click keypress", "#formpembimbing input", function (e) {
    $(this).validationEngine('hideAll');
});



//simpan pembimbing
$("#formpembimbing").submit(function (e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    formVal = formVal + "&idsk=" + $("#idsk_pembimbing").val();
    if ($(this).validationEngine('validate') && confirm("apakah anda yakin simpan data?")) {
        appAjax("app/pembimbing/simpan", formVal).done(function (vRet) {
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
    $("#formpembimbing")[0].reset();
    $("#iddesa").val("").trigger("change");
    $("#keterangan").val("");
    //$("#keterangan").summernote("code", "");
}



//jadwal kkn di select2
function carijadwal(tahun) {
    let vselector = "#idjadwalkkn";
    let cari = {
        0: { val: tahun, fld: "tahun", cond: "where" },
    };
    appAjax("app/jadwal/cari", cari).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option(v.tema + ' (' + v.jenis + ')', v.id, false, false);
                $(vselector).append(newOption).trigger('change');
            });
        }
    });
}

//simpan sk-pembimbing
function loadpembimbing() {
    let vselector = "#iduser_pembimbing";
    let formVal = {
        //0: { val: null, fld: "a.id IS NOT NULL", cond: "where" },
    };
    appAjax("app/pembimbing/daftar", formVal).done(function (vRet) {
        $(vselector).empty();
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                console.log(v.nama+' '+v.id);
                if(v.id>0){
                    var newOption = new Option(v.nama, v.id, false, false);
                    $(vselector).append(newOption).trigger('change');
                }
            });
        }
    });
};

