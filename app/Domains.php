<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Storage, Crypt;

class Domains extends Model{
	private $code;
    protected $table = 'domains';
    public function __construct(array $attributes = array()){
         if (file_exists(storage_path('app/.code'))) {
            try {
                $this->code = json_decode(Crypt::decryptString(Storage::get('.code')));
            } catch (\Exception $e) {
                $this->code = null;
            }
         }
    }
    public static function insert($requests){
    	$domain = new Domains;
    	if (is_object($domain->code) && $domain->code->license == 'Extended License') {
    		DB::table('domains')->insert($requests);
    	}
    }
}
