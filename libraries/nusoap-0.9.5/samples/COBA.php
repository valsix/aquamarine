<?php
/*
 *	$Id: client1.php,v 1.3 2007/11/06 14:48:24 snichol Exp $
 *
 *	Client sample that should get a fault response.
 *
 *	Service: SOAP endpoint
 *	Payload: rpc/encoded
 *	Transport: http
 *	Authentication: none
 */
require_once('../lib/nusoap.php');
$sc = new nusoap_client('http://portalsi.pp3.co.id/wsouth.asmx?wsdl',TRUE);
$psc = array('xIDAPLIKASI'=>22,'xUsername'=>$usrLogin,'xPassword'=>$password);
$rsc = $sc->call('valLoginAkun', $psc);

echo $rsc['valLoginAkunResult']['USERNAME'];
?>
