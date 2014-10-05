
<?php


echo '<h4>Installed Apps</h4>';

$found = array();

$controllers = Naterweb\Engine\Applications::get_controllers();
print '<table class="table table-striped">';
print '<th>Name</th><th>Install Location</th>';
foreach ($controllers as $blogid=> $location) {
	print '<tr><td>'.$blogid.'</td><td>'.$location.'</td></tr>'."\n";
}

?>

</table>
<br />

<h4>Install Status</h4>
<table class="table table-striped">

    <th>Item</th><th>Status</th>
    <tr>
        <td>Dynamic Storage</td>
        <td>
        <?php
        
        $writetest = fopen( \Naterweb\Engine\Configuration::get_option('dynamic_directory').'/writetest.txt', 'w');
        fclose($writetest);

        # Check if dynamic directory is writeable
        if ( is_writable( \Naterweb\Engine\Configuration::get_option('dynamic_directory').'/writetest.txt') ){

	unlink( \Naterweb\Engine\Configuration::get_option('dynamic_directory').'/writetest.txt' );
	print 'Okay (dynamic storage is writeable)';
        }else{

	print '<font color="red">Problem: cannot write to ' . \Naterweb\Engine\Configuration::get_option('dynamic_directory') . '</font>';
        }
        ?>
            
        </td>
    </tr>
    <tr>
        
        <td>Log Storage</td>
        
        <td>
        
        <?php
        
        if (file_exists(NWEB_ROOT.'/var/log') && is_dir(NWEB_ROOT.'/var/log') ){
        $writetest = fopen( NWEB_ROOT.'/var/log/writetest.txt', 'w');
        fclose($writetest);
        }

        # Check if dynamic directory is writeable
        if ( is_writable( NWEB_ROOT.'/var/log/writetest.txt') ){

	unlink( NWEB_ROOT.'/var/log/writetest.txt' );
	print 'Okay (log storage is writeable)';
        }else{

            print '<font color="red">Problem: cannot write to '.NWEB_ROOT.'/var/log</font>';
        }
        
        ?>
            
        </td>
        
        
    </tr>
    
    <tr>
        <td>Site Domain</td>
        <td><?php echo \Naterweb\Engine\Configuration::get_option('site_domain'); ?></td>
    </tr>
    <tr>
        <td>Enable Database (disabled if no status shown)</td>
        <td><?php echo \Naterweb\Engine\Configuration::get_option('use_db'); ?></td>
    </tr>
    
    <tr>
        <td>Timezone</td>
        <td><?php echo date_default_timezone_get(); ?></td>
    </tr>
    
    <tr>
        <td>Feed Type</td>
        <td><?php echo \Naterweb\Engine\Configuration::get_option('feed_type'); ?></td>
    </tr>

</table>
