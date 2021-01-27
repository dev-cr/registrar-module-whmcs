<?php


use WHMCS\Domains\DomainLookup\ResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;
use WHMCS\Domain\TopLevel\ImportItem;



    ini_set("display_errors","1");
    $apiUrl ="https://api.connectreseller.com/ConnectReseller/";
    function connectreseller_getConfigArray(){
        $configarray = array(
            'APIKey' => array('Type' => "text", 'Size' => "20", 'Description' => "Enter your API key"),
            'BrandId' => array('Type' => "text", 'Size' => "20", 'Description' => " Enter your BrandId  "), 
            'CouponCode' => array('Type' => "text", 'Size' => "20", 'Description' => " Enter your Coupon code  "),      
        );
        return $configarray;
    }

    function connectreseller_GetNameservers($params) {
        $tld = $params["tld"];
        $sld = $params["sld"];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $msgResult = array_key_exists ( "responseMsg" ,$res );
        if($msgResult){
            if($res["responseMsg"]['statusCode']!='200'){
                $values["error"] = $res["responseMsg"]['statusCode']." - ".$res["responseMsg"]['message'];
            } else {
                $values["ns1"] = $res["responseData"]['nameserver1'];
                $values["ns2"] =$res["responseData"]['nameserver2'];
                $values["ns3"] = $res["responseData"]['nameserver3'];
                $values["ns4"] = $res["responseData"]['nameserver4'];
                $values["ns5"] = $res["responseData"]['nameserver5'];
                $values["ns6"] =$res["responseData"]['nameserver6'];
                $values["ns7"] = $res["responseData"]['nameserver7'];
                $values["ns8"] = $res["responseData"]['nameserver8'];
                $values["ns9"] = $res["responseData"]['nameserver9'];
                $values["ns10"] =$res["responseData"]['nameserver10'];
                $values["ns11"] = $res["responseData"]['nameserver11'];
                $values["ns12"] = $res["responseData"]['nameserver12'];
                $values["ns13"] = $res["responseData"]['nameserver13'];
            }
        }else{
            $values["error"] = $res['statusCode']." - ".$res['statusText']." - ".$res['responseText'];
        }
        return $values;
    }

    function connectreseller_SaveNameservers($params) {
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $nameserver1 = $params["ns1"];
        $nameserver2 = $params["ns2"];
        $nameserver3 = $params["ns3"];
        $nameserver4 = $params["ns4"];
        $nameserver5 = $params["ns5"];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $DomainNameID = $res["responseData"]['domainNameId'];
        # Put your code to save the nameservers here
        if($res["responseData"]['isDomainLocked']!='True'){
            $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld.'&domainNameId='.$DomainNameID;
            if($nameserver1 != "") $query .='&nameServer1='.$nameserver1;
            if($nameserver2 != "") $query .='&nameServer2='.$nameserver2;
            if($nameserver3 != "") $query .='&nameServer3='.$nameserver3;
            if($nameserver4 != "") $query .='&nameServer4='.$nameserver4;
            if($nameserver5 != "") $query .='&nameServer5='.$nameserver5;
          
            $updateDomainurl ="https://api.connectreseller.com/ConnectReseller/ESHOP/UpdateNameServer/?".$query;
            $updateDomainurl = trim($updateDomainurl);
            $updateDomainurl = str_replace ( ' ', '%20', $updateDomainurl);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $updateDomainurl);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $updateResponse = curl_exec($ch);

            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
                exit;
            }
            curl_close($ch);
            $updateRes =json_decode($updateResponse, true);
            if($updateRes["responseMsg"]['statusCode']!='200'){
                $values["error"] = "Domain Updation Failed(Invalid NameServers)";
            }
        }else{
            $values["error"]="Disable The Status of Lock";
        }
        return $values;
    }

    function connectreseller_GetRegistrarLock($params) {
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;

        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        if ($res["responseData"]['isDomainLocked'] == "True" || $res["responseData"]['isDomainLocked'] == "true") {
            $lockstatus = "locked";
        } else {
            $lockstatus = "unlocked";
        }
        return $lockstatus;
    }

    function connectreseller_SaveRegistrarLock($params) {
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];    
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        if ($params["lockenabled"] == 'unlocked') {
            $DomainLockStatus = 'false';
        } else {
            $DomainLockStatus = 'true';
        }
        // get the domainnameid from url    
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);

        $res =json_decode($response, true);
       
        $domainNameId = $res["responseData"]['domainNameId'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld.'&domainNameId='.$domainNameId.'&isDomainLocked='.$DomainLockStatus;
        $manageUrl = trim("https://api.connectreseller.com/ConnectReseller/ESHOP/ManageDomainLock/?".$query);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $manageUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $manageResponse = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $manageRes =json_decode($manageResponse, true);
        if($manageRes["responseMsg"]['statusCode']!='200'){
            $values["error"] = $manageRes["responseMsg"]['statusCode']." - ".$manageRes["responseMsg"]['message'];
        }
        return $values;
    }
    function connectreseller_GetDNS($params){
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];   
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;

        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $domianId = $res["responseData"]['domainNameId'];
        $websiteId = $res["responseData"]['websiteId'];
        if($res["responseData"]['dnszoneStatus'] == "1" ) {
            $query = 'APIKey='.$ApiKey.'&WebsiteId='.$websiteId;
            $viewDnsUrl = trim("https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDNSRecord/?".$query);
            $viewDnsUrl = trim($viewDnsUrl);
            $viewDnsUrl = str_replace ( ' ', '%20', $viewDnsUrl);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $viewDnsUrl);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $viewDnsResponse = curl_exec($ch);
            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
                exit;
            }
            curl_close($ch);
            $viewDnsRes =json_decode($viewDnsResponse, true);
            if($viewDnsRes["responseMsg"]['statusCode']!='200'){
                $values["error"] = $viewDnsRes["responseMsg"]['statusCode']." - ".$viewDnsRes["responseMsg"]['message'];
             }else{
                $host = $viewDnsRes['responseData'];
                foreach ($host as $v) {
                    if(($v['recordType'] != 'SRV') && ($v['recordType'] != 'SOA')  && ($v['recordType'] != 'NS')){
                        $values[] = array(
                            'hostname' => $v['recordName'],
                            'type'     => $v['recordType'],
                            'address'  => $v['recordContent'],
                            'priority' => $v['recordPriority'],
                            'recid' =>$v['dnszoneRecordID']
                        );
                    }
                }
            }
        }else{
            $query = 'APIKey='.$ApiKey.'&WebsiteId='.$websiteId;
            $manageDnsUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ManageDNSRecords/?".$query;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $manageDnsUrl);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $manageDnsResponse = curl_exec($ch);
            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
                exit;
            }
            curl_close($ch);
            $manageDnsRes =json_decode($manageDnsResponse, true);
            if($manageDnsRes["responseMsg"]['statusCode']!='200'){
                $values["error"] = $manageDnsRes["responseMsg"]['statusCode']." - ".$manageDnsRes["responseMsg"]['message'];
            }else{
                $query = 'APIKey='.$ApiKey.'&WebsiteId='.$websiteId;
                $viewDnsUrl = trim("https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDNSRecord/?".$query);
                $viewDnsUrl = trim($viewDnsUrl);
                $viewDnsUrl = str_replace ( ' ', '%20', $viewDnsUrl);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $viewDnsUrl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                $viewDnsResponse = curl_exec($ch);
                if($errno = curl_errno($ch)) {
                    $error_message = curl_strerror($errno);
                    echo "cURL error ({$errno}):\n {$error_message}";
                    exit;
                }
                curl_close($ch);
                $viewDnsRes =json_decode($viewDnsResponse, true);
                if($viewDnsRes["responseMsg"]['statusCode']!='200'){
                    $values["error"] = $viewDnsRes["responseMsg"]['statusCode']." - ".$viewDnsRes["responseMsg"]['message'];
                }else{
                    $host = $viewDnsRes['responseData'];
                    foreach ($host as $v) {
                        if(($v['recordType'] == 'SRV') || ($v['recordType'] == 'SOA')  || ($v['recordType'] == 'NS')){
                            $values[] = array(
                                'hostname' => $v['recordName'],
                                'type'     => $v['recordType'],
                                'address'  => $v['recordContent'],
                                'priority' => $v['recordPriority'],
                                'recid' =>$v['dnszoneRecordID']
                            );
                        } 
                    }
                }
            }
        }
        return $values;
    }
    function connectreseller_SaveDNS($params){
        $sld = $params['sld'];  
        $tld =  $params['tld']; 
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        # Put your code to get the lock status here
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;

        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $domianId = $res["responseData"]['domainNameId'];
        $websiteId = $res["responseData"]['websiteId'];
        $DomainNameID =9463;
        if($res["responseData"]['dnszoneStatus'] == "1" ) {
            $DNSZoneId = $res["responseData"]['dnszoneId'];
            $query = 'APIKey='.$ApiKey.'&WebsiteId='.$websiteId;
            $viewDnsUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDNSRecord/?".$query;
            $viewDnsUrl = trim($viewDnsUrl);
            $viewDnsUrl = str_replace ( ' ', '%20', $viewDnsUrl);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $viewDnsUrl);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $viewDnsResponse = curl_exec($ch);
            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
                exit;
            }
            curl_close($ch);
            $viewDnsRes =json_decode($viewDnsResponse, true);

            if($viewDnsRes["responseMsg"]['statusCode']!='200'){
                $values["error"] = $viewDnsRes["responseMsg"]['statusCode']." - ".$viewDnsRes["responseMsg"]['message'];
             }else{
                $host = $viewDnsRes['responseData'];
                foreach ($params['dnsrecords'] as $k => $v) {
                    if (!empty($v['hostname']) && !empty($v['type']) && !empty($v['address'])) {                    
                        if($v['recid'] !="" && $v['recid'] !=null){
                            $key = array_search($v['recid'], array_column($host, 'dnszoneRecordID'));
                            if($key != -1){
                                $checkHost = $host[$key];
                                if(($v['hostname'] !=$checkHost['recordName'] ) || ($v['type'] !=$checkHost['recordType']) || ($v['address'] !=$checkHost['recordContent']) || 
                                    (($v['priority'] !=$checkHost['recordPriority']) && $v['type'] =="MX" )){
                                    $query = 'APIKey='.$ApiKey.'&WebsiteId='.$websiteId.'&DNSZoneID='.$DNSZoneId.'&DNSZoneRecordID='.$v['recid'].'&RecordName='.$v['hostname'].'&RecordType='.$v['type'].'&RecordValue='.$v['address'].'&RecordTTL=43200'; 
                                    $modifyDnsUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ModifyDNSRecord/?".$query;
                                    $modifyDnsUrl = trim($modifyDnsUrl);
                                    $modifyDnsUrl = str_replace ( ' ', '%20', $modifyDnsUrl);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $modifyDnsUrl);
                                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                                    $viewDnsResponse = curl_exec($ch);
                                    if($errno = curl_errno($ch)) {
                                        $error_message = curl_strerror($errno);
                                        echo "cURL error ({$errno}):\n {$error_message}";
                                        exit;
                                    }
                                    curl_close($ch);
                                    $modifyDnsRes =json_decode($viewDnsResponse, true);
                                    if($modifyDnsRes["responseMsg"]['statusCode']!='200'){
                                        $values["error"] = $modifyDnsRes["responseMsg"]['statusCode']." - ".$modifyDnsRes["responseMsg"]['message'];
                                    }else{
                                        $values["error"] ="Record Updated Successfully";
                                    }
                                }
                            }      
                        }else{
                            $query = 'APIKey='.$ApiKey.'&WebsiteId='.$websiteId.'&DNSZoneID='.$DNSZoneId.'&RecordName='.$v['hostname'].'&RecordType='.$v['type'].'&RecordValue='.$v['address'].'&RecordTTL=43200'; 
                            $addDnsUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/AddDNSRecord/?".$query;
                            $addDnsUrl = trim($addDnsUrl);

                            $addDnsUrl = str_replace ( ' ', '%20', $addDnsUrl);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $addDnsUrl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            $addDnsResponse = curl_exec($ch);
                            if($errno = curl_errno($ch)) {
                                $error_message = curl_strerror($errno);
                                echo "cURL error ({$errno}):\n {$error_message}";
                                exit;
                            }
                            curl_close($ch);
                            $addDnsRes =json_decode($viewDnsResponse, true);
                            if($addDnsRes["responseMsg"]['statusCode']!='200'){
                                $values["error"] = $addDnsRes["responseMsg"]['statusCode']." - ".$addDnsRes["responseMsg"]['message'];
                            }else{
                                $values["error"] = " Record Added Successfully";
                            }
                        }
                    }
                }
            }
        }
        return $values;
    }
    function connectreseller_RegisterDomain($params){
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $regperiod = $params["regperiod"];
        $nameserver1 = $params["ns1"];
        $nameserver2 = $params["ns2"];
        $nameserver3 = $params["ns3"];
        $nameserver4 = $params["ns4"];
        $IsWhoisProtectionFalse ="false";
        $CouponCode =$params["CouponCode"];
        $IsWhoisProtection = $params["idprotection"]==1?true:$IsWhoisProtectionFalse;
        $RegistrantEmailAddress = $params["email"];
        $query = 'APIKey='.$ApiKey.'&UserName='.$RegistrantEmailAddress;
        $viewClienturl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewClient/?".$query;
        $viewClienturl = trim($viewClienturl);
        $viewClienturl = str_replace ( ' ', '%20', $viewClienturl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewClienturl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "$viewClienturl";
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
     
        curl_close($ch);
        $res =json_decode($response, true);

        $msgResult = array_key_exists ( "responseMsg" ,$res );
        if($msgResult){
            if($res['responseMsg']['statusCode']!='200'){
                $UserName = $params["email"];
                $str ="cr123456";
                $Password =str_shuffle($str);
                $companyname = $params["companyname"];
                $firstname = $params["fullname"];
                $lastname = $params["lastname"];
                $Gender='male';  
                $DOB='10-21-1987';   
                $address1 = $params["address1"];
                $address2 = $params["address2"];   
                $countryname = $params["countryname"];
                if($params["fullstate"]==''){
                    $state='other';
                }else{
                    $state = $params["fullstate"];
                }
                $city = $params["city"];
                $postcode = $params["postcode"];
                $phonecc = $params["phonecc"];
                $phonenumber = $params["phonenumber"];
                $MobileNo_cc=$params["phonecc"]; 
                $MobileNo=$params["phonenumber"];  
                $AccountEmailAddress='sales@qualispace.in';
                $Faxno_cc='';  
                $FaxNo='';
                $Size_Of_Org=''; // need to verify 
                $Number_Of_Computers=''; // need to verify 
                $Industry_Type=''; // need to verify 
                $Signup_Newsletter='false'; // need to verify 
                $OutPutType='XML';
                if ($params["currency"]==1) {
                    $accountingcurrencysymbol='USD';
                }else{
                    $accountingcurrencysymbol='INR';
                }
                $query="APIKey=".urlencode($ApiKey);
                $query.="&UserName=".urlencode($RegistrantEmailAddress);
                $query.="&Password=".urlencode($Password)."&CompanyName=".urlencode($companyname)."&FirstName=".urlencode($firstname)."&Address1=".urlencode($address1.$address2)."&City=".urlencode($city) ."&StateName=".$state."&CountryName=".$countryname ."&Zip=".$postcode."&PhoneNo_cc=".$phonecc."&PhoneNo=".$phonenumber;
                $addClienturl ="https://api.connectreseller.com/ConnectReseller/ESHOP/AddClient?".trim($query);
                $addClienturl = trim($addClienturl);
                $addClienturl = str_replace ( ' ', '%20', $addClienturl);
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $addClienturl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $addClientResponse = curl_exec($ch);
                if($errno = curl_errno($ch)) {
                    $error_message = curl_strerror($errno);
                    echo "$viewClienturl";
                    echo "cURL error ({$errno}):\n {$error_message}";
                    exit;
                }
                

                $addClientRes=json_decode($addClientResponse,true);
                if($addClientRes['responseMsg']['statusCode']!=200){
                    $values["error"] ="Domain Registration Failure: Unable to add client.";
                } else {
                    $res = json_decode($addClientResponse);
                    $UserName = $addClientRes['responseData']['userName'];
                    $CustomerID = $addClientRes['responseData']['clientId'];

                    $query = 'APIKey='.$ApiKey.'&Id='.$CustomerID;
                    $defaultRegistranturl = "https://api.connectreseller.com/ConnectReseller/ESHOP/DefaultRegistrantContact/?".$query;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $defaultRegistranturl);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                    $defaultRegistrantResponse = curl_exec($ch);
                    if($errno = curl_errno($ch)) {
                        $error_message = curl_strerror($errno);
                        echo "cURL error ({$errno}):\n {$error_message}";
                        exit;
                    }
                    curl_close($ch);
                    $defaultRegistrantRes = json_decode($defaultRegistrantResponse,true);
                    if($defaultRegistrantRes['responseMsg']['statusCode']!=200){
                        $values["error"] = $defaultRegistrantRes['responseMsg']['statusCode']." - ".$defaultRegistrantRes['responseMsg']['message'];
                    } else {
                        $ContactId = $defaultRegistrantRes['responseData']['registrantContactId'];
                    }
                    $regperiod=$params["regperiod"];
                    $websitename= $sld.'.' .$tld;
                    $query = 'APIKey='.$ApiKey.'&Id='.$CustomerID.'&ProductType=1&Websitename='.$websitename.'&Duration='.$regperiod.'&IsWhoisProtection='.$IsWhoisProtection;
                    if($nameserver1 != "") $query .='&ns1='.$nameserver1;
                    if($nameserver2 != "") $query .='&ns2='.$nameserver2;
                    if($nameserver3 != "") $query .='&ns3='.$nameserver3;
                    if($nameserver4 != "") $query .='&ns4='.$nameserver4;
                    $premiumEnabled = (bool) $params['premiumEnabled']==true?1:0;
                    $query .='&isEnablePremium='.$premiumEnabled;
                    if (!(!isset($CouponCode) || trim($CouponCode) === '')){
                       $query .= '&couponCode='.$CouponCode;
                    }
                    $orderUrl ="https://api.connectreseller.com/ConnectReseller/ESHOP/domainorder/?".$query;
                    $orderUrl = trim($orderUrl);
                    $orderUrl = str_replace ( ' ', '%20', $orderUrl);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $orderUrl);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                    $orderResponse = curl_exec($ch);
                    if($errno = curl_errno($ch)) {
                        $error_message = curl_strerror($errno);
                        echo "cURL error ({$errno}):\n {$error_message}";
                        exit;
                    }
                    curl_close($ch);
                    $orderRes = json_decode($orderResponse,true);
                    if($orderRes['responseMsg']['statusCode']!=200){
                        $values["error"] = $orderRes['responseMsg']['statusCode']." - ".$orderRes['responseMsg']['message'];
                    }else{
                        $values["result"] = "success";
                    }
                }
            }else{
                $UserName = $res['responseData']['userName'];
                $CustomerID = $res['responseData']['clientId'];
                $query = 'APIKey='.$ApiKey.'&Id='.$CustomerID;           
                $regperiod=$params["regperiod"];
                $websitename= $sld.'.' .$tld;
                $query = 'APIKey='.$ApiKey.'&Id='.$CustomerID.'&ProductType=1&Websitename='.$websitename.'&Duration='.$regperiod.'&IsWhoisProtection='.$IsWhoisProtection;
                if($nameserver1 != "") $query .='&ns1='.$nameserver1;
                if($nameserver2 != "") $query .='&ns2='.$nameserver2;
                if($nameserver3 != "") $query .='&ns3='.$nameserver3;
                if($nameserver4 != "") $query .='&ns4='.$nameserver4;
                $premiumEnabled = (bool) $params['premiumEnabled']==true?1:0;
                $query .='&isEnablePremium='.$premiumEnabled;
                if (!(!isset($CouponCode) || trim($CouponCode) === '')){
                   $query .= '&couponCode='.$CouponCode;
                }
                   
                $orderUrl ="https://api.connectreseller.com/ConnectReseller/ESHOP/domainorder/?".$query;
                $orderUrl = trim($orderUrl);
                $orderUrl = str_replace ( ' ', '%20', $orderUrl);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $orderUrl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                $orderResponse = curl_exec($ch);
                if($errno = curl_errno($ch)) {
                    $error_message = curl_strerror($errno);
                    echo "cURL error ({$errno}):\n {$error_message}";
                    exit;
                }
                curl_close($ch);        
                $orderRes = json_decode($orderResponse,true);
                if($orderRes['responseMsg']['statusCode']!=200){
                    $values["error"] = $orderRes['responseMsg']['statusCode']." - ".$orderRes['responseMsg']['message'];
                }else{
                    $values["result"] = "success";
                }
            }
        }else{
            $values["error"] = $res['statusCode']." - Domain Registration Failure - ".$res['responseText'];
        }
        return $values;
    }
    function connectreseller_TransferDomain($params){
      
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];    
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $regperiod = $params["regperiod"];
        $CouponCode =$params["CouponCode"];
        $nameserver1 = $params["ns1"];
        $nameserver2 = $params["ns2"];
        $nameserver3 = $params["ns3"];
        $nameserver4 = $params["ns4"];
        $IsWhoisProtectionFalse ="false";
        $IsWhoisProtection = $params["idprotection"]==1?true:$IsWhoisProtectionFalse;
        $RegistrantEmailAddress = $params["email"];
        $authCode = $params["eppcode"];
        $query = 'APIKey='.$ApiKey.'&UserName='.$RegistrantEmailAddress;
        $viewClienturl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewClient/?".$query;
        $viewClienturl = trim($viewClienturl);
        $viewClienturl = str_replace ( ' ', '%20', $viewClienturl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewClienturl);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);

        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        
        $msgResult = array_key_exists ( "responseMsg" ,$res );
        if($msgResult){
            if($res["responseMsg"]['statusCode']!='200'){
                
                $UserName = $params["email"];
                $str ="cr123456";
                $Password =str_shuffle($str);
                $companyname = $params["companyname"];
                $firstname = $params["fullname"];
                $lastname = $params["lastname"];
                $Gender='male';  
                $DOB='10-21-1987';   
                $address1 = $params["address1"];
                $address2 = $params["address2"];   
                $countryname = $params["countryname"];
                if($params["fullstate"]==''){
                    $state='other';
                }else{
                    $state = $params["fullstate"];
                }
                $city = $params["city"];
                $postcode = $params["postcode"];
                $phonecc = $params["phonecc"];
                $phonenumber = $params["phonenumber"];
                $MobileNo_cc=$params["phonecc"]; 
                $MobileNo=$params["phonenumber"];  
                $AccountEmailAddress='sales@qualispace.in';
                $Faxno_cc='';  
                $FaxNo='';
                $Size_Of_Org=''; // need to verify 
                $Number_Of_Computers=''; // need to verify 
                $Industry_Type=''; // need to verify 
                $Signup_Newsletter='false'; // need to verify 
                $OutPutType='XML';
                if ($params["currency"]==1) {
                    $accountingcurrencysymbol='USD';
                }else{
                    $accountingcurrencysymbol='INR';
                }
                $query="APIKey=".urlencode($ApiKey);
                $query.="&UserName=".urlencode($RegistrantEmailAddress);
                $query.="&Password=".urlencode($Password)."&CompanyName=".urlencode($companyname)."&FirstName=".urlencode($firstname)."&Address1=".urlencode($address1.$address2)."&City=".urlencode($city) ."&StateName=".$state."&CountryName=".$countryname ."&Zip=".$postcode."&PhoneNo_cc=".$phonecc."&PhoneNo=".$phonenumber;
                $addClienturl ="https://api.connectreseller.com/ConnectReseller/ESHOP/AddClient?".trim($query);
                $addClienturl = trim($addClienturl);
                $addClienturl = str_replace ( ' ', '%20', $addClienturl);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $addClienturl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                $addClientResponse = curl_exec($ch);
               
                if($errno = curl_errno($ch)) {
                    $error_message = curl_strerror($errno);
                    echo "cURL error ({$errno}):\n {$error_message}";
                }
                $addClientRes=json_decode($addClientResponse,true);
                if($addClientRes['responseMsg']['statusCode']!=200){
                    $values["error"] ="Domain Transfer Failure: Unable to add client.";
                } else {
                    curl_close($ch);   
                    $UserName = $addClientRes['responseData']['userName'];
                    $CustomerID = $addClientRes['responseData']['clientId'];
                    try{
                        $dataArr =array(
                            'Id' => intval($CustomerID),
                            'OrderType' => 4,
                            'APIKey' =>$ApiKey,
                            'Websitename' => $websitename,
                            'AuthCode' => $authCode
                        );
                        $query =http_build_query($dataArr);
                        $query = $query.'&IsWhoisProtection='.$IsWhoisProtection;
                        // $premiumEnabled = (bool) $params['premiumEnabled']==true?1:0;
                        // $query .='&isEnablePremium='.$premiumEnabled;
                        if (!(!isset($CouponCode) || trim($CouponCode) === '')){
                           $query .= '&couponCode='.$CouponCode;
                        }


                        $orderUrl ="https://api.connectreseller.com/ConnectReseller/ESHOP/TransferOrder/?".$query;
                        $orderUrl = trim($orderUrl);
                        $orderUrl = str_replace ( ' ', '%20', $orderUrl);                   
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $orderUrl);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                        $orderResponse = curl_exec($ch);
                        if($errno = curl_errno($ch)) {
                            $error_message = curl_strerror($errno);
                            echo "cURL error ({$errno}):\n {$error_message}";
                            exit;
                        }
                        curl_close($ch);
                        $orderRes = json_decode($orderResponse,true);
                        if($orderRes['responseMsg']['statusCode']!=200){
                            $values["error"] = $orderRes['responseMsg']['statusCode']." - ".$orderRes['responseMsg']['message'];
                        }else{
                            if($orderRes['responseData']['statusCode']!=200){
                                $values["error"] = $orderRes['responseData']['statusCode']." - ".$orderRes['responseData']['message'];
                            }else{
                                $values["result"] = "success";
                            }    
                        }
                    }catch (Exception $e) {
                        $values['error'] = "An error occurred: " . $e->getMessage();
                    }
                }
            } else {
                $UserName = $res['responseData']['userName'];
                $CustomerID = $res['responseData']['clientId'];
                try{  
                    $dataArr =array(
                        'Id' => intval($CustomerID),
                        'OrderType' => 4,
                        'APIKey' =>$ApiKey,
                        'Websitename' => $websitename,
                        'AuthCode' => $authCode
                    );
                    $query =http_build_query($dataArr);
                    // $premiumEnabled = (bool) $params['premiumEnabled']==true?1:0;
                    // $query .='&isEnablePremium='.$premiumEnabled;
                    if (!(!isset($CouponCode) || trim($CouponCode) === '')){
                       $query .= '&couponCode='.$CouponCode;
                    }

                    $orderUrl ="https://api.connectreseller.com/ConnectReseller/ESHOP/TransferOrder/?".$query;
                   
                    $orderUrl = trim($orderUrl);
                    $orderUrl = str_replace ( ' ', '%20', $orderUrl);
                    $query = $query.'&IsWhoisProtection='.$IsWhoisProtection;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $orderUrl);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                    $orderResponse = curl_exec($ch);
                    if($errno = curl_errno($ch)) {
                        $error_message = curl_strerror($errno);
                        echo "cURL error ({$errno}):\n {$error_message}";
                        exit;
                    }
                    curl_close($ch);
                    $orderRes = json_decode($orderResponse,true);

                    if($orderRes['responseMsg']['statusCode']!=200){
                        $values["error"] = $orderRes['responseMsg']['statusCode']." - ".$orderRes['responseMsg']['message'];
                    }else{
                        if($orderRes['responseData']['statusCode']!=200){
                            $values["error"] = $orderRes['responseData']['statusCode']." - ".$orderRes['responseData']['message'];
                        }else{
                            $values["result"] = "success";
                        }    
                    }
                }
                catch (Exception $e) {
                    $values['error'] = "An error occurred: " . $e->getMessage();
                }
            }
        }else{
            $values["error"] = $res['statusCode']." - Domain Transfer Failure - ".$res['responseText'];
        }
        return $values;
    }
    function connectreseller_RenewDomain($params){

        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $regperiod =$params['regperiod'];
        $CouponCode =$params["CouponCode"];
        $IsWhoisProtectionFalse ="false";
        $IsWhoisProtection = $params["idprotection"]==1?true:$IsWhoisProtectionFalse;
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $msgResult = array_key_exists ( "responseMsg" ,$res );
        if($msgResult){

            if($res["responseMsg"]['statusCode']!='200'){
                $values["error"] = $res["responseMsg"]['message'];  
            }else{ 
                $CustomerId = $res["responseData"]['customerId'];
                $query = 'APIKey='.$ApiKey.'&Websitename='.$sld.'.'.$tld.'&OrderType=2&Duration='.$regperiod.'&Id='.$CustomerId.'&IsWhoisProtection='.$IsWhoisProtection;
                $premiumEnabled = (bool) $params['premiumEnabled']==true?1:0;
                $query .='&isEnablePremium='.$premiumEnabled;
                if (!(!isset($CouponCode) || trim($CouponCode) === '')){
                   $query .= '&couponCode='.$CouponCode;
                }

                $renewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/renewalorder/?".$query;
                $renewDomainurl = trim($renewDomainurl);
                $renewDomainurl = str_replace ( ' ', '%20', $renewDomainurl);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $renewDomainurl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                $response1 = curl_exec($ch);
                if($errno = curl_errno($ch)) {
                    $error_message = curl_strerror($errno);
                    echo "cURL error ({$errno}):\n {$error_message}";
                    exit;
                }
                curl_close($ch);
                $res1 =json_decode($response1, true);
                if($res1["responseMsg"]['statusCode']!='200'){
                    $values["error"] = $res1["responseMsg"]['statusCode']." - ".$res1["responseMsg"]['message'];
                }else{
                    if($res1["responseData"]['statusCode']!=1000){
                        $values["error"] = $res1["responseData"]['statusCode']." - ".$res1["responseData"]['message'];
                    }else{
                        $values["result"] = "success";
                    }
                }
            }
        }else{
            $values["error"] = $res['statusCode']." - Domain Renewal Failure - ".$res['responseText'];
        }
        return $values;
    }
    function connectreseller_GetContactDetails($params){

        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];    
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $regperiod =$params['regperiod'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);

        $RegistrantContactId = $res["responseData"]['registrantContactId'];
        $GetContactDetails = 'https://api.connectreseller.com/ConnectReseller/ESHOP/ViewRegistrant?APIKey='.$ApiKey.'&RegistrantContactId='.$RegistrantContactId;
        $GetContactDetails = trim($GetContactDetails);
        $GetContactDetails = str_replace ( ' ', '%20', $GetContactDetails);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $GetContactDetails);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $contactDetailsResponse = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $contactDetailsRes = json_decode($contactDetailsResponse, true);
        $result = array();
        $result['name'] = $contactDetailsRes["responseData"]['name'];
        $result['Company'] = $contactDetailsRes["responseData"]['companyName'];
        $result['Address1'] = $contactDetailsRes["responseData"]['address1'];
        $result['Address2'] = $contactDetailsRes["responseData"]['address2'];;
        $result['Address3'] = $contactDetailsRes["responseData"]['address3'];
        $result['City'] = $contactDetailsRes["responseData"]['city'];
        $result['State'] = "Maharashtra";
        $result['Country'] = "India";
        $result['Zip'] = $contactDetailsRes["responseData"]['postalCode'];
        $result['PhoneNo_CountryCode'] = $contactDetailsRes["responseData"]['phoneCode'];
        $result['PhoneNo'] = $contactDetailsRes["responseData"]['phoneNo'];
        $result['PhoneNo'] = substr($result['PhoneNo'], 0, 10);
        $result['emailaddr'] = $contactDetailsRes["responseData"]['emailAddress'];
        $values['Registrant'] = array( 'Full Name' => $result['name'], 'Email' => $result['emailaddr'], 'Company Name' => $result['Company'], 'Address 1' => $result['Address1'], 'Address 2' => $result['Address2'], 'Address 3' => $result['Address3'], 'City' => $result['City'], 'State' => $result['State'], 'Country' => $result['Country'], 'Postcode' => $result['Zip'], 'Phone Number' => $result['PhoneNo_CountryCode'] . $result['PhoneNo']  );
        
        return $values;
    }
    function connectreseller_SaveContactDetails($params){
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $regperiod = $params['regperiod'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $msgResult = array_key_exists ( "responseMsg" ,$res );
        if($msgResult){
            $RegistrantContactId = $res["responseData"]['registrantContactId'];
            $query = 'APIKey='.$ApiKey;
            $query .= '&Id='.$res["responseData"]['customerId'];
            $query .='&EmailAddress='.$params['contactdetails']['Registrant']['Email'];
            $query .='&Name='.$params['contactdetails']['Registrant']['Full Name'];
            $query .='&Address1='.$params['contactdetails']['Registrant']['Address 1'];
            $query .='&Address2='.$params['contactdetails']['Registrant']['Address 2'];
            $query .='&Address3='.$params['contactdetails']['Registrant']['Address 3'];
            $query .='&City='.$params['contactdetails']['Registrant']['City'];
            $query .='&StateName='.$params['contactdetails']['Registrant']['State'];
            $query .='&CountryName='.$params['contactdetails']['Registrant']['Country'];
            $query .='&PhoneNo_cc=91';
            $query .='&PhoneNo='.$params['contactdetails']['Registrant']['Phone Number'];
            $query .='&Zip='.$params['contactdetails']['Registrant']['Postcode'];
            $query .='&CompanyName='.$params['contactdetails']['Registrant']['Company Name'];
            $query .='&domainId='.$res["responseData"]['domainNameId'];
            
            $SaveContactDetails ="https://api.connectreseller.com/ConnectReseller/ESHOP/ModifyRegistrantContact_whmcs?".$query;
            
            $SaveContactDetails = trim($SaveContactDetails);
            $SaveContactDetails = str_replace ( ' ', '%20', $SaveContactDetails);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $SaveContactDetails);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $updateResponse = curl_exec($ch);
            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
                exit;
            }
            curl_close($ch);
            $updateRes =json_decode($updateResponse, true);
           
            if($updateRes["responseMsg"]['statusCode']!='200'){     
                $values["error"] = $updateRes["responseMsg"]['message'];
            }
        }else{
            if($updateRes["responseMsg"]['statusCode']!='200'){     
                $values["error"] = $updateRes["responseMsg"]['message'];
            }
        }
        return $values;
    }
    function connectreseller_GetEPPCode($params) {
        
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $websitename = $sld.'.'.$tld;
        $query = 'APIKey='.$ApiKey.'&websiteName='.$websitename;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        if($res["responseMsg"]['statusCode']!='200'){
            $values["error"] = $res["responseMsg"]['statusCode'].$res["responseMsg"]['message'];
        } else {
            $eppcode = $res["responseData"]["authCode"];
            $values["eppcode"] = $eppcode;
            
        }
        return $values;
    }
    function connectreseller_RegisterNameserver($params){

        $tld = $params["tld"]; 
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename =$params['domainname'];
        $ipaddress = $params["ipaddress"];
        $Server = $params["nameserver"];

        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $domainNameId = $res["responseData"]['domainNameId'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$websitename.'&domainNameId='.$domainNameId.'&hostName='.$Server.'&ipAddress='.$ipaddress;
        $addChildUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/AddChildNameServer/?".$query;
        $addChildUrl = trim($addChildUrl);
        $addChildUrl = str_replace ( ' ', '%20', $addChildUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $addChildUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $addChildResponse = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $addChildRes =json_decode($addChildResponse, true);
            if($addChildRes["responseMsg"]['statusCode']!='200'){
                if($addChildRes["responseData"]['msgCode']!=2302){  
                    $values["error"] = $addChildRes["responseData"]['msgCode']." - Invalid IP Address";
                }else{
                    $values["error"] = $addChildRes["responseData"]['msgCode']." - Nameserver already exits";
                }
            
        }else{
            $values["error"]='Created Successfully '.$Server;
        }
        return $values;
    }
    function connectreseller_ModifyNameserver($params){
        $tld = $params["tld"]; 
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];
        $BrandId = $params['BrandId'];
        $websitename =$params['domainname'];
        $ipaddress = $params["ipaddress"];
        $Server = $params["nameserver"];  
        $currentipaddress = $params["currentipaddress"];
        $newipaddress = $params["newipaddress"]; 
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $domainNameId = $res["responseData"]['domainNameId'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$websitename.'&domainNameId='.$domainNameId.'&hostName='.$Server.'&oldIpAddress='.$currentipaddress.'&newIpAddress='.$newipaddress;
        $modifyChildUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ModifyChildNameServerIP/?".$query;
        $modifyChildUrl = trim($modifyChildUrl);
        $modifyChildUrl = str_replace ( ' ', '%20', $modifyChildUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $modifyChildUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $modifyChildResponse = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $modifyChildRes =json_decode($modifyChildResponse, true);

        if($modifyChildRes["responseMsg"]['statusCode']!='200'){
            if($modifyChildRes["responseData"]['msgCode']==2303){
                $values["error"] = $modifyChildRes["responseData"]['msgCode']." - Nameserver does not exist";
            }else if($modifyChildRes["responseData"]['msgCode']==2304){
                $values["error"] = $modifyChildRes["responseData"]['msgCode']." - Domain is locked";
            }else{
                $values["error"] = $modifyChildRes["responseData"]['msgCode']." - Invalid Ipaddress ";
            }
            
        }else{
            $values["error"]='Updated Successfully '.$Server;
        }
        return $values;
    }
    function connectreseller_DeleteNameserver($params){

        $tld = $params["tld"]; 
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];    
        $BrandId = $params['BrandId'];
        $websitename =$params['domainname'];
        $Server = $params["nameserver"];  
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $res =json_decode($response, true);
        $domainNameId = $res["responseData"]['domainNameId'];
     
        $query = 'APIKey='.$ApiKey.'&websiteName='.$websitename.'&domainNameId='.$domainNameId.'&hostName='.$Server;
        $deleteChildUrl = "https://api.connectreseller.com/ConnectReseller/ESHOP/DeleteChildNameServer/?".$query;
        $deleteChildUrl = trim($deleteChildUrl);
        $deleteChildUrl = str_replace ( ' ', '%20', $deleteChildUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $deleteChildUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $deleteChildResponse = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $deleteChildRes =json_decode($deleteChildResponse, true);
        if($deleteChildRes["responseMsg"]['statusCode']!='200'){
            if($deleteChildRes["responseData"]['msgCode']==2303){
                $values["error"] = $deleteChildRes["responseData"]['msgCode']." - Nameserver does not exist";
            }else if($deleteChildRes["responseData"]['msgCode']==2304){
                $values["error"] = $deleteChildRes["responseData"]['msgCode']." - Domain is locked";
            }else{
                $values["error"] = $deleteChildRes["responseData"]['msgCode']." - Invalid Process ";
            }
            
        }else{
            $values["error"]='Deleted Successfully '.$Server;
        }  
        
        return $values;
    }
    function connectreseller_IDProtectToggle($params){
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey']; 
        
        if($params['protectenable']==1)
            $protectEnable ='true';
        else
            $protectEnable ='false';
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        
        // get the domainnameid from url    
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);

        $res =json_decode($response, true);
       
        $domainNameId = $res["responseData"]['domainNameId'];
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld.'&domainNameId='.$domainNameId.'&iswhoisprotected='.$protectEnable;
        $manageUrl = trim("https://api.connectreseller.com/ConnectReseller/ESHOP/ManageDomainPrivacyProtection/?".$query);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $manageUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $manageResponse = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $manageRes =json_decode($manageResponse, true);
        if($manageRes["responseMsg"]['statusCode']!='200'){
            $values["error"] = $manageRes["responseMsg"]['statusCode']." - ".$manageRes["responseMsg"]['message'];
        }
        return $values;
    }
    function connectreseller_TransferSync($params){
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey']; 
        $BrandId = $params['BrandId'];
        $websitename = $params['domain'];   
        $query = 'APIKey='.$ApiKey.'&domainName='.$websitename;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/syncTransfer/?".$query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        $result = array();
        $res =json_decode($response, true);
        if($res["responseMsg"]['statusCode']!='200'){
            $result['completed'] =false;
            $result['failed']=false;
            $result['error'] =$res["responseMsg"]['message'];
        }else{
            if($res["responseData"]['status']== 'completed'){
                $result['completed'] =true;
                $result['failed']=false;
                $result['expirydate'] =$date= date('Y-m-d', intval($res["responseData"]['expiryDate']/1000));
            }
            else if($res["responseData"]['status']== 'pending'){
                $result['completed'] =false;
                $result['failed']=false;
                
            } else{
                $result['completed'] =false;
                $result['failed']=true;
                $result['reason'] =$res["responseData"]['reason'];
            }
        }  
        curl_close($ch);
        return $result;
    }

    function connectreseller_Sync($params){

       
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey']; 
        $BrandId = $params['BrandId'];
        $websitename = $params['domain'];   
        $query = 'APIKey='.$ApiKey.'&websiteName='.$sld.'.'.$tld;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/ViewDomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        curl_close($ch);
        $result = array();
        $res =json_decode($response, true);
        $msgResult = array_key_exists ( "responseMsg" ,$res );
        if($msgResult){
            if($res["responseMsg"]['statusCode']!='200'){
                $values["error"] = $res["responseMsg"]['statusCode']." - ".$res["responseMsg"]['message'];
            } else {
                if($res["responseData"]["status"] == "Renewal Hold" || $res["responseData"]["status"] == "Pending Delete Restorable"  || $res["responseData"]["status"] == "Deleted" ){
                    $result['active'] =false;
                    $result['expired']=true;
                    $result['expirydate'] =$date= date('Y-m-d', intval($res["responseData"]['expirationDate']/1000));
                }else{
                    $result['active'] =true;
                    $result['expired']=false;
                    $result['expirydate'] =$date= date('Y-m-d', intval($res["responseData"]['expirationDate']/1000));
                }
                return $result;   
            }
        }else{
            $values["error"] = $res['statusCode']." - ".$res['statusText']." - ".$res['responseText'];
        }  
    }

    function connectreseller_CheckAvailability($params){

        
        $userIdentifier = $params['API Username'];
        $apiKey = $params['API Key'];
        $testMode = $params['Test Mode'];
        $accountMode = $params['Account Mode'];
        $emailPreference = $params['Email Preference'];
        $additionalInfo = $params['Additional Information'];
        $searchTerm = $params['searchTerm'];
        $tldsToInclude = $params['tldsToInclude'];
        $premiumEnabled = (bool) $params['premiumEnabled']==true?1:0;
        $tld = $params["tld"];
        $sld = $params["sld"];
        $ApiKey = $params['APIKey'];   
        $BrandId = $params['BrandId'];
        $websitename = $sld.'.'.$tld;
        $tldsToInclude =implode(",",$params['tldsToInclude']);
        $query = 'APIKey='.$ApiKey.'&searchString='.$sld.'&tldsInclude='.$tldsToInclude.'&premiumEnable='.$premiumEnabled;
        $viewDomainurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/whmcscheckdomain/?".$query;
        $viewDomainurl = trim($viewDomainurl);
        $viewDomainurl = str_replace ( ' ', '%20', $viewDomainurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $viewDomainurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
         $results = new ResultsList();
        $res =json_decode($response, true);
        try {
            foreach ($res["responseData"] as $domain) {
                $arr =explode(".",$domain['domain'],2);
               
                $searchResult = new SearchResult($arr[0], ".".$arr[1]);
                if ($domain['status'] == 'available') {
                    $status = SearchResult::STATUS_NOT_REGISTERED;
                } elseif ($domain['status'] == 'registered') {
                    $status = SearchResult::STATUS_REGISTERED;
                } elseif ($domain['status'] == 'reserved') {
                    $status = SearchResult::STATUS_RESERVED;
                } else {
                    $status = SearchResult::STATUS_TLD_NOT_SUPPORTED;
                }
                $searchResult->setStatus($status);
                if($params['premiumEnabled']){
                    if ($domain['premium']) {
                        $searchResult->setPremiumDomain(true);
                        $searchResult->setPremiumCostPricing(
                            array(
                                'register' => $domain['price'],
                                'renew' => $domain['renewalPrice'],
                                'CurrencyCode' => $domain['currencyCode'],
                            )
                        );
                    }
                }
                
                $results->append($searchResult);
            }
            return $results;
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }


    function connectreseller_GetTldPricing($params){

        
 
        $ApiKey = $params['APIKey'];
        $query = 'APIKey='.$ApiKey;
        $tldsyncurl = "https://api.connectreseller.com/ConnectReseller/ESHOP/tldsync/?".$query;
        $tldsyncurl = trim($tldsyncurl);
        $tldsyncurl = str_replace ( ' ', '%20', $tldsyncurl);
      //  print_r($tldsyncurl);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tldsyncurl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }
        curl_close($ch);
        $results = new ResultsList();
        $res =json_decode($response, true);
       
        try {
            foreach ($res as $extension) {
                // All the set methods can be chained and utilised together.

                $item = (new ImportItem)
                    ->setExtension($extension['tld'])
                    ->setMinYears($extension['minPeriod'])
                    ->setMaxYears($extension['maxPeriod'])
                    ->setRegisterPrice($extension['registrationPrice'])
                    ->setRenewPrice($extension['renewalPrice'])
                    ->setTransferPrice($extension['transferPrice'])
                    ->setCurrency($extension['currencyCode']);

                 $results[] = $item;
            }
            return $results;
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }
?>