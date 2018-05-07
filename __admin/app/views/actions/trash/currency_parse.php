<?php

$date = date("d.m.Y", time());
  //  $date = ''.$y.'.07.2013';
    $xml_url = 'http://cbar.az/currencies/'.$date.'.xml';

    if(!function_exists("simplexml_load_file")){
        require_once "simplexml.class.php";
        $xml_obj = new simplexml;
        $simple_xml_exist = false;
    } else {
        $simple_xml_exist = true;
    }
    $currency_data = simplexml_load_file($xml_url);

    if (!empty($currency_data)) {
        foreach ($currency_data->ValType as $item) {
            if ($item->attributes()->Type == 'Xarici valyutalar') {
                $currencies = array();
                foreach($item->Valute as $value){
                    $currencies[] = $value;
                }
            }
        }
        $rate = array();
        foreach ($currencies as $item) {
            $cur_name = (string)$item->attributes()->Code;
            $cur_value = round((float)$item->Value, 4);
            $rate[$cur_name] = $cur_value;
        }
    }

	$resultato = json_encode($rate);
	$fp = fopen('../../cashe/counter.txt', 'w+');
	$test = fwrite($fp, $resultato);