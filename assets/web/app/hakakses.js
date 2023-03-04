var dtTable = null;

loadgrup();
loadTabel();

sel2_aktif("#c","#modal-form-web");
sel2_aktif("#r","#modal-form-web");
sel2_aktif("#u","#modal-form-web");
sel2_aktif("#d","#modal-form-web");
sel2_aktif("#f","#modal-form-web");

function loadgrup() {
    let formVal = {
        vTable: "grup as g",
        vOrder: "g.nama_grup ASC",
        vField: "g.*",
        vCari: {},
    };

    $('#idgrup').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['nama_grup'] });
            });
        }
        sel2_datalokal("#idgrup", dataparent);
    });
}

loadmodule();
function loadmodule() {
    let formVal = {
        vTable: "module as m",
        vOrder: "m.module ASC",
        vField: "m.*",
        vCari: {},
    };

    $('#idmodule').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['module'] });
            });
        }
        sel2_datalokal("#idmodule", dataparent, true, "#modal-form-web");
    });
}
//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//tambahan - mengecek semua ceklist
$("#idgrup").change(function () {
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
            "url": vBase_url + "app/hakakses/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idgrup = $("#idgrup").val();
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
            [2, "asc"],
            [3, "asc"],
            [4, "desc"],
            [5, "desc"],
            [6, "desc"],
            [7, "desc"],
            [8, "desc"],
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
            "data": "nama_grup",
            "width": "10%",
        },
        {
            "data": "module",
            "width": "40%",
        },
        {
            "data": "c",
            "width": "8%",
        },
        {
            "data": "r",
            "width": "8%",
        },
        {
            "data": "u",
            "width": "8%",
        },
        {
            "data": "d",
            "width": "8%",
        },
        {
            "data": "f",
            "width": "8%",
        },
        {
            "data": "ket",
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

function resetform(){
    $("#formweb #id").val("");
    $("#formweb #ket").val("");
    $('#formweb #c').val("y").trigger("change");
    $('#formweb #r').val("y").trigger("change");
    $('#formweb #u').val("y").trigger("change");
    $('#formweb #d').val("y").trigger("change");
    $('#formweb #f').val("y").trigger("change");
    $('#formweb #idmodule').val("").trigger("change");
}

//menampilkan form modal
function tambahForm() {
    if($("#idgrup").val()==""){
        showNotification(false,["Pilih grup terlebih dahulu"]);    
    }else{

        var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
        //refreshidparent();
        resetform();
    }
}


$(".btnBatal").click(function() {
    resetform();
});

$(".addPage").click(function() {
    tambahForm();
});

//simpan data dari form modal 
$("#formweb").submit(function(e) {
    e.preventDefault();
    let formVal = {
            id: $("#formweb #id").val(),
            idmodule: $("#formweb #idmodule").val(),
            idgrup: $("#idgrup").val(),
            c: $("#formweb #c").val(),
            r: $("#formweb #r").val(),
            u: $("#formweb #u").val(),
            d: $("#formweb #d").val(),
            f: $("#formweb #f").val(),
            ket: $("#formweb #ket").val(),
    };
    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/hakakses/simpan", formVal).done(function(vRet) {

            let bgclr = "bg-red";
            if (vRet.status) {
                bgclr = "bg-blue";
                if ($("#formweb #id").val() == "")
                    resetform();
                dtTable.ajax.reload(null, false);
                //dataTree();
                //refreshidparent();
            }
            showNotification(vRet.status,vRet.pesan);    
        });
    }
    return false;
});

//fungsi menghapus data 
function fDelete(submitVal, vUrl) {
    if ($("#idPilih").val() !== "")
        if (confirm("Apakah anda yakin? data akan terhapus secara permanen...")) {
            appAjax(vUrl, submitVal).done(function(vRet) {
                if (vRet.status) {
                    dtTable.ajax.reload(null, false);
                    //dataTree();
                    //refreshidparent();
                    resetform();
                }
                showNotification(vRet.status,vRet.pesan);    
            });
        }
}

//ganti / edit data  	
$(document).on("click",".editRow", function(e) {
    e.preventDefault();

    if($("#idgrup").val()==""){
        showNotification(false,["Pilih grup terlebih dahulu"]);    
    }else{
        let formVal = {
            vTable:"aksesgrup as g",
            vField:"g.*",
            vCari: {
                0:{ val: $(this).data("id"), fld: "g.id", cond: "where" },
            }
        };
            
        appAjax("api/carigeneral", formVal).done(function(dataLoad) {
            if (dataLoad.status) {
                let dtweb=dataLoad.db[0];
                //console.log(dtweb);

                $("#formweb #id").val(dtweb.id);
                $("#formweb #ket").val(dtweb.ket);
                $('#formweb #c').val(dtweb.c).trigger("change");
                $('#formweb #r').val(dtweb.r).trigger("change");
                $('#formweb #u').val(dtweb.u).trigger("change");
                $('#formweb #d').val(dtweb.d).trigger("change");
                $('#formweb #f').val(dtweb.f).trigger("change");
                $('#formweb #idmodule').val(dtweb.idmodule).trigger("change");
                        
                var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
                    backdrop: 'static',
                    keyboard: false,
                });
                myModal.toggle();
            
            }
        });
    }
});

//menghapus data dari tombol hapus datatables
$(document).on("click",".deleteRow", function(e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = {
        idTerpilih,
    }
    fDelete(submitVal, "app/hakakses/delete");
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
        fDelete(submitVal, "app/hakakses/delete");
    }
})

//tambahan - refresh datatables
$(".refreshData").click(function() {
    loadTabel();    
});

