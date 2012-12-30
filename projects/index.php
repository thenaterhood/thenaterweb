<?php 

function safeChars($string, $length) {
    /*
     * Verify that a string is made of html-safe characters and
     * short enough to fit where it belongs.  Basically some simple
     * input sanitizing for nonsecure things.
     * 
     * Arguments:
     *  $string (string): a string or something else
     *  $length (integer): an integer value for the length limit of the string
     * 
     * Returns:
     *  $safestring (string): a html-safe and proper length string
     */
     
    # Check that the string is actually a string, return "" if not
    if (gettype($string) != 'string'){
        return '';
    }
    
    #Santize input so that it's text so we don't have XSS problems
    $safestring = htmlspecialchars($string, ENT_QUOTES);
    
    #Check the length of the string and the limit given, truncate if needed
    if ($length == 0){
        return $safestring;
    }
    if (strlen($safestring) > $length){
        return substr($safestring, 0, $length);
    }
    else {
        return $safestring;
    }
}

function setIfEmpty($string, $emptyValue){
	/*
	 * Checks if a given string is empty and returns the value to set
	 * it as if it is.  if not, returns the string.
	 *
	 * Arguments:
	 *	$string (string): string value to check
	 *	$emptyValue (string): Value to return if the string is empty
	 *
	 * Returns:
	 *	$string or $emptyValue (string): $string if the string is not empty
	 *		or $emptyValue if the string is empty
	 */
	if (empty($string)){
		return $emptyValue;
	}
	else{
		return $string;
	}
}

function checkCookie($name, $emptyValue){
	/*
	 * Checks the cookie with the given name and returns its contents,
	 * or a default value if the cookie is empty/doesn't exist
	 * 
	 *
	 * Arguments:
	 *	$name (string): the name of the cookie to check
	 *	$emptyValue (string): string to return if the cookie is bad
	 *
	 * Returns:
	 *	$contents (string): the contents of the cookie or default value
	 */
	$contents = $_COOKIE[$name];
	
	return setIfEmpty(&$contents, &$emptyValue);
}

function setVarFromURL($name, $emptyValue, $length){
	/*
	 * Sets a variable from the URL by running the URL input through
	 * safeChars to make it html-safe and the right size, then
	 * looking for a cookie if the variable has not been set, and 
	 * sets the variable to a default value if it has not been defined
	 * in the url or a cookie.
	 *
	 * Arguments:
	 *	$name (string): the name of the variable to get/set
	 *	$emptyValue (string): a default value for the variable if no
	 *		other value can be found
	 *	$length (int): a maximum length for the variable if pulled from URL
	 *
	 * Returns:
	 *	(string): the default value or the value pulled from a cookie or URL
	 */
	$value = safeChars($_GET[$name], &$length);
	return setIfEmpty(&$value, checkCookie(&$name, &$emptyValue));
}

# Grab variables from the URL. Syntax for this is...
# name of variable, default value of variable, maxlength of variable
$first_name = setVarFromURL('name', 'Guest', 42);
$track = setVarFromURL('track', '', 1);
$konami = setVarFromURL('konami', '', 0);
$id = setVarFromURL('id', 'projects', 15);

$current_domain = ";";
$current_domain = preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);


// Checks for cookies and sets them (or refreshes them) if necessary

setcookie('name',$first_name,time() + (86400 * 30),"/","$current_domain"); // 86400 = 1 day
setcookie('track',$track,time() + (86400 * 30),"/","$current_domain"); // 86400 = 1 day
// Sets page options and variables

$page_content_file = "page_$id.html";
$page_description_file = "page_$id_description.txt";
$page_keywords_file = "page_$id_keywords.txt";

if ("$track" == "n"){
	$tracking = "../template_no-track.php";
	}
else {
	$tracking = "../template_tracking.php";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php print '<meta name="keywords" content="nate levesque, thenaterhood" />'; ?>
<?php print '<meta name="description" content="Nate Levesque" />'; ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print "Nate Levesque :: $id"; ?>
</title>
<link href="/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css' />
<style type="text/css">
</style>
<?php include "$tracking"; ?>
</head>
<?php if ("$konami" == "pride") print '<style type="text/css">body {background: url(/images/rainbow.jpg) fixed}</style>'; ?>
<body>
<div id="wrapper">
<?php include "../template_header.php";?>
	<div id="page">
		<div id="content">
			<div class="post">
				<div style="clear: both;">&nbsp;</div>
				<div class="entry">
				<?php if ("$first_name" == "Guest") include "../template_introduction.php"; ?>
				<?php if (file_exists("$page_content_file")) include "$page_content_file"; else include "../template_error.php"; ?>
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
<?php include "../template_sidebar.php"; ?> 
	</div>
</div>
<?php include "../template_footer.php"; ?>
</body>
</html>


