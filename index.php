<?php
//VPNonDemand-Script
//written by Arnaud Feld <mail@arnaudfeld.de>
// found on Github ( https://github.com/jonathanjdavis/iosmailprofile ):
// based on a Script from	
//			Jonathan Davis <davis@snickers.org>
//          Jon Nistor <nistor@snickers.org>
// Orginal-Purpose:	Generate snickers.org profile entries in iOS
// New-Purpose: Generate VPNonDemand-Profil for iOS

$o_vpnuser		= NULL;
$o_vpnpasswort	= NULL;
$o_Dyndns 	= NULL;
$o_SharedSecret	= NULL;
$o_gWlan1	= NULL;
$o_gWlan2	= NULL;
$o_gWlan3	= NULL;
$o_gWlan4	= NULL;


$o_DomainIP1	= NULL;
$o_DomainIP2	= NULL;
$o_DomainIP3	= NULL;
$o_DomainIP4	= NULL;


$generate	= TRUE;

function gen_uuid()
{
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

if( isset($_POST) )
{

    if(!empty($_POST['vpnuser']))
    {
        $o_vpnuser = trim(strip_tags ($_POST['vpnuser']));
      } else {
        $generate = FALSE;
        $o_vpnuser_m = 'class="missing" ';
    }


    if(!empty($_POST['vpnpasswort']))
    {
    $o_vpnpasswort = trim(strip_tags ($_POST['vpnpasswort']));
      } else {
       //$generate = FALSE;
       //$o_vpnpasswort_m = 'class="missing" ';
     }

    if(!empty($_POST['Dyndns']))
    {
        $o_Dyndns = trim(strip_tags ($_POST['Dyndns']));

        if (strpos($o_Dyndns, ' ') > 0) {
            $generate = FALSE;
            $o_Dyndns_m = 'class="missing" ';
        }

      } else {
        $generate = FALSE;
        $o_Dyndns_m = 'class="missing" ';
    }


    if(!empty($_POST['SharedSecret']))
    {
        $o_SharedSecret = trim(strip_tags ($_POST['SharedSecret']));
      } else {
        //$generate = FALSE;
        //$o_SharedSecret_m = 'class="missing" ';
    }

    if(!empty($_POST['gWlan1']))
    {
        $o_gWlan1 = trim(strip_tags ($_POST['gWlan1']));
      } else {
        $generate = FALSE;
        $o_gWlan1_m = 'class="missing" ';
    }
	 

    if(!empty($_POST['gWlan2']))
    {
        $o_gWlan2 = trim(strip_tags ($_POST['gWlan2']));
      } else {
        //$generate = FALSE;
        //$o_gWlan2_m = 'class="missing" ';
    }
	 

    if(!empty($_POST['gWlan3']))
    {
        $o_gWlan3 = trim(strip_tags ($_POST['gWlan3']));
      } else {
        //$generate = FALSE;
        //$o_gWlan3_m = 'class="missing" ';
    }
	 

    if(!empty($_POST['gWlan4']))
    {
        $o_gWlan4 = trim(strip_tags ($_POST['gWlan4']));
      } else {
        //$generate = FALSE;
        //$o_gWlan4_m = 'class="missing"';
    }
	 

    if(!empty($_POST['DomainIP1']))
    {
        $o_DomainIP1 = trim(strip_tags ($_POST['DomainIP1']));
      } else {
        $generate = FALSE;
        $o_DomainIP1_m = 'class="missing" ';
    }
	 

    if(!empty($_POST['DomainIP2']))
    {
        $o_DomainIP2 = trim(strip_tags ($_POST['DomainIP2']));
      } else {
        //$generate = FALSE;
        //$o_DomainIP2_m = 'class="missing" ';
    }
	 

    if(!empty($_POST['DomainIP3']))
    {
        $o_DomainIP3 = trim(strip_tags ($_POST['DomainIP3']));
      } else {
        //$generate = FALSE;
        //$o_DomainIP3_m = 'class="missing" ';
    }
	 

    if(!empty($_POST['DomainIP4']))
    {
        $o_DomainIP4 = trim(strip_tags ($_POST['DomainIP4']));
      } else {
        //$generate = FALSE;
        //$o_DomainIP4_m = 'class="missing" ';
    }
	
    if( $generate )
    {
      $o_uuid1	= gen_uuid();
      $o_uuid2	= gen_uuid();
    }
} else {

$generate = FALSE;

}


$html = <<< EOHTMLF
<!DOCTYPE html>
<html lang="de-DE">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta name="author" content="Arnaud Feld mail@arnaudfeld.de">
<title>VPNonDemand Profile Generator for iOS</title>
<link rel="stylesheet" href="default.css">
</head>
<body>
<h1>VPNonDemand Profile Generator for iOS v1.0.3.3</h1>
<p>Diese Seite erstellt ein VPNonDemand Profil für euer iOS-Device</p>
<p><a href="#FAQ">FAQ findet ihr unten</a></p>
<p>Bitte gebt eure VPN-Einstellungen ein:</p>
<p>Pflichteingaben sind <font color="white"><span style="background-color:orangered;">Rot</span></font> makiert!</p>
<p>Die Erstellung des Profils läuft komplett im Browser ab. Es werden keine Daten auf dem Server gespeichert.</p>
<!--<br>
<p>Please enter the your VPN-information:</p>
<p>This page generates a VPNonDemand Profile for your iOS-Device</p> -->
<form name="profile_info" action="{$_SERVER['PHP_SELF']}" method="post">
<ul>
    <li>
        <label for="vpnuser" {$o_vpnuser_m}>Benutzername VPN-Users:</label>
        <input type="text" name="vpnuser" value="{$o_vpnuser}">(e.g. Arnaud-VPN)
    </li>
    <li>
        <label for="vpnpasswort"{$o_vpnpasswort_m}>Passwort VPN-Users:</label>
        <input type="text" name="vpnpasswort" value="{$o_vpnpasswort}">
    </li>
<li>
        <label for="Dyndns"{$o_Dyndns_m}>Eure DynDNS/MyFritz-Adresse:</label>
        <input type="text" name="Dyndns" value="{$o_Dyndns}">(z.B.: xxxxx.myfritz.net)
    </li>
<li>
        <label for="SharedSecret"{$o_SharedSecret_m}>Shared Secret aus der FritzBox:</label>
        <input type="text" name="SharedSecret" value="{$o_SharedSecret}">(wird von eurer FB vorgegeben)
    </li>
<li>
        <label for="gWlan1"{$o_gWlan1_m}>WLANs wo kein VPN aufgebaut wird:</label>
        <input type="text" name="gWlan1" value="{$o_gWlan1}">(z.B.: MeinWLAN-Zuhause)
    </li>
<li>
        <label for="gWlan2"{$o_gWlan2_m}>WLANs wo kein VPN aufgebaut wird:</label>
        <input type="text" name="gWlan2" value="{$o_gWlan2}">
    </li>
<li>
        <label for="gWlan3"{$o_gWlan3_m}>WLANs wo kein VPN aufgebaut wird:</label>
        <input type="text" name="gWlan3" value="{$o_gWlan3}">
    </li>
    <li>
        <label for="gWlan4"{$o_gWlan4_m}>WLANs wo kein VPN aufgebaut wird:</label>
        <input type="text" name="gWlan4" value="{$o_gWlan4}">
    </li>
<li>
        <label for="DomainIP1"{$o_DomainIP1_m}>DomainIP1:</label>
        <input type="text" name="DomainIP1" value="{$o_DomainIP1}">(bei welchen Domains oder IPs wird das VPN aufgebaut)
    </li>

    <li>
        <label for="DomainIP2"{$o_DomainIP2_m}>DomainIP2</label>
        <input type="text" name="DomainIP2" value="{$o_DomainIP2}">
    </li>

    <li>
        <label for="DomainIP3"{$o_DomainIP3_m}>DomainIP3:</label>
        <input type="text" name="DomainIP3" value="{$o_DomainIP3}">
    </li>
    <li>
        <label for="DomainIP4"{$o_DomainIP4_m}>DomainIP4:</label>
        <input type="text" name="DomainIP4" size="32" value="{$o_DomainIP4}">
    </li>
    <li class="submit">
         <input type="submit" value="Erstellt euer Profil" >
    </li>
</ul>
</form>
<p>:-)</p>

