;<?php
;die();
;/*
; The main configuratino file for thenaterweb
timezone= America/New_York
; Configure the default site template.
; Some apps will observe this setting.
template= NWEB_ROOT/config/template.d/generic_template.php
; Configure whether or not to use a database.
; Some apps may have their own settings which
; will override this for the application.
use_db= False
; Configure the database engine and 
; credentials if they apply
engine_storage_db= sqlite
db_user=
db_password=
db_port=
db_name=
; The database error leve, 1 or 0.
; Use 1 for development (debug) and 0 for
; production
db_error_level= 1
; Set the file for storing dynamic files such
; as cache files
dynamic_directory= NWEB_ROOT/var/dynamic
; Configure how many posts should be displayed on
; a blog page
posts_per_page= 4
; Configure how many posts should be in the blog feed
max_feed_items= 100
; The domain name of the site
site_domain= http://192.168.1.220:8080
site_author= Nate Levesque
; Configure whether or not to use 'friendly' URLs.
; Normally URLs are of the form yourdomain.com/?url=...
; If you have mod_rewrite enabled and configured,
; you can enable this optionto instead have URLs of
; the form yourdomain.com/...
; In either mode, both URL schemes will work, this
; determines which one is used for building URLs
friendly_urls= false
; Sets which feed type to use (rss/atom)
feed_type= atom
; All the files that should be ignored when
; looking through directories
hidden_files[]= .
hidden_files[]= ..
hidden_files[]= index.php
hidden_files[]= error_log
hidden_files[]= .htaccess
; The default page to assume for apps
id= home

