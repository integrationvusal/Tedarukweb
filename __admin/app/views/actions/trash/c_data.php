<?php
if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

$order = @$_GET['order'];
$sort = @$_GET['sort'];
$st = @$_GET['st'];

if (is_array($order)) {
    $order = array_shift($order);
}
if (is_array($sort)) {
    $sort = array_shift($sort);
}
if (is_array($st)) {
    $st = array_shift($st);
}

$order = htmlspecialchars($order);
$sort = htmlspecialchars($sort);
$st = htmlspecialchars($st);

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $sql = $pdo->query("DELETE FROM `details` WHERE `id` = " . $pdo->quote(intval($_GET['delete'])));
    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=c_data'</script>";
}?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Данные</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <?php
        if ($st == '' || $st == 0) {
            $st = 1;
        }
        $name_ord_sel = ($order == 'ASC') ? 'DESC' : 'ASC';
        $name_sort_sel = ($sort == 'name') ? 'position' : 'id';

        $query = $pdo->query("SELECT * FROM `details` ORDER BY `" . $name_sort_sel . "` " . $name_ord_sel);
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));

        if ($setir > 0) {?>
            <form name="menu_list" action="" method="POST">
                <table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
                    <thead>
                    <tr>
                        <th id="d_id">ID</th>
                        <th id="d_name">ФИО</th>
                        <th id="d_structure">Организация</th>
                        <th id="d_position">Позиция</th>
                        <th>Управление</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $link_sc = "index.php?page=c_data";
                    $count = 70;
                    $cnt = 100;
                    $rpp = $count;
                    $rad = 1;
                    if (isset($st)) {
                        $page = $st - 1;
                    } else {
                        $page = 0;
                    }

                    $links = $rad * 2 + 1;
                    $pages = ceil($setir / $rpp);
                    $start = $page - $rad;

                    if ($start > $pages - $links) {
                        $start = $pages - $links;
                    }
                    if ($start < 0) {
                        $start = 0;
                    }
                    $end = $start + $links;

                    if ($end > $pages) {
                        $end = $pages;
                    }
                    for ($j = $start; $j < $end; $j++) {
                        if ($j == $page) {
                            $sql = $pdo->query("SELECT * FROM `details` ORDER BY `" . $name_sort_sel . "` " . $name_ord_sel . " LIMIT " . ($count * $j) . " ," . ($count) . "");
                            $row = $sql->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($row as $row1) {
                                $name = $row1['surname'].' '.$row1['name'].' '.$row1['mname'];?>
                                <tr>
                                    <td width="5%"><?php echo $row1['id'];?></td>
                                    <td><?php echo $name;?></td>
                                    <td><?php echo $row1['structure_name'];?></td>
                                    <td><?php echo $row1['position'];?></td>


                                    <td width="12%">
                                        <a href="index.php?page=c_edit_data&id=<?php echo $row1['id']; ?>&rsp=<?php echo htmlspecialchars($st); ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/></a>
                                        <a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=c_data&st=<?php echo htmlspecialchars($st); ?>&order=<?php echo htmlspecialchars($order); ?>&sort=<?php echo htmlspecialchars($sort); ?>&delete=<?php echo $row1['id']; ?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete"/></a>
                                        <span class="loader" style="visibility: hidden;"><img src='templates/default/images/2.gif'></span>
                                    </td>
                                </tr>
                            <?php
                            } // menu list foreach end
                        }
                    }?>
                    </tbody>
                </table>
            </form>

            <div class="pagination">
                <?php
                if ($page > 0) {
                    echo "<a href=\"$link_sc&st=1\">« First</a><a href=\"$link_sc&st=" . ($page) . "\">« Previous</a>";
                }
                for ($i = $start; $i < $end; $i++) {
                    if ($i == $page) {
                        echo '<a href="#" class="number current">';
                    } else {
                        echo "<a class='number' href=\"$link_sc&st=" . ($i + 1) . "\">";
                    }
                    echo($i + 1);

                    if ($i == $page) {
                        echo "</b>";
                    } else {
                        echo "</a>";
                    }
                    if ($i != ($end - 1)) {
                        echo "";
                    }
                }
                if ($pages > $links && $page < ($pages - $rad - 1)) {
                    echo " ... <a href=\"$link_sc&st=" . ($pages) . "\">" . ($pages) . "</a>";
                }
                if ($page < $pages - 1) {
                    echo " <a  href=\"$link_sc&st=" . ($page + 2) . "\">Next »</a><a href=\"$link_sc&st=" . ($pages) . "\">Last »</a>";
                }
                ?>
            </div>

        <?php
        } else {
            echo '<div class="no_table_info">Информация отсутствует</div>';
        }?>
        <div class="clear"></div>

        <script type="text/javascript">
            $(document).ready(function () {
                var d_id = $('#d_id');          // set cookoe value equal to 0
                var d_name = $('#d_name');        // set cookoe value equal to 1
                var d_structure = $('#d_structure'); // set cookoe value equal to 2
                var d_position = $('#d_position');       // set cookoe value equal to 3

                d_id.click(function () {
                    $.cookie('cookie_menu_sort_row', '0', { expires: 7 });
                });

                d_name.click(function () {
                    $.cookie('cookie_menu_sort_row', '1', { expires: 7 });
                });

                d_structure.click(function () {
                    $.cookie('cookie_menu_sort_row', '2', { expires: 7 });
                });

                d_position.click(function () {
                    $.cookie('cookie_menu_sort_row', '3', { expires: 7 });
                });

                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [
                        [$.cookie('cookie_menu_sort_row'), 0]
                    ],
                    headers: {
                        4: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>
    </div>
</div>