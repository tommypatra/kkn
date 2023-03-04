/*
$('.editorweb').summernote({
    height: 120,
    toolbar: [
        ['all', ['bold', 'italic', 'underline', 'strikethrough', 'ul', 'ol', 'link']]
    ],
});
*/
var idkkn=null;
var idpendaftar=null;
var idadministrasi=null;

sel2_semester("#semester");
sel2_tahun("#tahun");
sel2_jeniskkn("#jenis");

sel2_semester("#flt_semester");
sel2_tahun("#flt_tahun");
sel2_jeniskkn("#flt_jenis");


let waktu = new Date(); 
let tgl_1 = waktu.getDate() + "-"+ (waktu.getMonth()+1)  + "-" + waktu.getFullYear();
let tgl_2 = waktu.getFullYear() + "-"+ (waktu.getMonth()+1)  + "-" + waktu.getDate();

//mendaftar pada KKN yang tersedia
$(".daftar").click(function(e) {
    e.preventDefault();
    let formVal={
        "idkkn":$(this).data("idkkn"),
        "id":$(this).data("id"),
    };
    if(confirm("Apakah anda yakin akan mendaftar pada KKN ini?")){
        $(":button").attr("disabled","true");
        appAjax("mahasiswa/kkn/daftar", formVal).done(function(vRet) {
            if(vRet.status){
                alert(vRet.pesan);
                window.location.replace(vBase_url+"mahasiswa/kkn");
            }else{
                //$(":button").attr("disabled","false");    
                alert(vRet.pesan);
                location.href = vBase_url+'mahasiswa/kkn';
            }
        });        
    }
});

//refresh upload berkas
function loadberkas(){
    let formVal={
        "idkkn":idkkn,
        "idpendaftar":idpendaftar,
    };
    appAjax("api/berkaspendaftar_administrasi", formVal).done(function(vRet) {
        if(!$('#modal-form-kelengkapan').is(':visible')){
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelengkapan'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
        }
        $(".fkelengkapan").html(vRet.berkas_html);
    });
}

/*
$('#modal-form-kelengkapan').on('hidden.bs.modal', function () {
    // do something…
    //status-berkas-final
    let formVal={
        "idkkn":idkkn,
        "idpendaftar":idpendaftar,
    };
    appAjax("api/berkaspendaftar_administrasi", formVal).done(function(vRet) {
        if(!$('#modal-form-kelengkapan').is(':visible')){
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-kelengkapan'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
        }
        $(".fkelengkapan").html(vRet.html);
    });

})
*/

//batalkan pendaftaran
$(".batalkan").click(function(e) {
    e.preventDefault();
    let formVal={
        idkkn:$(this).data("idkkn"),
        idpendaftar:$(this).data("idpendaftar"),
    };

    if(confirm("apakah anda yakin membatalkan pendaftaran? silahkan terlebih dahulu menghapus lampiran dokumen pada KKN ini")){
        appAjax("mahasiswa/kkn/batalpendaftaran", formVal).done(function(vRet) {
            if (vRet.status) {
                location.href = vBase_url+'mahasiswa/kkn';
            } 
            showNotification(vRet.status,vRet.pesan);    
        });        
    }
    //loadberkas();
});

//modal kelengkapan
$(".kelengkapan").click(function(e) {
    e.preventDefault();
    idkkn=$(this).data("idkkn");
    idpendaftar=$(this).data("idpendaftar");
    loadberkas();
});

$(document).on("click",".btn-upload",function (e) {
    e.preventDefault();
    idadministrasi=$(this).data("idadministrasi");
    var myModal = new bootstrap.Modal(document.getElementById('fModalUpload'), {
            backdrop: 'static',
            keyboard: false,
    });
    myModal.toggle();
});

$(document).on("click",".btn-hapus-upload",function (e) {
    e.preventDefault();
    let formVal={
        "idupload":$(this).data("idupload"),  
    };
    if(confirm("Apakah anda yakin akan menghapus data secara permanen?")){
        appAjax("mahasiswa/kkn/berkas_delete", formVal).done(function(vRet) {
            if (vRet.status) {
                loadberkas();
            }        
            showNotification(vRet.status,vRet.pesan);    
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
    url: vBase_url + "mahasiswa/kkn/berkas_upload", // Set the url
    thumbnailWidth: 80,
    maxFilesize: vMaxSize,
    uploadMultiple: false,
    maxFiles: 1,
    thumbnailHeight: 80,
    parallelUploads: 20,
    acceptedFiles: 'application/pdf',
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

myDropzone.on("success", function(file, response) {
    const modalupload = document.querySelector('#fModalUpload');
    const modal = bootstrap.Modal.getInstance(modalupload);    
    modal.hide();

    let vRet = jQuery.parseJSON(response);
    if (vRet.status) {
        loadberkas();
    }

    showNotification(vRet.status,vRet.pesan);    
});

myDropzone.on("sending", function(file, xhr, formData) {
    formData.append("multi", false);
    formData.append("berkas", "berkaspendaftaran");
    formData.append("idkkn", idkkn);
    formData.append("idadministrasi", idadministrasi);
    formData.append("idpendaftar", idpendaftar);
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