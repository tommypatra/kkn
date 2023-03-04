refreshgrupuser();
dataTree();
refreshidparent();

$(document).on("keydown click", ".formweb", function (e) {
    $(this).validationEngine('hideAll');
});

var vMyTree = $("#tree").jstree({
    "core": {
        "data": {},
    },
    types: {
        "root": {
            "icon": "bi bi-folder2-open"
        },
        "child": {
            "icon": "bi bi-file-earmark"
        },
        "default": {
            "icon": "bi bi-file-earmark"
        }
    },
    plugins: ["search", "types"]
}).on("open_node.jstree", function (event, data) {
    data.instance.set_icon(data.node, "bi bi-folder2-open");
}).on("close_node.jstree", function (event, data) {
    data.instance.set_icon(data.node, "bi bi-folder2");
}).on("changed.jstree", function (event, data) {
    //let refMenu = $(".menu-navbar");
    if (data.selected.length) {
        $("#idpilih").val(data.selected[0]);
    }
});


function dataTree() {
    let formVal = {
        vCari: [
            { "cond": "like", "fld": "m.menu", "val": $("#fCari").val() },
            { "cond": "where", "fld": "m.idgrup", "val": $("#idgrup").val() },
        ],
    };
    appAjax("api/itemTree", formVal).done(function (vRet) {

        $('#tree').jstree(true).settings.core.data = vRet.listdata;
        $('#tree').jstree(true).refresh();

        vMyTree.bind("refresh.jstree", function (event, data) {
            vMyTree.jstree("open_all");
        });
    });
}

function refreshgrupuser() {
    let formVal = {};
    let dt_option = [];
    $('#idgrup').empty().trigger("change");
    appAjax("api/daftargrup", formVal, false).done(function (vRet) {
        //refresh data parent
        dt_option = [{ id: "", text: "" }];
        if (vRet.status)
            jQuery.each(vRet.db, function (i, val) {
                dt_option.push({ id: val['id'], text: val['nama_grup'] });
            });
        sel2_datalokal("#idgrup", dt_option);
    });
}

function refreshidparent() {
    let formVal = {
        vCari: [
            { "cond": "where", "val": "#", "fld": "m.link" },
            { "cond": "where", "fld": "m.idgrup", "val": $("#idgrup").val() },
        ],
    };
    //console.log(formVal);
    $('#idparent').empty().trigger("change");
    appAjax("api/itemTree", formVal, false).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.listdata, function (i, val) {
                if (val['link'] == "#") {
                    dataparent.push({ id: val['id'], text: val['text'] });
                }
            });
        }
        sel2_datalokal("#idparent", dataparent, true, "#modal-form-web");
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

function resetform() {
    $("#formweb #id").val("");
    $("#formweb #urut").val("0");
    $("#formweb #menu").val("");
    $("#formweb #link").val("#");
    $("#formweb #icon_list").val("far fa-circle nav-icon");
    $("#formweb #icon_right").val("");
    $('#formweb #show').val("y").trigger("change");
    $('#formweb #module').val("").trigger("change");
}

//menampilkan form modal
function tambahForm() {
    var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    refreshidparent();
    resetform();
}


$(".btnBatal").click(function () {
    resetform();
});

$(".addPage").click(function () {
    tambahForm();
});

$("#idgrup").change(function (e) {
    e.preventDefault();
    if ($(this).val() != "") {
        $("#menu-web").show();
    } else {
        $("#menu-web").hide();
    }
    dataTree();
});

//simpan data dari form modal 
$("#formweb").submit(function (e) {
    e.preventDefault();
    let formVal = {
        id: $("#formweb #id").val(),
        idgrup: $("#idgrup").val(),
        urut: $("#formweb #urut").val(),
        menu: $("#formweb #menu").val(),
        link: $("#formweb #link").val(),
        icon_list: $("#formweb #icon_list").val(),
        idparent: $("#formweb #idparent").val(),
        module: $("#formweb #module").val(),
        icon_right: $("#formweb #icon_right").val(),
        show: $("#formweb #show").val(),
    };
    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/menu/simpan", formVal).done(function (vRet) {

            let bgclr = "bg-red";
            if (vRet.status) {
                bgclr = "bg-blue";
                if ($("#formweb #id").val() == "")
                    resetform();
                $("#formweb #fnama").focus();
                dataTree();
                refreshidparent();
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
                    dataTree();
                    refreshidparent();
                    resetform();
                }
                showNotification(vRet.status, vRet.pesan);
            });
        }
}

$("#fCari").keypress(function (e) {
    if (e.which === 13) {
        dataTree();
        refreshidparent();
    }
});

//ganti / edit data  	
$(".gantiTerpilih").click(function () {
    let idpilih = $("#idpilih").val();
    if (idpilih !== "") {
        let formVal = {
            vCari: {
                0: { val: idpilih, fld: "m.id", cond: "where" },
            }
        };

        appAjax("api/itemTree", formVal).done(function (dataLoad) {
            if (dataLoad.status) {
                let dtweb = dataLoad.db[0];
                //console.log(dtweb);
                $("#formweb #id").val(dtweb.id);
                $("#formweb #urut").val(dtweb.urut);
                $("#formweb #menu").val(dtweb.menu);
                $("#formweb #module").val(dtweb.module);
                $("#formweb #link").val(dtweb.link);
                $("#formweb #icon_list").val(dtweb.icon_list);
                $("#formweb #icon_right").val(dtweb.icon_right);
                $('#formweb #show').val(dtweb.show).trigger("change");
                $('#formweb #idparent').val(dtweb.idparent).trigger("change");

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
$(".hapusTerpilih").click(function () {
    let idTerpilih = [$("#idpilih").val()];
    let submitVal = {
        idTerpilih,
    }
    fDelete(submitVal, "app/menu/delete");
});

//tambahan - refresh datatables
$(".refreshData").click(function () {
    refreshidparent();
});

sel2_aktif("#show", "#modal-form-web");
