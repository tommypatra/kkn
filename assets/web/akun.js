sel2_jeniskelamin("#kel");

sel2_datalokal("#idkabupaten", null, false);
sel2_datalokal("#idkecamatan", null, false);
sel2_datalokal("#iddesa", null, false);
loadprofil();

let waktu = new Date(); 
let tgl_1 = waktu.getDate() + "-"+ (waktu.getMonth()+1)  + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-"+ (waktu.getMonth()+1)  + "-" + waktu.getDate();

$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
});


// --------------------SELECT 2------------------------
$("#idprovinsi").select2({
    minimumInputLength: 3,
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
                vField: "id,provinsi as text",
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
    cariwilayah("#idkecamatan", null, $("#idkabupaten").val(), null, false);
});

$('#idkecamatan').on('select2:select', function (e) {
    cariwilayah("#iddesa", null, null, $("#idkecamatan").val(), false);
});

function cariwilayah(vselector, idprovinsi = null, idkabupaten = null, idkecamatan = null, pilihdef = null) {
    if (idprovinsi) {
        vurl = "api/carigeneral";
        cari = {
            vTable: "wilayah_kab",
            vField: "id,kabupaten as text",
            vCari: {
                0: { val: idprovinsi, fld: "idprovinsi", cond: "where" },
            }
        };
    }else if (idkabupaten) {
        vurl = "api/carigeneral";
        cari = {
            vTable: "wilayah_kec",
            vField: "id,kecamatan as text",
            vCari: {
                0: { val: idkabupaten, fld: "idkabupaten", cond: "where" },
            }
        };
    }else if (idkecamatan) {
        vurl = "api/carigeneral";
        cari = {
            vTable: "wilayah_desa",
            vField: "id,desa as text",
            vCari: {
                0: { val: idkecamatan, fld: "idkecamatan", cond: "where" },
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

function loadprofil(){
    appAjax("akun/load", {}).done(function(vRet) {
        if(vRet.status){
            let data=vRet['db'][0];
            let time = (new Date()).getTime();

            $("#iduser").val(data.iduser);
            $("#email").val(data.email);
            $("#glrdepan").val(data.glrdepan);
            $("#glrbelakang").val(data.glrbelakang);
            $("#nama").val(data.nama);
            $("#hp").val(data.hp);
            $("#nik").val(data.nik);
            $("#tgllahir").val(data.tgllahir);
            $("#tmplahir").val(data.tmplahir);
            $("#alamat").val(data.alamat);
            $("#kel").val(data.kel).trigger("change");

            $("#pasfoto").attr("src",vBase_url+data.path);
                

            //load provinsi
            let vselector="#idprovinsi";
            $(vselector).empty();     
            var newOption = new Option(data.provinsi, data.idprovinsi, false, false);
            $(vselector).append(newOption).trigger('change');   
            //end load provinsi

            //load kabupaten
            vselector="#idkabupaten";
            cariwilayah(vselector, data.idprovinsi);
            $(vselector).val(data.idkabupaten).trigger('change');   

            //load kabupaten
            vselector="#idkecamatan";
            cariwilayah(vselector, null,data.idkabupaten);
            $(vselector).val(data.idkecamatan).trigger('change');   

            //load desa
            vselector="#iddesa";
            cariwilayah(vselector, null,null,data.idkecamatan);
            $(vselector).val(data.iddesa).trigger('change');   
        }
    });
}


$(".btn-resendemail").click(function(e) {
    e.preventDefault();
    if (confirm("kirim aktivasi akun sekarang?")) {
        appAjax("akun/resendemail", {}).done(function(vRet) {
            showNotification(vRet.status,vRet.pesan);    
        });
    }
});

//simpan jadwal
$("#formakun").submit(function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    if ($(this).validationEngine('validate')) {
        appAjax("akun/simpan", formVal).done(function(vRet) {
            showNotification(vRet.status,vRet.pesan);    
            if (vRet.status) {
                loadprofil();
            }
        });
    }
});

//bersihkan validasi
$(document).on("click keypress", "#formakun", function (e) {
    $(this).validationEngine('hideAll');
});


$(document).on("click",".btn-upload",function (e) {
    e.preventDefault();
    var myModal = new bootstrap.Modal(document.getElementById('fModalUpload'), {
            backdrop: 'static',
            keyboard: false,
    });
    myModal.toggle();
});


// DropzoneJS upload
Dropzone.autoDiscover = false

// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
var previewNode = document.querySelector("#template");
previewNode.id = ""
var previewTemplate = previewNode.parentNode.innerHTML
previewNode.parentNode.removeChild(previewNode)

var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: vBase_url + "file/upload_gambar_mhs", // Set the url
    maxFilesize: vMaxSizeImg,
    uploadMultiple: false,
    maxFiles: 1,
    thumbnailHeight: 80,
    thumbnailWidth: 80,
    parallelUploads: 20,
    acceptedFiles: 'image/*',
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
    init: function() {
        this.on("error", function(file, message) {
            alert(message);
            this.removeFile(file);
        });
    }
})

myDropzone.on("addedfile", function(file) {
    file.previewElement.querySelector(".start").onclick = function() {
        myDropzone.enqueueFile(file)
    }
})

// Update the total progress bar
myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
})

myDropzone.on("success", function(file, vRet) {
    const modalupload = document.querySelector('#fModalUpload');
    const modal = bootstrap.Modal.getInstance(modalupload);    
    modal.hide();
    
    //let vRet = jQuery.parseJSON(response);
    console.log(file);
    if (vRet.status) {
        $("#pasfoto").attr("src",file.dataURL);    
    }
    showNotification(vRet.status,vRet.pesan);    
});

myDropzone.on("sending", function(file, xhr, formData) {
    formData.append("multi", false);
    formData.append("table", "user");
    formData.append("fldid", "id");
    formData.append("berkas", "pasfoto");
    document.querySelector("#total-progress").style.opacity = "1"
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
})

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0";
})

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
}
document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
}
// DropzoneJS upload End
