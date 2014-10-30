<?php

$APPPATH = dirname(__FILE__).'/';
require_once '../post_fun.php';

$ourl = 'thunder://QUFmdHA6Ly9hOmFAZHguZGwxMjM0LmNvbTo4MDA2L1slRTclOTQlQjUlRTUlQkQlQjElRTUlQTQlQTklRTUlQTAlODJ3d3cuZHkyMDE4LmNvbV0lRTUlQkYlOEQlRTglODAlODUlRTclQTUlOUUlRTklQkUlOUYlRTUlOEYlOTglRTclQTclOEQlRTYlOTclQjYlRTQlQkIlQTNIRCVFNCVCOCVBRCVFOCU4QiVCMSVFNSU4RiU4QyVFNSVBRCU5Ny5ybXZiWlo=';

$url = convert_downurl($ourl);

echo urldecode($url['origin']),"\n";

