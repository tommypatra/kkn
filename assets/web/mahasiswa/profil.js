$("#rowktm").hide();
sel2_datalokal("#idprodi", null, false);
loadprodi("#idprodi");

loadidentitas();

function loadidentitas(){
    appAjax("mahasiswa/profil/loadidentitas", {}).done(function (vRet) {
        if (vRet.status) {
            let data=vRet.db[0];
            //let ranid=Math.random()*1000;
            //var dt = new Date();
            //var time = dt.getHours() +""+ dt.getMinutes() +""+ dt.getSeconds();
            var time = (new Date()).getTime();
            //console.log(data);
            $(".btn-upload").data("iduser",data.iduser);
            
            $("#nama").val(data.nama);
            $("#nik").val(data.nik);
            $("#kel").val(data.kel);
            $("#email").val(data.email);
            $("#nim").val(data.nim);
            $("#idmahasiswa").val(data.idmahasiswa);
            $("#idhakakses").val(data.idhakakses);            
            $("#idprodi").val(data.idprodi).trigger("change");


            $("#fotoprofil").attr("src",vBase_url+data.pasfoto+"?v=10"+time);
            if(data.kartumahasiswa!==null)
                $("#kartumhs").attr("src",vBase_url+data.kartumahasiswa+"?v=10"+time);

            if(data.nim){
                $("#rowktm").show();
                $("#tombolform").remove();
                //$("#tombolform").hide();

                $("#persetujuan").prop("checked", true);
                $("#nim").prop("disabled",true);
                $("#idprodi").prop("disabled",true);
                $("#persetujuan").prop("disabled",true);
            }else{
                $("#tombolform").show();
                $("#tombolform").css('visibility', 'visible');
            }

            //if(data.kartumahasiswa===null || data.pasfoto===null){
              //  alert("OK");
               // $("#tombolform").show();
               // $("#tombolform").css('visibility', 'visible');
            //}

        }   
    });
}

//cari seluruh prodi dan push di select2
function loadprodi(vselector) {
    $(vselector).empty();
    appAjax("mahasiswa/profil/loadprodi", {}, false).done(function (vRet) {
        var newOption = new Option("", "", false, false);
        $(vselector).append(newOption).trigger('change');
        if (vRet.status) {
            $.each(vRet.db, function (k, v) {
                var newOption = new Option(v.prodi, v.idprodi, false, false);
                $(vselector).append(newOption).trigger('change');
            });
        }
    });
}

$(document).on("click",".btn-upload",function (e) {
    e.preventDefault();
    var myModal = new bootstrap.Modal(document.getElementById('fModalUpload'), {
            backdrop: 'static',
            keyboard: false,
    });
    myModal.toggle();
});


$("#formidentitas").submit(function (e) {
    e.preventDefault();
    /*
    let defimg="/reborn/assets/img/kartumhs.png";
    let parser = document.createElement('a');
    parser.href = $("#kartumhs").attr("src");
    console.log(parser.hostname);
    console.log(parser.pathname);
    if(defimg==parser.pathname){
        showNotification(false, ["Upload kartu mahasiswa terlebih dahulu"]);
    }
    */
    let nim=$("#nim").val();
    let nama=$("#nama").val();
    if ($(this).validationEngine('validate'))
        if(confirm("apakah anda yakin simpan data?")) 
        if(confirm("apakah mahasiswa atas nama "+nama+" nim "+nim+" telah benar?")) 
        if(confirm("data sudah tidak bisa diubah lagi, anda yakin?")) 
        {
            appAjax("mahasiswa/profil/simpan", $(this).serialize()).done(function(vRet) {
                if(vRet.status){
                    loadidentitas();
                }
                showNotification(vRet.status, vRet.pesan);
            });
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
        $("#kartumhs").attr("src",file.dataURL);    
    }
    showNotification(vRet.status,vRet.pesan);    
});

myDropzone.on("sending", function(file, xhr, formData) {
    formData.append("multi", false);
    formData.append("table", "mahasiswa");
    formData.append("fldid", "iduser");
    formData.append("idhakakses", $("#idhakakses").val());
    formData.append("berkas", "kartumahasiswa");
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