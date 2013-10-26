<?php 

if( !($passwd = @fopen( "./.htpasswd", "r" ))) {  
	 echo "Cannot open password file."; 
	 exit; 
} 

if (!isset($_SERVER['PHP_AUTH_USER'])) {  
	Header( "WWW-authenticate: basic realm=\"Realm\"" ); 
	Header( "HTTP/1.0 401 Unauthorized" ); 
	echo "Text to see if user hits 'Cancel'"; 
	exit; 
} 

while( $pwent = fgets( $passwd, 100 )) {

	$part = explode( ":", chop($pwent));
	$pass = explode( "\$", $part[1]);

	$plainpasswd=$_SERVER['PHP_AUTH_PW'];
	$salt=$pass[2];
	$len = strlen($plainpasswd);
	$text = $plainpasswd.'$apr1$'.$salt;
	$bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
	for($i = $len; $i > 0; $i -= 16) { 
        $text .= substr($bin, 0, min(16, $i)); 
    }
	for($i = $len; $i > 0; $i >>= 1) { 
        $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; 
    }
	$bin = pack("H32", md5($text));
	for($i = 0; $i < 1000; $i++) {
		 $new = ($i & 1) ? $plainpasswd : $bin;
		 if ($i % 3) $new .= $salt;
		 if ($i % 7) $new .= $plainpasswd;
		 $new .= ($i & 1) ? $bin : $plainpasswd;
		 $bin = pack("H32", md5($new));
	}
	$tmp="";
	for ($i = 0; $i < 5; $i++) {
		 $k = $i + 6;
		 $j = $i + 12;
		 if ($j == 16) $j = 5;
		 $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
	}
	$tmp = chr(0).chr(0).$bin[11].$tmp;
	$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
	   "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
	   "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
	$hashedpasswd = "$"."apr1"."$".$salt."$".$tmp;

	if (($_SERVER['PHP_AUTH_USER'] == $part[0]) && ($hashedpasswd == $part[1])){
		#echo "Now you are Logged In";
		#exit;
	}

}

// This only has effect of no text was output previously, so 
//  it is ignored in all cases except authentication error. 
Header( "HTTP/1.0 401 Unauthorized" ); 
echo "No Match";
?>