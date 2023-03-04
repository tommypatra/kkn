var idoutput=null;
var idpenempatan=null;
var idkkn=null;


function outputkkn(idpenempatan){
    let cari = {
        0: { val: idpenempatan, fld: "pm.id", cond: "where" },
    };
    appAjax("api/outputkkn", cari).done(function (vRet) {
        $('.foutput').empty();
        if(vRet.status)
            $.each(vRet.db, function (k, v) {
                console.log(v);

                let btn_upload="";
                let btn_delete="";
                if(v.kettugas=="terbuka"){
                    btn_upload= "<a href='#' title='Upload "+v.output+"' class='btn btn-sm btn-success outputurl' data-idpenempatan='"+v.idpenempatan+"' data-idoutput='"+v.id+"'><i class='bi bi-globe'></i> <span style='font-size:13px;'>URL</span></a> &nbsp; "+
                                "<a href='#' title='Upload "+v.output+"' class='btn btn-sm btn-primary outputdok' data-idpenempatan='"+v.idpenempatan+"' data-idoutput='"+v.id+"'><i class='bi bi-cloud-upload'></i> <span style='font-size:13px;'>FILE</span></a>";
                    btn_delete="<a href='#' class='hapus-output' data-idoutput_penempatan='"+v.idoutput_penempatan+"'><i class='bi bi-trash3'></i></a>";
                }


                let detupload="";  
                let vurl="";
                let iconfile = "";
                if(v.idoutput_penempatan!=null){
                    vurl=vBase_url+"file/read/"+v.idoutput_penempatan+"/output_penempatan";
                    iconfile = "bi bi-filetype-pdf";
                    if(v.jenis=="url"){
                        vurl=v.path;
                        iconfile = "bi bi-globe";
                    }


                    detupload=  "<div class='mb-2'>"+
                                    "<a href='"+vurl+"' target='_blank'><i class='"+iconfile+"'></i> "+v.output+"</a>"+
                                    "<div style='font-size:12px;'><span class='badge bg-success'>"+v.waktu_upload+"</span> "+btn_delete+"</div>"
                                "</div>";
                }

                let html="<div class='row'>"+    
                            "<div class='col-1'>"+(k+1)+".</div>"+   
                            "<div class='col-6'><h6>"+v.output+"</h6>"+
                                "<div style='font-size:12px;font-style:italic'>"+v.keterangan+"</div>"+   
                            "</div>"+
                            "<div class='col-5' style='text-align:right'>"+btn_upload+" "+detupload+"</div>"+
                        "</div><hr>";
                $('.foutput').append(html);
            });

    });
}


$(document).on("click",".outputdok",function (e) {
    e.preventDefault();
    idoutput=$(this).data("idoutput");
    var myModal = new bootstrap.Modal(document.getElementById('fModalUpload'), {
            backdrop: 'static',
            keyboard: false,
    });
    myModal.toggle();
});


$(document).on("click",".outputurl",function (e) {
    e.preventDefault();
    idoutput=$(this).data("idoutput");
    var myModal = new bootstrap.Modal(document.getElementById('modal-upload-url'), {
            backdrop: 'static',
            keyboard: false,
    });
    myModal.toggle();
});

$("#formurl").submit(function (e) {
    e.preventDefault();
    if ($(this).validationEngine('validate'))
        if(confirm("apakah anda yakin simpan data?")) 
        {
            let formval={
                idkkn:idkkn,
                idoutput:idoutput,
                idpenempatan:idpenempatan,
                url:$("#url").val(),
            
            };
            appAjax("file/upload_output_url", formval).done(function(vRet) {
                if(vRet.status){
                    outputkkn(idpenempatan);
                }
                showNotification(vRet.status, vRet.pesan);
            });
        }
});

$(document).on("click",".upload-output",function (e) {
    e.preventDefault();
    idpenempatan=$(this).data("idpenempatan");
    idkkn=$(this).data("idkkn");
    outputkkn(idpenempatan);
    var myModal = new bootstrap.Modal(document.getElementById('modal-form-output'), {
            backdrop: 'static',
            keyboard: false,
    });
    myModal.toggle();
});

$(document).on("click",".hapus-output",function (e) {
    e.preventDefault();
    let formVal={
        "idupload":$(this).data("idoutput_penempatan"),  
        "table":"output_penempatan",  
    };
    if(confirm("Apakah anda yakin akan menghapus data secara permanen?")){
        appAjax("file/delete", formVal).done(function(vRet) {
            if (vRet.status) {
                outputkkn(idpenempatan);
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
    url: vBase_url + "file/upload_output", // Set the url
    maxFilesize: vMaxSizeImg,
    uploadMultiple: false,
    maxFiles: 1,
    thumbnailHeight: 80,
    thumbnailWidth: 80,
    parallelUploads: 20,
    acceptedFiles: '.pdf',
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
    //console.log(file);
    if (vRet.status) {
        outputkkn(idpenempatan);
    }
    showNotification(vRet.status,vRet.pesan);    
});

myDropzone.on("sending", function(file, xhr, formData) {
    formData.append("idkkn", idkkn);
    formData.append("idoutput", idoutput);
    formData.append("idpenempatan", idpenempatan);
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