<?php
# uncomment to get error output
// error_reporting(E_ALL); ini_set( 'display_errors','1');

# configure camera arrays
$user="user";  # username
$pass='password'; # password

# number the cameras and specify ip address
$cam_list=[
  1 => '192.168.1.xxx',
  2 => '192.168.1.xxx',
];

# list the camera id for the older cameras that uses basic auth only (or armcrest ones)
$basic_auth_list=[

];
# =========================================
# ==== Do not change below this line! =====
# =========================================

# catch querystrings
$cam     = catchGet('c');
$action  = catchGet('a');
$preset  = catchGet('p');
$debug   = catchGet('d');
$a1      = catchGet('a1');
$a2      = catchGet('a2');
$a3      = catchGet('a3');

# set basic auth
$basic_auth=false;
if(in_array($cam,$basic_auth_list)){
  $basic_auth=true;
}


# ====== Specify Endpoints ======
# MagicBox
$e['GETMaxExtraStreamCounts'] ="magicBox.cgi?action=getProductDefinition&name=MaxExtraStream";
$e['GETLanguageCaps']         ="magicBox.cgi?action=getLanguageCaps";
$e['Reboot']                  ="magicBox.cgi?action=reboot";
$e['Shutdown']                ="magicBox.cgi?action=shutdown";
$e['GetDeviceType']           ="magicBox.cgi?action=getDeviceType";
$e['GetHardwareVersion']      ="magicBox.cgi?action=getHardwareVersion";
$e['GetSerialNo']             ="magicBox.cgi?action=getSerialNo";
$e['GetMachineName']          ="magicBox.cgi?action=getMachineName";
$e['GetSystemInfo']           ="magicBox.cgi?action=getSystemInfo";

# DeviceINput
$e['GetDeviceCaps']           ="devVideoInput.cgi?action=getCaps&channel=0";

# Encode
$e['GetEncodeCaps']           ="encode.cgi?action=getConfigCaps";

# netApp
$e['GetInterfaces']           ="netApp.cgi?action=getInterfaces";
$e['GetUPnPStatus']           ="netApp.cgi?action=getUPnPStatus";

# alarm
$e['GetInSlots']              ="alarm.cgi?action=getInSlots";
$e['GetOutSlots']             ="alarm.cgi?action=getOutSlots";
$e['GetInState']              ="alarm.cgi?action=getInState";
$e['GetOutState']             ="alarm.cgi?action=getOutState";

# eventManager
$e['GetEventIndexes']         ="eventManager.cgi?action=getEventIndexes&code=".$a1;

# ptz
$e['GetProtolList']           ="ptz.cgi?action=getProtolList";
$e['GetCurrentProtolCaps']    ="ptz.cgi?action=getCurrentProtocolCaps&channel=".$a1;
$e['GotoPreset']              ="ptz.cgi?action=start&channel=0&code=GotoPreset&arg1=0&arg2=".$a1."&arg3=0";

# global
$e['GetCurrentTime']          ="global.cgi?action=getCurrentTime";

# userManager
$e['GetGroupInfo']            ="userManager.cgi?action=getGroupInfo&name=".$a1;
$e['GetGroupInfoAll']         ="userManager.cgi?action=getGroupInfoAll";
//$e['AddUser']                 ="userManager.cgi?action=addUser ... ";
//$e['DeleteUser']              ="userManager.cgi?action=deleteUser&name=".$a1;
//$e['ModifyUser']              ="userManager.cgi?action=modifyUser ... ";
//$e['ModifyPassword']          ="userManager.cgi?action=modifyPassword ... ";
//$e['GetUserInfo']             ="userManager.cgi?action=getUserInfo&name=".$a1;
//$e['GetUserInfoAll']          ="userManager.cgi?action=getUserInfoAll";