<h2><b id="FAQ">FAQ</b></h2>
<p><b><i>Wie deinstalliere ich das Profil?</i></b></p>
<p>Einstellung -> Allgemein -> Profile ,dort dann das VPN-Profil auswählen und deinstallieren.</p>
<br>
<p><b><i>Werden Daten auf Server gespeichert?</i></b></p>
<p>Nein.</p>
<br>
<p><b><i>ToDo's:</i></b></p>
<p><li>Multi-Language</li></p>
<p><li>Domains/IPs variabel dazu klicken (wenn der eine nur 1, der andere 5 hinterlegen möchte)</li></p>
<hr />
<p>Zurück zu <a href="https://arnaudfeld.de">meinem</a> Blog.</p>
<p class="right">Based on a Script from <a href="http://github.com/notdavis/iosmailprofile">Jonathan Davis (davis@snickers.org) & Jon Nistor (nistor@snickers.org) </a></p>
</body>
</html>
EOHTMLF;

$xml = <<< EOXMLF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple Computer//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>PayloadContent</key>
	<array>
		<dict>
			<key>IPSec</key>
		<dict>
			<key>AuthenticationMethod</key>
			<string>SharedSecret</string>
			<key>XAuthEnabled</key>
				<integer>1</integer>
				<key>XAuthName</key>
				<string>{$o_vpnuser}</string>
				<key>XAuthPassword</key>
				<string>{$o_vpnpasswort}</string>
				<key>OnDemandEnabled</key>
			<integer>1</integer>
			<key>OnDemandRules</key>
			<array>
			<dict>
				<key>InterfaceTypeMatch</key>
				<string>WiFi</string>
				<key>SSIDMatch</key>
				<array>
				<string>{$o_gWlan1}</string>
				<string>{$o_gWlan2}</string>
				<string>{$o_gWlan3}</string>
				<string>{$o_gWlan4}</string>
				</array>
				<key>Action</key>
				<string>Disconnect</string>
			</dict>
			<dict>
				<key>Action</key>
				<string>EvaluateConnection</string>
				<key>ActionParameters</key>
				<array>
				<dict>
					<key>Domains</key>
					<array>
					<string>{$o_DomainIP1}</string>
					<string>{$o_DomainIP2}</string>
					<string>{$o_DomainIP3}</string>
					<string>{$o_DomainIP4}</string>
					</array> 
					<key>DomainAction</key>
					<string>ConnectIfNeeded</string>
				</dict>
				</array>
			</dict>
			</array>
			<key>LocalIdentifier</key>
			<string>{$o_vpnuser}</string>
			<key>LocalIdentifierType</key>
			<string>KeyID</string>
			<key>RemoteAddress</key>
			<string>{$o_Dyndns}</string>
			<key>SharedSecret</key>
			<string>{$o_SharedSecret}</string>
		</dict>
			<key>PayloadDescription</key>
			<string>Konfiguiert das VPNonDemand für euer iOS-Device</string>
			<key>PayloadDisplayName</key>
			<string>VPNonDemand {$o_Dyndns}</string>
			<key>PayloadIdentifier</key>
			<string>VPNonDemand {$o_Dyndns}</string>
			<key>PayloadOrganization</key>
			<string>VPNonDemand {$o_Dyndns}</string>
			<key>PayloadType</key>
			<string>com.apple.vpn.managed</string>
			<key>PayloadUUID</key>
			<string>{$o_uuid1}</string>
			<key>PayloadVersion</key>
			<integer>1</integer>
			<key>UserDefinedName</key>
			<string>VPNonDemand {$o_Dyndns}</string>
			<key>VPNType</key>
			<string>IPSec</string>
		</dict>
	</array>
	<key>PayloadDescription</key>
	<string>myVPNonDemand-Profil {$o_Dyndns}</string>
	<key>PayloadDisplayName</key>
	<string>VPNonDemand</string>
	<key>PayloadIdentifier</key>
	<string>VPNonDemand-Profil</string>
	<key>PayloadOrganization</key>
	<string>erstellt mit https://zum-en.de/VPN</string>
	<key>PayloadType</key>
	<string>Configuration</string>
	<key>PayloadUUID</key>
	<string>{$o_uuid2}</string>
	<key>PayloadVersion</key>
	<integer>1</integer>
</dict>
</plist>

EOXMLF;

if($generate) {
    // header("Content-type: text/plain");
    // Modified per: http://www.rootmanager.com/iphone-ota-configuration/iphone-ota-setup-with-signed-mobileconfig.html
    header('Content-type: application/x-apple-aspen-config; chatset=utf-8');
    header('Content-Disposition: attachment; filename="MyVPNonDemand.mobileconfig"');
    echo $xml;

} else {
    echo $html;
}
?>