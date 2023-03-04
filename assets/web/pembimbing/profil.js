sel2_statuspeg("#statuspeg");

loadidentitas();

function loadidentitas(){
    appAjax("pembimbing/profil/loadidentitas", {}).done(function (vRet) {
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
            $("#nip").val(data.nip);
            $("#idpembimbing").val(data.idpembimbing);
            $("#idhakakses").val(data.idhakakses);
            $("#statuspeg").val(data.statuspeg).trigger("change");


            $("#fotoprofil").attr("src",vBase_url+data.pasfoto+"?v=10"+time);

            /*
            if(data.nip){

                $("#persetujuan").prop("checked", true);
                $("#nip").prop("disabled",true);
                $("#statuspeg").prop("disabled",true);
                $("#persetujuan").prop("disabled",true);
            }else{
                $("#tombolform").show();
                $("#tombolform").css('visibility', 'visible');
            }
            */

            $("#tombolform").show();
            $("#tombolform").css('visibility', 'visible');

        }   
    });
}


$("#formidentitas").submit(function (e) {
    e.preventDefault();
    if ($(this).validationEngine('validate'))
        if(confirm("apakah anda yakin simpan data?")) 
        {
            appAjax("pembimbing/profil/simpan", $(this).serialize()).done(function(vRet) {
                if(vRet.status){
                    loadidentitas();
                }
                showNotification(vRet.status, vRet.pesan);
            });
        }
});