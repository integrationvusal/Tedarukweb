<!doctype html>
<html>
<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <title>Printable version</title>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>

    <style type="text/css">
            /* http://meyerweb.com/eric/tools/css/reset/
       v2.0 | 20110126
       License: none (public domain)
    */

            /* Reset styles start */

        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
            /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        /* Reset styles end */



        body{
            font-family: verdana;
            font-size: 13px;
            color: #333;
        }

        h2{
            font-size: 18px;
            text-align: center;
            margin:0 0 20px 0;
        }

        i{
            font-style: italic;
            color: #999999;
        }

        div.container{
            width: 900px;
            height: auto;
            min-height: 400px;
            border: 1px #ccc solid;
            margin: 20px auto;
            padding: 10px;;
            position: relative;
        }

        div.container p{
            margin: 5px 0;
            line-height: 25px;;
        }

        div.container b{
            font-weight: bold;
        }

        table.data_table{
            width: 100%;
            border: none;
        }

        table.data_table tr td ,table.data_table tr th {
            border: 1px #e0e0e0 solid;
            padding: 5px;
        }

        .zebra{
            background-color: #F0EDED;;
        }

        .table_title{
            background-color: #cccccc;
            font-weight: bold;
        }

        .logo{
            position: absolute;
            top: 5px;
            right: 5px;
            width: 225px;
            height: 66px;
            opacity: .3;
        }

    </style>
</head>

<body>
<?php


 if( isset($_POST['print_it']) )
 {

    $google_map = str_replace('https', 'http', stripslashes($_POST['google_map']));
 }
?>

<div class="container">


    <div class="logo">
        <img src="../../templates/default/images/new_logo.png" />
    </div>

     <h2>Дорожная квитанция N<?=$_POST['id']?></h2>
     <p><b>Имя получателя : </b> <?=$_POST['name']?> </p>
     <p><b>Фамилия : </b> <?=$_POST['lastname']?> </p>
     <p><b>Номер : </b> <?=$_POST['phone']?> </p>
     <p><b>Адрес : </b> <?=$_POST['address']?> </p>

     <?php
        $array_size = count($_POST['ordered_products']);
        $total_sum = $_POST['total_sum'];

        /*foreach($_POST['ordered_product_price'] as $each_price)
        {
            $total_sum += $each_price;
        }*/
    ?>
    <p><b>Заказанные продукты</b></p>
    <table class="data_table">
        <tr>
            <th>Название продукта</th>
            <th>Количество</th>
            <th>Цена</th>
        </tr>
            <?php
                for( $i=0; $i < $array_size; $i++  )
                {?>
                    <tr>
                        <td><?=$_POST['ordered_products'][$i]?></td>
                        <td style="text-align: center"><?=$_POST['ordered_product_count'][$i]?></td>
                        <td style="text-align: center"><?=$_POST['ordered_product_price'][$i]?> AZN</td>
                    </tr>
                <?}?>
        <tr>
            <td colspan="3"><b>Общая цена : <?=$total_sum?>  AZN</b></td>
        </tr>

    </table>




     <p><b>Примечание :  </b></p>
     <p><i><?=$_POST['notes']?></i></p>
     <p><b>Карта :  </b></p>
     <p><?php echo $google_map ?></p>



</div>





<script type="text/javascript" language="javascript">
    $(document).ready(function(){
        $('iframe').attr('width','900px');


        $('table.data_table tr:nth-child(2n+3)').addClass('zebra');
        $('table.data_table tr:eq(0)').addClass('table_title');
        $('table.data_table tr:last-child td').css({'padding':'10px' , 'backgroundColor':'#FFFFD7' , 'fontSize':'14px'});
    });// ready end

</script>


</body>

</html>


