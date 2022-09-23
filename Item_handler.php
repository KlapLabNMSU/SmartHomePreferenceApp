<!--
Author: Theoderic Platt
Contributors: Moinul Morshed Porag Chowdhury
Date Last Modified: 03/31/2022
Description: Item_Handler.php uses cURL requests to openhab to allow 
            users to add devices through the use of the smarthome project webpage. 
            This application supports and handles the following actions: (WIP)
            1) Installation of bindings into openhab
            2) Listing of all installed bindings in openhab
            3) Scanning connected bindings for incoming devices
            4) listing devices found from the scan
            5) approval/denial of scanned devices into openhab registry
            6) creation of 'Items'
            7) linking of 'Items' to device channels
Includes: ---
Included In: activedevices.php bindings.php createitems.php installbinding.php items.php registration.php scan.php
Links To: ---
Links From: --- 
-->

<?php
/*

*/







/////////////////////////
// Important Variables //
/////////////////////////
$url   = 'http://localhost:8080'; //openhab url
$usr = 'tplatt'; //username --TODO look into security, info should not be hardcoded.
$psd  = '0Noudont'; //password --TODO look into security, info should not be hardcoded.

///////////////////////////
// API calling functions //
///////////////////////////

/*
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password
      $_uid - binding UID to install

Postconditions: 
      Binding is installed if uid is correct
      return response from openhab

Last Edited:
      02/16/2022 */
function bindingInstall_old($_url,$_usr,$_psd,$_uid){
      $api = $_url . '/rest/addons/' . $_uid . '/install';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>bindingInstall curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $response;
}//end bindingInstall


/*
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password

Postconditions: 
      returns array containing all installed bindings' UIDs 

Last Edited:
      02/23/2022 */
function bindingList($_url,$_usr,$_psd,$debug=false){
      $api = $_url . '/rest/bindings';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>bindingList curl http code:'.$httpCode.'</br>';

      curl_close ($ch);

      $bindings = [];
      $numIds = substr_count($response,'"id":');
      $lastId = 0;
      for($i = 0; $i<$numIds ; $i++){
            $lastId+= 5;
            $offset = strpos($response,'"id":',$lastId);
            $lastId = $offset;
            $length = strpos($response,'","',$offset);
            $uid = substr($response,$offset+6,$length-$lastId-6);
            
            array_push( $bindings , 'binding-' . $uid ); 
      }
      
      return $bindings;
}//end bindingList


/*
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password

Postconditions: 
      returns array containing all uninstalled bindings' UIDs 

Last Edited:
      02/23/2022 */
function getUninstalledBindings($_url,$_usr,$_psd){
      $api = $_url . '/rest/addons';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

                  curl_close ($ch);

      return json_decode($response,true);
}//end bindingList


/*
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password

Postconditions: 
      returns array containing all installed bindings' UIDs 

Last Edited:
      02/23/2022 */
function getAllItems($_url,$_usr,$_psd){
      $api = $_url . '/rest/items';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

                  curl_close ($ch);

      return json_decode($response,true);
}//end bindingList


/*
Preconditions:  
      $_url  - openhab url
      $_usr  - username
      $_psd  - password
      $_uids - array containing all UIDs to be scanned from

Postconditions: 
      all scanned devices from the binding UIDs are added to the inbox
      returns array containing openhab response for each uid

Last Edited:
      02/23/2022 */
function bindingScan($_url,$_usr,$_psd,$_uids,$debug=false){
      
      $response = [];
      $ch = curl_init();
      
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      foreach($_uids as $uid){
            $api = $_url . '/rest/discovery/bindings/' . $uid . '/scan';
            curl_setopt($ch,CURLOPT_URL,$api);
            $ret = curl_exec($ch);
            $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            #echo '</br>curl http code:'.$httpCode.'</br>';
            array_push($response, $ret);
      }

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>bindingScan curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $response;
}//end bindingScan


/*
Preconditions:  
      $_url  - openhab url
      $_usr  - username
      $_psd  - password

Postconditions: 
      All data contained in the inbox is returned as a json formatted array.

Last Edited:
      02/28/2022 */
function inboxList($_url,$_usr,$_psd,$debug=false){
      $api = $_url . '/rest/inbox';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>inboxList curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return json_decode($response,true);
}//end inboxList


/*                   
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password
      $_uid - uid of the device to approve

Postconditions: 
      The device with UID = $_uid is approved from the inbox.
      returns openhab response from adding the device.

Last Edited:
      03/14/2022 */