# configManager
$configGet="configManager.cgi?action=getConfig&name=";
$configSet="configManager.cgi?action=setConfig&name=";
$e['GetVideoColorConfig']     =$configGet."VideoColor";
$e['GetVideoInOptionsConfig'] =$configGet."VideoInOptions";
$e['GetEncodeConfig']         =$configGet."Encode";
$e['GetChannelTitleConfig']   =$configGet."ChannelTitle";
$e['GetVideoStandardConfig']  =$configGet."VideoStandard";
$e['GetVideoWidgetConfig']    =$configGet."VideoWidget";
$e['GetBasicConfig']          =$configGet."Network";
$e['GetPPPoEConfig']          =$configGet."PPPoE";
$e['GetDDNSConfig']           =$configGet."DDNS";
$e['GetEmailConfig']          =$configGet."Email";
$e['GetWlanConfig']           =$configGet."WLan";
$e['GetUPnPConfig']           =$configGet."UPnP";
$e['GetNTPConfig']            =$configGet."NTP";
$e['GetAlarmServerConfig']    =$configGet."AlarmServer";
$e['GetAlarmConfig']          =$configGet."Alarm";
$e['GetAlarmOutConfig']       =$configGet."AlarmOut";
$e['GetMotionDetectConfig']   =$configGet."MotionDetect";
$e['GetBlindDetectConfig' ]   =$configGet."BlindDetect";
$e['GetLossDetectConfig']     =$configGet."LossDetect";
$e['GetPTZConfig']            =$configGet."Ptz";
$e['GetRecordConfig']         =$configGet."Record";
$e['GetRecordModeConfig']     =$configGet."RecordMode";
$e['GetSnapConfig']           =$configGet."Snap";
$e['GetGeneralConfig']        =$configGet."General";
$e['GetLocalsConfig']         =$configGet."Locales";
$e['GetLanguageConfig']       =$configGet."Language";
$e['GetAccessFilterConfig']   =$configGet."AccessFilter";
$e['GetAutoMaintainConfig']   =$configGet."AutoMaintain";

# [TODO]  Setconfigs, currently not implemented.
// $e['SetVideoColorConfig']     =$configSet."VideoColor";
// $e['SetVideoInOptionsConfig'] =$configSet."VideoInOptions";
// $e['SetEncodeConfig']         =$configSet."Encode";
// $e['SetChannelTitleConfig']   =$configSet."ChannelTitle0";
// $e['SetVideoStandardConfig']  =$configSet."VideoStandard";

# ====  Main =====
if(!$cam){
  die('Please specify which camera to operate');
}
if($preset){  // Shortcut for goto preset
  $GotoPreset="cgi-bin/ptz.cgi?action=start&channel=0&code=GotoPreset&arg1=0&arg2=".$preset."&arg3=0";
  $url="http://{$cam_list[$cam]}/$GotoPreset";
}else if($action){
   if(isset($e[$action])){
     $url="http://{$cam_list[$cam]}/cgi-bin/".$e[$action];
   }else{
      $message="<p>the Endpoint specified was not implemented.</p>".
         "<p>list of available Endpoints:</p>".
         "<pre>". print_r(array_keys($e), true). "</pre>";
      die($message);
   }
}else{  // no preset and no action.. throw error
  die('please provide action or preset!');
}

$options=build_options($url,$user,$pass,$debug,$basic_auth);

# execute the curl call
$response=curl_call($options);

# output the call
if($debug){
  $headers=array();
  $data=explode("\n",$response);
  echo "<pre>$url</pre><hr /><pre>".print_r($data,true)."</pre>";
}else{
   echo "<pre>$response</pre>";
}


# ===== Functions ====
function catchGet($opt){
  if(isset($_GET[$opt])&&trim($_GET[$opt])!==''){
    return trim($_GET[$opt]);
  }else{
    return false;
  }
}

function build_options($url,$user,$pass,$debug,$basic_auth){
  $options = array(
          CURLOPT_URL            => $url,
          CURLOPT_USERPWD        => $user . ":" . $pass,
          CURLOPT_RETURNTRANSFER => true,
  );
  # auth method
  if($basic_auth){
    $auth_header='Basic '.base64_encode("$user:$pass");
    $options[CURLOPT_HTTPHEADER]=[$auth_header];
  }else{
    $options[CURLOPT_HTTPAUTH]=CURLAUTH_DIGEST;
  }
  # debug headers
  if($debug){
    $options[CURLOPT_HEADER]=true;
    $options[CURLOPT_VERBOSE]=true;
  }
  return $options;
}

function curl_call($options){
  $ch = curl_init();
  curl_setopt_array( $ch, $options);

  try {
    $raw_response  = curl_exec( $ch );
    // validate CURL status
    if(curl_errno($ch)){
        throw new Exception(curl_error($ch), 500);
    }
    // validate HTTP status code (user/password credential issues)
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status_code != 200)
        throw new Exception("Response with Status Code [" . $status_code . "].", 500);
  } catch(Exception $ex) {
      throw new Exception($ex);
  } finally {
      curl_close($ch);
      return $raw_response;
  }
}

?>
