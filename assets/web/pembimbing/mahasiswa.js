var dtTable = null;
let waktu = new Date();
sel2_tahun("#tahun");
$("#tahun").val(waktu.getFullYear()).trigger("change");

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
            "url": vBase_url + "pembimbing/mahasiswa/read",
            "dataType": "json",
            "type": "POST",
            "data": function(d) {
                d.tahun = $("#tahun").val();
            },    
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,6],
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,6],
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0,1,2,3,4,6],
                }
            },        
        ],        
        "order": [
            [8, "desc"],
            [9, "asc"],
            [10, "asc"],
            [11, "asc"],
            [12, "asc"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false,
            "width": "3%",
        },
        {
            "data": "tema",
            "width": "10%",
        },
        {
            "data": "kelompok",
            "width": "20%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "datamhs",
            "width": "30%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "prodi",
            "width": "10%",
        },
        {
            "data": "nilai_angka",
            "width": "10%",
        },        
        {
            "data": "nilai_akhir",
            visible: false,        
        },
        {
            "data": "tahun",
            visible: false,        
        },
        {
            "data": "kecamatan",
            visible: false,        
        },
        {
            "data": "desa",
            visible: false,        
        },
        {
            "data": "urut",
            visible: false,
        },
        {
            "data": "nama",
            visible: false,        
        },
        {
            "data": "nim",
            visible: false,       
        },
        {
            "data": "prodi",
            visible: false,
        },
        {
            "data": "jabatan",
            visible: false,
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

var nilailama=null;
$(document).on("focusin",".nilaimhs",function (e){
    nilailama=$(this).val();
});

$(document).on("blur",".nilaimhs",function (e){
    updatenilai($(this).data("idnilai"),$(this).data("idpenempatan"),$(this).data("idmst_penilaian"),$(this).val());
});

$(document).on("keyup",".nilaimhs",function (e){
    if(e.keyCode == 13){
        updatenilai($(this).data("idnilai"),$(this).data("idpenempatan"),$(this).data("idmst_penilaian"),$(this).val());
    };    
});
function updatenilai(idnilai,idpenempatan,idmst_penilaian,nilai){
    if(nilai!=nilailama){
        var formData = {
                "idnilai":idnilai,
                "idpenempatan":idpenempatan,
                "idmst_penilaian":idmst_penilaian, 
                "nilai_angka":nilai,
        }                
    
        if (nilai>=0 && nilai<=100) {
            appAjax("pembimbing/mahasiswa/simpan", formData).done(function(vRet) {
                showNotification(vRet.status,vRet.pesan);    
                if (vRet.status) {
                    nilailama=nilai;
                    if (dtTable)
                        dtTable.ajax.reload(null, false);
                }
            });
        }else{
            showNotification(false,["masukan nilai antara 0 sd 100"]);    
        }
    }
}    


//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

