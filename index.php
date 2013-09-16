<?php
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
include GNAT_ROOT.'/lib/core_web.php';
#print $_GET['url'];
$requestUri = explode( '/', $_GET['url'] );
#unset( $requestUri[0] );
#print_r( $requestUri );

$requestUri = array_values( $requestUri );

if ( $requestUri[0] == 'page' || $requestUri[0] == '' ){
        $sectionId = 'mainsite';
        if ( count( $requestUri ) > 1 )
                $_GET['id'] = $requestUri[1];
        else
                $_GET['id'] = '';
} else {
        $_GET['controller'] = array_shift( $requestUri );
        $sessionMvc = new session( array( 'controller' ) );
        for( $i = 0; $i < count( $requestUri ); $i+=2 ){
                $value = "";
                $key = $requestUri[$i];
                if ( $i+1 < count( $requestUri ) ){
                        $j = $i + 1;
                        $value = $requestUri[$j];
 
                }

                $_GET[$key] = $value;
        }
        $sectionId = $sessionMvc->controller;
}
#print $sectionId;
#print_r( $_GET );
include GNAT_ROOT.'/config/template.d/page_template.php';

?>
