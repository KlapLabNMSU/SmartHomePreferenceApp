<!DOCTYPE html>
<html>
      <body>

            <?php
            /* Author: Theoderic Platt
            Last Edited: 02/23/2022
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
            */







            /////////////////////////
            // Important Variables //
            /////////////////////////
            $url   = 'http://localhost:8080'; //openhab url
            $usr = ''; //username --TODO look into security, info should not be hardcoded.
            $psd  = ''; //password --TODO look into security, info should not be hardcoded.







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
            function bindingInstall($_url,$_usr,$_psd,$_uid){
                  $api = $_url . '/rest/addons/' . $_uid . '/install';
                  
                  $ch = curl_init();
                  curl_setopt($ch,CURLOPT_URL,$api);
                  curl_setopt($ch,CURLOPT_POST,1);
                  curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $response = curl_exec($ch);

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
            function bindingList($_url,$_usr,$_psd){
                  $api = $_url . '/rest/bindings';
                  
                  $ch = curl_init();
                  curl_setopt($ch,CURLOPT_URL,$api);
                  curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $response = curl_exec($ch);

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
                  $_url  - openhab url
                  $_usr  - username
                  $_psd  - password
                  $_uids - array containing all UIDs to be scanned from

            Postconditions: 
                  all scanned devices from the binding UIDs are added to the inbox
                  returns array containing openhab response for each uid
            
            Last Edited:
                  02/23/2022 */
            function bindingScan($_url,$_usr,$_psd,$_uids){
                  
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

                  curl_close ($ch);
                  return $response;
            }//end bindingScan


            /*
            Preconditions:  
                  $_url  - openhab url
                  $_usr  - username
                  $_psd  - password

            Postconditions: 
                  All data contained in the inbox is returned.
            
            Last Edited:
                  02/28/2022 */
            function inboxList($_url,$_usr,$_psd){
                  $api = $_url . '/rest/inbox';
                  
                  $ch = curl_init();
                  curl_setopt($ch,CURLOPT_URL,$api);
                  curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $response = curl_exec($ch);

                  curl_close ($ch);
                  return $response;
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
                  02/28/2022 */
            function inboxApprove($_url,$_usr,$_psd,$_uid){
                  $api = $_url . '/rest/inbox/' . $_uid . '/approve';
                  
                  $ch = curl_init();
                  curl_setopt($ch,CURLOPT_URL,$api);
                  curl_setopt($ch,CURLOPT_POST,1);
                  curl_setopt($ch, CURLOPT_USERPWD, $_usr . ":" . $_psd);

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $response = curl_exec($ch);

                  curl_close ($ch);
                  return $response;
            }//end inboxApprove








            /////////////////
            // Experiments //
            /////////////////
            
            //bindingInstall
            echo 'Response from bindingInstall() : </br>&emsp;' . bindingInstall($url,'smarthome','smarthome','binding-tplinksmarthome');
            echo '</br>';
            
            //bindingList
            echo 'Response from bindingList() : ';
            echo '</br>';
            foreach(bindingList($url,'smarthome','smarthome') as $uid)
                  echo '&emsp;'.$uid . '</br>';
            
            //bindingScan
            echo 'Response from bindingScan() : ';
            echo '</br>';
            foreach(bindingScan($url,'smarthome','smarthome',bindingList($url,'smarthome','smarthome')) as $uid)
                  echo '&emsp;'.$uid . '</br>';

            //inboxList
            echo 'Response from inboxList() : </br>&emsp;' . inboxList($url,'smarthome','smarthome');
            
            //inboxApprove
            //echo 'Response from inboxList() : </br>&emsp;' . inboxApprove($url,'smarthome','smarthome','tplinksmarthome:hs100:9D12EA');
            
            

            

            ?>

      </body>
</html>

