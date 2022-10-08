<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;


class smsController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * index
     * 
     * Last Update 2021-06-01 13:20:09
     * By Prasong putichanchai
     * 
     *  @return array
     */
    public function index(Request $Request)
    {
        $this->_setConfigDb();
        $pdo = '';
        $compact = [];

        try {
            $pdo = DB::connection()->getPdo();
            if ($pdo) {
                $Connect =  "Connected to database name : " . DB::connection()->getDatabaseName();
                $isConnect = true;      
            }

        } catch (\Exception $e) {
            //die("Could not connect to the database.  Please check your configuration. error:");
            $Connect = "Could not connect to the database.  Please check your configuration.";
            $isConnect = false;
        }

        $dataDecrypt = (object)[];
        $dataConfig = $this->_getConfigFile();
        if (isset($dataConfig)) {
            foreach ($dataConfig as $key => $value) {
                $dataDecrypt->$key = $this->_decrypted($value);
            }
        }

        $compact['connect'] =  $Connect;
        $compact['isConnect'] = $isConnect;
        $compact['config'] = $dataDecrypt;

        return view('_sms.index', (array) $compact);
        
    }

    function getTel(Request $Request){

        $this->_setConfigDb();
        $res = (object)[];
        $pdo = '';

        try {
            $pdo = DB::connection()->getPdo();
        
            if($Request->NumAtCard && $pdo){
                $response = DB::table('ORDR as a')
                ->distinct()
                ->select('a.NumAtCard as CusID', DB::raw('isnull(c.Cellolar,c.Tel1) as cus_moblie'))
                ->leftJoin('OHEM AS b', function ($join) {
                    $join->on('a.OwnerCode', '=', 'b.empID');
                })
                ->leftJoin('OCPR AS c', function ($join) {
                    $join->on('a.CntctCode', '=', 'c.CntctCode')->On('a.CardCode', '=', 'c.CardCode');
                })
                ->leftJoin(DB::raw('(select a.CardCode,a.name,a.E_MailL from OCPR a) AS d'), function ($join) {
                    $join->on('a.CardCode', '=', 'd.CardCode')->On('a.U_m_technician_partner', '=', 'd.Name');
                })
                ->where('a.DocStatus', 'O')
                ->where('a.NumAtCard', $Request->NumAtCard)
                ->orderBy('a.NumAtCard')
                ->first();
            }

            if(@$response->cus_moblie){
                $moblie = $response->cus_moblie;
                $s = "/\/|, | & | and /";

                $arrMoblie = preg_split($s, $moblie, -1, PREG_SPLIT_NO_EMPTY);
                foreach($arrMoblie as $k => $mob){
                    $isError = true;
                    $isChk = true;
                    $new_tel = null;
                    $a = str_replace([':', '\\', '/', '*', '-', ' ','(',')'], '', $mob);

                    $fc2 = substr($a, 0, 2);
                    $fc3 = substr($a, 0, 3);
                    $fc4 = substr($a, 0, 4);

                    if($fc2== '66'){
                        $b = substr($a, 2);

                        if(substr($b, 0, 1) == '0' && strlen($b) == 10){
                            $a = substr($a, 2); 
                        }else{
                            $a = '0' . substr($a, 2); 
                        }

                    }else if ($fc3 == '+66') {

                        $b = substr($a, 3);

                        if (substr($b, 0, 1) == '0' && strlen($b) == 10) {
                            $a = substr($a, 3);
                        } else {
                            $a = '0' . substr($a, 3);
                        }
                    
                    } else if ($fc2 == '02') {
                        $isChk = false;
                    }

                    if ($isChk && strlen($a) == 10) {
                        $new_tel = $a;
                        $isError = false;
                    }

                    if($isError == true){
                        $status = 400;
                        $message = __("sms.get_number.wrong_number");
                    }else{
                        $status = 200;
                        $message = 'Success';
                    }

                    $arrNew[$k]['old'] = $mob;
                    $arrNew[$k]['new'] = $new_tel;
                    $arrNew[$k]['status'] = $status;
                    $arrNew[$k]['message'] = $message;

                }
                
            }else{
                $arrNew = [];
                $arrNew[0]['status'] = 404;
                $arrNew[0]['message'] =  __("sms.get_number.no_number");
            }

            $res->status = 200;
            $res->title = 'Success';
            $res->message = 'Success';
            $res->data = $arrNew;

        } catch (\Exception $e) {
            //die("Could not connect to the database.  Please check your configuration. error:");
            $res->status = 400;
            $res->title = 'Could not connect to the database.';
            $res->message = 'Please check your configuration.';
            $res->data = [];
        }
        
        return response()->json($res);
    }

    /**
     * add Log SMS
     * 
     * Last Update 2021-05-28 09:09:09
     * By Prasong putichanchai
     * 
     *  @return array
     */

    public function addLogSMS(Request $request, $data = [], $req = [])
    {

        Log::channel('sms_log')->info(
            '#log#',
            [
                'ip' => $this->getUserIP(),
                'date' =>  date("Y-m-d H:i:s"),
                'cusid' => $request->cusid,
                'tel' => $request->tel,
                'code' => $request->code,
                'sms_return_id' => $request->sms_return_id,
                'sms_return_str' => $request->sms_return_str,
            ]
        );

        $res = (object)[];
        $res->status = 200;
        $res->message = 'Success';

        return response()->json($res);
    }

    function history(Request $request){

        $compact = (object)[];
        $arrayLog = [];
        $dir = "/sms_log/";
        $date = $request['date'];
        $cusId = $request['cus_id'];
        if (!$date) $date = date('Y-m-d');
        $fileName = "smslog-" . $date . ".log";
        $file = storage_path() . $dir . $fileName;
        
        if(file_exists($file)){
            $content = @File::get($file);
            foreach (explode("\n", $content) as $key => $line) {
                if (strpos($line, "#")) {
                    $isShow = true;
                    $newData = [];
                    list($time, $name, $data) = explode("#", $line);

                    $newData = json_decode($data);

                    if(@$cusId){
                        if ($newData->cusid == $cusId) {
                            $arrayLog[] = $newData;
                        }
                    }else{
                        $arrayLog[] = $newData;
                    }                  
                }
            }
            krsort($arrayLog);
        }

        if (is_dir(storage_path() . $dir)) {

            if ($dh = opendir(storage_path() . $dir)) {
                $arrDate = [];
                while (($file = readdir($dh)) !== false) {

                    if ($file != '.' &&  $file != '..' &&  $file != '') {
                        $aFn = explode("smslog-", $file);
                        $arrDate[] = explode(".log", $aFn[1])[0];
                    }
                }
                krsort($arrDate);
                closedir($dh);
            }
            $compact->selectDate = $arrDate;
            $compact->sDate = $date;
            $compact->cusId = $cusId;
        }
        
        $compact->collection = $arrayLog;
        $compact->total = count($arrayLog);  

        return view('_sms.history', (array) $compact);
    }

    function configLogin(Request $request){
        $compact = (object)[]; 
        return view('_sms.config_login', (array) $compact);
    }

    function config(Request $request)
    {
        $compact = (object)[];
        $input = (object)$request->all();
        $dataDecrypt = (object)[];
        $dataConfig = $this->_getConfigFile();

        if (isset($dataConfig)) {

            foreach($dataConfig as $key => $value) {
                $dataDecrypt->$key = $this->_decrypted($value);
            }

            if($dataDecrypt->username == $input->username && $dataDecrypt->password == $input->password){
                $compact = $dataDecrypt;
            }else{
                return redirect()->route('login-config-sms')->with('error' , ucfirst(__('sms.config.messages.login_error')));
            }
        }else{
            return redirect()->route('login-config-sms')->with('error', ucfirst(__('sms.config.messages.file_error')));
        }

        return view('_sms.config', (array) $compact);
    }

    function configUpdate(Request $request)
    {
        $input = (object)$request->all();
        $dataDecrypt = (object)[];
        $dataConfig = $this->_getConfigFile();

        if (isset($dataConfig)) {

            foreach ($dataConfig as $key => $value) {

                $dataDecrypt->$key = $this->_encrypted($input->$key);
            }

            $file = $this->_getFilePath();
            $newJsonString = json_encode($dataDecrypt);
            file_put_contents($file, $newJsonString);

        }
        
        return redirect()->route('login-config-sms')->with('success', ucfirst(__('sms.config.messages.update_success')));
    }

    private function _getConfigFile(){
        
        $config = (object)[];
        $file = $this->_getFilePath();
        if (file_exists($file)) {
            $strJsonFileContents = file_get_contents($file);
            $config = json_decode($strJsonFileContents);
        }
        return $config;
    }

    private function _setConfigDb()
    {
        $config = $this->_getConfigFile();
        config()->set('database.connections.sqlsrv.host', $this->_decrypted($config->db_host));
        config()->set('database.connections.sqlsrv.port', $this->_decrypted($config->db_port));
        config()->set('database.connections.sqlsrv.database', $this->_decrypted($config->db_database));
        config()->set('database.connections.sqlsrv.username', $this->_decrypted($config->db_username));
        config()->set('database.connections.sqlsrv.password', $this->_decrypted($config->db_password));
    }

    private function _getFilePath(){
        $dir = "/user_config/";
        $fileName = 'config-sms.json';
        return storage_path() . $dir . $fileName;
    }

    private function _encrypted($data=""){
        $keyEncrypt = Config('app.sis_key');
        $Encrypted = new Encrypter($keyEncrypt, Config('app.cipher'));
        return $Encrypted->encrypt($data);
    }

    private function _decrypted($data = ""){
        $keyEncrypt = Config('app.sis_key');
        $Encrypted = new Encrypter($keyEncrypt, Config('app.cipher'));
        return $Encrypted->decrypt($data);
    }

    function getUserIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
        $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

/** 
 * sms
 *
 * Modify/Update BY PRASONG PUTICHANCHAI
 * 
 * Latest Update : 01/06/2021 18:40
 * Version : ver.1.0.00
 *
 */
