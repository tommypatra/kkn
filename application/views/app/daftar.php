<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta <?= $this->config->item("app_name") ?></title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">
    <link href="<?= base_url('assets/bootstrap.min.css') ?>" rel="stylesheet">
    <?php include(APPPATH . "views/importhead.php"); ?>
</head>

<body>
    <div class="mloading"></div>

    <div class="table-responsive">
        <table class="table table-striped table-sm table-data">
            <thead>
                <tr>
                    <th style="vertical-align: middle;" scope="col">No</th>
                    <th style="vertical-align: middle;" scope="col">Nama DPL</th>
                    <th style="vertical-align: middle;" scope="col">Mahasiswa</th>
                    <th style="vertical-align: middle;" scope="col">NIM</th>
                    <th style="vertical-align: middle;" scope="col">Prodi</th>
                    <th style="vertical-align: middle;" scope="col">HP</th>
                    <th style="vertical-align: middle;" scope="col">Email</th>
                    <th style="vertical-align: middle;" scope="col">Kelompok</th>
                    <th style="vertical-align: middle;" scope="col">Jabatan</th>
                    <th style="vertical-align: middle;" scope="col">Desa/ Kelurahan</th>
                    <th style="vertical-align: middle;" scope="col">Kecamatan</th>
                    <th style="vertical-align: middle;" scope="col">Kabupaten</th>
                    <th style="vertical-align: middle;" scope="col">Provinsi</th>
                    <th style="vertical-align: middle;" scope="col"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>


    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/') ?>jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/bootstrap.bundle.min.js') ?>"></script>
    <?php include(APPPATH . "views/importfooter.php"); ?>

    <script>
        var dtTable = null;
        loadTabel();

        //datatables, menampilkan data
        function loadTabel() {
            dtTable = $('.table-data').DataTable({
                "autoWidth": false,
                "bDestroy": true,
                "processing": false,
                "serverSide": true,
                "lengthMenu": [
                    [25, 250, 500, 1000, -1],
                    ["25", "250", "500", "1000", "Semua"]
                ],
                "ajax": {
                    "url": vBase_url + "web/read_peserta",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.idkkn = "<?= $datakkn['id'] ?>";
                        d.isadmin = 1;
                    },
                    "dataSrc": function(json) {
                        return json.data;
                    },
                },
                dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
                buttons: [
                    'copy', 'excel', 'print'
                ],
                "order": [
                    [7, "asc"],
                    [8, "asc"],
                    [3, "asc"],
                ],
                "columns": [{ //0
                        "data": "no",
                        "orderable": false,
                        "searchable": false
                    },
                    { //1
                        "data": "dpl",
                        "width": "20%",
                    },
                    { //2
                        "data": "nama",
                        "width": "20%",
                    },
                    { //3
                        "data": "nim",
                        "width": "10%",
                    },
                    { //4
                        "data": "prodi",
                    },
                    { //5
                        "data": "hp",
                    },
                    { //6
                        "data": "email",
                    },
                    { //7
                        data: "namakelompok",
                        type: "numeric-comma",
                    },
                    { //8
                        "data": "jabatan",
                    },
                    { //9
                        "data": "desa",
                    },
                    { //10
                        "data": "kecamatan",
                    },
                    { //11
                        "data": "kabupaten",
                    },
                    { //12
                        "data": "provinsi",
                    },
                ],
                initComplete: function(e) {
                    var api = this.api();
                    $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function(e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
                },
            });
        }

        $.fn.dataTable.ext.type.order['numeric-comma-pre'] = function(data) {
            return parseFloat(data.replace(/,/g, ''));
        };

        //fungsi refresh
        $(".refreshData").click(function() {
            dtTable.ajax.reload(null, false);
        });
    </script>
</body>

</html>