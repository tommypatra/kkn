// Function exec ajax
var vBase_url = "https://kkn.iainpare.ac.id/";
var vTimeout = 10000;

function appAjax(vurl, vdata, vasync = true) {
  //var vretval = { status: false, pesan: "" };
  return $.ajax({
    url: vBase_url + vurl,
    data: vdata,
    timeout: vTimeout,
    type: "post",
    dataType: "json",
    async: vasync,
    success: function (vRet) {
      vretval = vRet;
    },
    error: function (request, status, error) {
      //console.log(error);
      alert("Terjadi kesalahan, periksa koneksi internet anda dan coba lagi");
    },
  });
  //return vretval;
}

function appAjaxData(vUrl, vdata) {
  let retVal;
  $.ajax({
    url: vBase_url + vUrl,
    timeout: vTimeout,
    data: vdata,
    type: "post",
    dataType: 'json',
    async: false,
    success: function (vRet) {
      retVal = vRet;
    },
    error: function (request, status, error) {
      //console.log(error);
      if (request.responseText == "undefined")
        alert("Terjadi kesalahan, periksa koneksi internet anda dan coba lagi");
      else
        alert(request.responseText);
    },
  });
  return retVal;
}

$(".goUrl").click(function (event) {
  if (confirm("Apakah anda yakin ?")) {
    var url = $(this).attr("data-url");
    window.location.href = url;
  }
});

function convDate(vTgl, vTo = "YMD") {
  let tmpTgl = vTgl;
  if (vTo == "YMD") tmpTgl = moment(vTgl, "DD-MM-YYYY").format("YYYY-MM-DD");
  else if (vTo == "DMY")
    tmpTgl = moment(vTgl, "YYYY-MM-DD").format("DD-MM-YYYY");
  return tmpTgl;
}

function showNotification(vStatus, vPesan) {
  //generate pesan
  let vIcon = "success";
  let vTitle = "Berhasil";
  if (!vStatus) {
    vIcon = "error";
    vTitle = "Terjadi Kesalahan...";
  }
  let pesan = "";
  $.each(vPesan, function (key, value) {
    pesan += value;
    if (key + 1 < vPesan.length)
      pesan += ",";
    pesan += "<br>";
  });

  Swal.fire({
    icon: vIcon,
    title: vTitle,
    html: pesan,
  })
}

function useraktif() {
  let formVal = {};
  let label1 = "<i class='bi bi-activity'></i> 0";
  let label2 = "<span style='font-size:16px'>user</span>";

  appAjax("api/useraktif", formVal).done(function (vRet) {
    if (vRet.status) {
      label1 = "<i class='bi bi-activity'></i> " + vRet.jumlah;
    }
    $("#jum-user-aktif").html(label1 + " " + label2);
  });
}

$(document).on("click",".tambah-like",function(e) {
  e.preventDefault();
  let formVal = {
      iduser:$(this).data('iduser'),
      idaktifitas:$(this).data('idaktifitas'),
  };
  if(confirm("yakin kita mau kasi like? tidak bisami dibatalkan itu...")){
      appAjax("dashboard/simpan_like", formVal).done(function(vRet) {        
          showNotification(vRet.status, vRet.pesan);
          if(vRet.status)
              refreshlkh();
      });    
  }
});
