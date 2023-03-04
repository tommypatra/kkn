  function sel2_datalokal(vselector,vdata=null,vAllowClear=true,vDropDownParent="",vTags=false){
    $(vselector).select2({
      data: vdata,
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
      allowClear: vAllowClear,
      tags:vTags,
    });
  }
  
  function sel2_jeniskelamin(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"L",text:"Laki-Laki"}, 
            {id:"P",text:'Perempuan'}],
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
      allowClear: true,
    });
  }
  
  function sel2_statuspeg(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"PNS",text:"PNS"}, 
            {id:"NON PNS",text:'NON PNS'}],
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
      allowClear: true,
    });
  }

  function sel2_semester(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"1",text:"GANJIL"}, 
            {id:"2",text:"GENAP"}],
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
      allowClear: true,
    });
  }

  function sel2_jeniskkn(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"REGULER",text:"REGULER"}, 
            {id:"PILIHAN",text:'PILIHAN'}],
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
      allowClear: false,
    });
  }

  function sel2_publish(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"1",text:"Ya"}, 
            {id:"0",text:'Tidak'}],
      dropdownAutoWidth: true,
      allowClear: false,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
    });
  }
  
  function sel2_aktif(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"y",text:"Ya"}, 
            {id:"n",text:'Tidak'}],
      dropdownAutoWidth: true,
      allowClear: false,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
    });
  }

  function sel2_ukuranupload(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"1000",text:"1Mb"}, 
            {id:"2000",text:'2Mb'},
            {id:"3000",text:'3Mb'},
            {id:"4000",text:'4Mb'},
            {id:"5000",text:'5Mb'},
            {id:"6000",text:'6Mb'},
            {id:"7000",text:'7Mb'}],
      dropdownAutoWidth: true,
      allowClear: false,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
    });
  }

  function sel2_jenisupload(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"pdf",text:"PDF"}, 
            {id:"doc",text:"Word"},
            {id:"xls",text:"Excel"},
            {id:"ppt",text:"Power Point"},
            {id:"img",text:"Image"}],
      dropdownAutoWidth: true,
      allowClear: false,
      dropdownParent: vDropDownParent,
      placeholder: '- pilih -',
    });
  }

  function sel2_tahun(vselector,vDropDownParent=""){
    let thn=new Date().getFullYear();
    let list_thn=[];
    let x=1;
    list_thn[0]={id:"",text:""};
    for(i=(thn);i>=(vTahunApp);i--){
      list_thn[x]={id:i,text:i};
      x++;
    }
    $(vselector).select2({
      data:list_thn,
      dropdownParent: vDropDownParent,
      dropdownAutoWidth: true,
      placeholder: '- pilih -',
      allowClear: true,
    });
  }