function inboxApprove($_url,$_usr,$_psd,$_uid,$debug=false){
      $api = $_url . '/rest/inbox/' . $_uid . '/approve';
      #echo $api . '</br>';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);
      $headers = array(
            "Accept: application/json",
            "Content-Type: text/plain"
      );
      curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>inboxApprove curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $response;
}//end inboxApprove


/*                   
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password
      $_bindingId - binding id of the device to install

Postconditions: 
      The device with Binging = $_bindingId is used
      returns openhab response from adding the binding.

Last Edited:
      03/14/2022 */
function bindingInstall($_url,$_usr,$_psd,$_bindingId){
      $api = $_url . '/rest/addons/' . $_bindingId . '/install';
      //echo $api . '</br>';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);
      $headers = array(
            "Accept: application/json",
            "Content-Type: text/plain"
      );
      curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      //echo '</br>curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $httpCode;
}//end bindingInstall


/*                   
Preconditions:  
      $_url - openhab url
      $_usr - username
      $_psd - password
      $_bindingId - binding id of the brand to uninstall

Postconditions: 
      The device with Binging = $_bindingId is used
      returns openhab response from adding the binding.

Last Edited:
      03/14/2022 */
function bindingUninstall($_url,$_usr,$_psd,$_bindingId){
      $api = $_url . '/rest/addons/' . $_bindingId . '/uninstall';
      //echo $api . '</br>';
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$api);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);
      $headers = array(
            "Accept: application/json",
            "Content-Type: text/plain"
      );
      curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      //echo '</br>curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $httpCode;
}//end bindingUninstall


/*
Preconditions:  
      $_url   - openhab url
      $_usr   - username
      $_psd   - password
      $_name  - item name
      $_label - item lable
      $_type  - item type

Postconditions: 
      itemCreate creates an item with all the set parameter values
      returns openhab response from adding the device.

Last Edited:
      03/16/2022 */
function itemCreate($_url,$_usr,$_psd,$_name,$_label,$_type,$debug = false){
      $api = $_url . '/rest/items/' . $_name;
      /*
      category: ""
      created: false
      groupNames: []
      label: "itemLabel"
      name: "itemName"
      tags: []
      type: "Switch"
      */
      $data = array(
            'category' => '', 
            'created'=> false,
            'groupNames' => [],
            'label'=> $_label,
            'name' => $_name,
            'tags' => [],  
            'type' => $_type
      );
      $payload = json_encode($data);
      
      #echo "Payload is: </br>";
      #echo $payload;
      #echo "</br>";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$api);
      //curl_setopt($ch, CURLOPT_PUT, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);
      $headers = array(
            "Content-Type: application/json"
      );
      curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>itemCreate curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $response;
}//end bindingScan

/*
Preconditions:  
      $_url   - openhab url
      $_usr   - username
      $_psd   - password
      $_name  - item name
      $_UID   - item UID
      $_type  - item type

Postconditions: 
      itemLink links an item to a channel
      returns openhab response from adding the device.

Last Edited:
      03/29/2022 */
function itemLink($_url,$_usr,$_psd,$_name,$_UID,$_type,$debug=false){
      $api = $_url . '/rest/links/' . $_name . '/' . $_UID . '%3A' . $_type;
      if($debug) echo 'api is: '.$api;
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$api);
      //curl_setopt($ch, CURLOPT_PUT, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

      $headers = array(
            "Content-Type: application/json"
      );
      curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if($debug)echo '</br>itemLink curl http code:'.$httpCode.'</br>';

      curl_close ($ch);
      return $response;
}//end bindingScan

//THE BELOW CODE COMMENTED OUT SHOWS EXACT OUTPUT OF THE inboxList() FUNCTION.
/*
$L0 = inboxList($url,$usr,$psd);

$ind1 = 0;
echo 'array indexes:</br>';
foreach($L0 as $L1){
      $ind2 = 0;
      if(is_array($L1))
      foreach($L1 as $L2){
            $ind3 = 0;
            if(is_array($L2))
            foreach($L2 as $L3){
                  echo '</br>t['.$ind1.']['.$ind2.']['.$ind3.']:'.$L3;
                  $ind3++;
            }
            else 
            echo '</br>t['.$ind1.']['.$ind2.']:'.$L2;
            $ind2++;
      }
      else 
      echo '</br>AAAHHHHHHH';
      $ind1++;
}
echo'</br></br></br>vardump:</br>';
var_dump($L0);
*/


?>

