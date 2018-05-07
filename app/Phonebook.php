<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Phonebook
{
    private static $table = 'phonebook';

    public static function all(){
		return  DB::select("SELECT * FROM ".self::$table." ORDER BY ordering");
    }

    public static function add($data){

    	DB::beginTransaction();

		try {

			$res = DB::select('SELECT id FROM '.self::$table.' WHERE  surname = :surname AND name = :name AND patronymic = :patronymic',
					['surname' => $_POST['user_surname'], 'name' => $_POST['user_name'], 'patronymic' => $_POST['user_patronymic'] ]);
			if(!$res){

				$lastID = DB::select('SELECT MAX(ordering) max FROM '.self::$table.' LIMIT 1');
				$lastID  =  $lastID?$lastID[0]['max']:0;

				$phones = [];

				foreach ($_POST['phone_number'] as $k=>$phone) {
					if(!empty($phone))
						$phones[] = $_POST['phone_format'][$k].' '.$phone;
				}

				DB::table(self::$table)->insert([
					'surname' => $_POST['user_surname'],
					'name' => $_POST['user_name'],
					'patronymic' => $_POST['user_patronymic'],
					'address' => $_POST['user_address'],
					'email' => $_POST['user_email'],
					'phones' => json_encode($phones),
					'website' => $_POST['user_website'],
					'ordering' => $lastID + 1,
				]);

		    	DB::commit();
		    	return true;
			}

			return false;

		} catch (Exception $e) {
		    DB::rollback();
		    throw $e;
		}

		return false;
    }

}
