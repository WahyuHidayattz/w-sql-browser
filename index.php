<?php include 'config.php' ?>
<?php
$query = '';
$data = false;
$date_init  = date('ym', strtotime("-1 days"));
$date_init_detail  = date('ymd', strtotime("-1 days"));
$server = 'Server 225';
$file_name = 'storage/' . getIpAddress() . "/" . $config->export_file_name;
$list_connection = $config->list_connection;
$obj_key = 0;
$con_name = '';
if (isset($_POST['submit'])) {
    $query = $_POST['input_query'];
    $server = $_POST['input_server'];
    $obj_key = array_search($server, array_column($list_connection, "name"));
    $con_name = $list_connection[$obj_key]->name;
    $con_host = $list_connection[$obj_key]->host;
    $con_user = $list_connection[$obj_key]->user;
    $con_pass = $list_connection[$obj_key]->pass;
    $con_db = $list_connection[$obj_key]->db;
    $koneksi = mysqli_connect($con_host, $con_user, $con_pass, $con_db);
    $data = query($koneksi, $query);
    if ($data) {
        $header = array_keys($data[0]);
        $exported = $data;
        $total_data = count($data);
        $max_render = 1000 > $total_data ? $total_data : 1000;
        array_unshift($exported, $header);
        writeCSV($exported, $file_name);
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/app.css">
    <link rel="stylesheet" href="assets/jquery/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/tablesorter/css/filter.formatter.min.css">
    <script src="assets/jquery/jquery-ui.min.js"></script>
    <script src="assets/jquery/external/jquery/jquery.js"></script>
    <script src="assets/tablesorter/js/jquery.tablesorter.min.js"></script>
    <script src="assets/tablesorter/js/jquery.tablesorter.widgets.min.js"></script>
    <link rel=" shortcut icon" type="image/png" href="assets/favicon.png" />
    <title>W-Sql (Debug Mode)</title>
</head>

<body>

    <div class="flex flex-col w-full max-h-screen min-h-screen text-sm text-gray-500 bg-gray-50">
        <!-- navbar -->
        <div class="flex flex-col gap-2 py-2 bg-gray-100 container-w">
            <div class="flex flex-row items-center justify-between">
                <span class="text-base text-gray-600">W-Sql Browser <span class="text-xs text-gray-400">(Versi 1.0)</span></span>
            </div>
            <form action="" method="POST" class="flex flex-row gap-4 ">
                <textarea name="input_query" rows="3" class="w-full px-2 py-1 bg-white border border-gray-300 outline-none focus:border-green-500 focus:ring focus:ring-green-200" placeholder="Masukan query jossmu disini..."><?= $query; ?></textarea>
                <div class="flex flex-col items-center gap-1">
                    <button name="submit" class="flex flex-col h-full p-3 text-white bg-green-500 rounded-md shadow-md hover:bg-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </button>
                    <span class="text-xs">Eksekusi</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <button name="reset" class="flex flex-col h-full p-3 text-white bg-red-500 rounded-md shadow-md hover:bg-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                    </button>
                    <span class="text-xs">Reset</span>
                </div>
                <div class="flex flex-col h-auto gap-1 bg-gray-300 bg-[url('squid.png')] bg-cover bg-center items-end justify-end shadow-md">
                    <select name="input_server" class="px-2 py-1 text-xs text-white outline-none bg-gray-800/60">
                        <?php foreach ($list_connection as $list) : ?>
                            <option <?= $con_name == $list->name ? 'selected' : ''; ?>><?= $list->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
        <!-- toolbar -->
        <div class="flex gap-4 py-1 bg-gray-100 border-t border-b container-w md:flex-row md:items-center md:justify-between border-t-gray-200 border-b-gray-200">
            <div class="flex-row flex-wrap items-center hidden gap-2 md:flex">
                <span class="font-semibold">Hasil : </span>
                <?php if ($data) : ?>
                    <span class="badge">Render : <?= $max_render; ?></span>
                    <span class="badge">Total Data : <?= $total_data; ?> </span>
                <?php endif; ?>
            </div>
            <div class="flex flex-row items-center w-full gap-2 md:w-auto md:flex-wrap">
                <?php if ($data) : ?>
                    <button onclick="selectElementContents( document.getElementById('table') );" class="px-2 py-1 text-gray-600 bg-gray-200 whitespace-nowrap">Seleksi Tabel</button>
                    <a href="<?= $file_name; ?>" class="px-2 py-1 text-white bg-gray-800 shadow-md hover:bg-gray-700 whitespace-nowrap">Download CSV</a>
                    <input type="text" id="input_search" class="w-full px-2 py-1 bg-white border border-gray-300 outline-none md:w-auto focus:border-green-500 focus:ring focus:ring-green-200" placeholder="Cari Data..">
                <?php endif ?>
            </div>
        </div>
        <!-- contents -->
        <div class="flex flex-col flex-grow mt-4 overflow-auto container-w">
            <div class="flex flex-col flex-grow w-full overflow-auto">
                <?php if ($data) : ?>
                    <table id="table" class="w-auto text-xs text-gray-800 border border-gray-200 table-auto font-palanquin ">
                        <thead class="sticky top-0">
                            <tr class="text-xs text-gray-800 bg-gray-300 border-b border-b-gray-200">
                                <th class="p-2 text-xs text-left whitespace-nowrap hover:cursor-pointer border-b-gray-400 hover:underline">No</th>
                                <?php foreach ($data[0] as $k => $d) : ?>
                                    <th class="p-2 text-xs text-left whitespace-nowrap hover:cursor-pointer border-b-gray-400 hover:underline"><?= $k; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <?php for ($i = 1; $i <= $max_render; $i++) : ?>
                                <tr class="text-xs border-b border-b-gray-200 hover:bg-gray-200">
                                    <td class="px-2 py-1.5 border-r border-r-gray-200 text-xs whitespace-nowrap"><?= $i; ?></td>
                                    <?php foreach ($data[$i - 1] as $v) : ?>
                                        <td class="px-2 py-1.5 border-r border-r-gray-200 text-xs whitespace-nowrap"><?= $v; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endfor ?>

                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <span class="flex items-center justify-center w-full px-4 py-2 text-xs text-center text-gray-500 container-w">Created by wahyu hidayat</span>
        </div>
    </div>

</body>

<script>
    function selectElementContents(el) {
        var body = document.body,
            range, sel;
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }
        } else if (body.createTextRange) {
            range = body.createTextRange();
            range.moveToElementText(el);
            range.select();
        }
    }

    $(document).ready(function() {
        $("#input_search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table_body tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    $(function() {
        $("#table").tablesorter({
            cancelSelection: false
        });
    });
</script>

</html>