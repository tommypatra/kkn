<html>

<head>
    <title>Pendaftar KKN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script>
        var vBase_url = '<?= base_url() ?>';
        var vTimeout = 10000;
    </script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Data Pendaftar/Peserta KKN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Refresh</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <table id='pendaftar' width='100%' class='table table-hover'>

        <head>
            <tr>
                <th>No</th>
                <th>Fakultas</th>
                <th>Prodi</th>
                <th>Laki-Laki</th>
                <th>Perempuan</th>
                <th>Jumlah</th>
            </tr>
        </head>

        <body>
        </body>
    </table>
</body>
<script src="https://kkn.iainkendari.ac.id/v2/assets/plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="https://kkn.iainkendari.ac.id/v2/assets/myapp.js"></script>
<script>
    var vidkkn = '<?= $idkkn ?>';
    var vstatusmhs = '<?= $statusmhs ?>';
    loadStatistik();

    function loadStatistik() {
        let formVal = {
            idkkn: vidkkn,
            statusmhs: vstatusmhs
        };
        appAjax("app/pendaftar/getStatistik", formVal).done(function(vRet) {
            setData(vRet.db, "#pendaftar");
        });
    };

    function setData(vData, vTabel) {
        let jumPria = 0;
        let jumWanita = 0;
        let jumTotal = 0;
        $.each(vData, function(i, item) {
            jumPria = (jumPria + parseInt(item.pria));
            jumWanita = (jumWanita + parseInt(item.wanita));
            jumTotal = (jumTotal + parseInt(item.jumlah));
            $(vTabel + ' > tbody:last-child').append(
                '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + item.fakultas + '</td>' +
                '<td>' + item.prodi + '</td>' +
                '<td>' + item.pria + '</td>' +
                '<td>' + item.wanita + '</td>' +
                '<td>' + item.jumlah + '</td>' +
                '</tr>'
            );
        });
        $(vTabel + ' > tbody:last-child').append(
            '<tr style="font-weight:bold;">' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td>' + jumPria + '</td>' +
            '<td>' + jumWanita + '</td>' +
            '<td>' + jumTotal + '</td>' +
            '</tr>'
        );
    }
</script>

</html>