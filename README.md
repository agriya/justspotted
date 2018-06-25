### Installation Steps

### Server Requirements

    # PHP Version - 5.2+ (preferably 5.3+)
        Extensions
            GD Version - 2.x+
            PCRE Version - 7.x+
            cURL version - 7.x+
            json version - 1.x+
            PDO
            Freetype
            mbstring
        php.ini settings
            max_execution_time - 180 (not mandatory)
            max_input_time - 6000 (not mandatory)
            memory_limit - 128M (at least 32M)
            safe_mode - off
            open_basedir - No Value
            display_error = On
            magic_quotes_gpc = Off
    # MySQL Version - 5.x
    # Apache - 1+ (preferably 2+)
        Modules
            mod_rewrite
            mod_deflate (not mandatory, but highly recommended for better performance–gzip)
            mod_expires (not mandatory, but highly recommended for better performance–browser caching)
    Recommended Linux distributions: Centos / Ubuntu / RedHat

### Initial Configurations

* Extract Files

		Unzip the zip file

Upload the unzipped files in server.

* Need write permission for following folders

(Need write permission for php/apache; can be chmod 655 or 755 or 777 depending upon server configuration)

    Make sure the permission as read,write and executable as recursively for the below directories

    app/media
    app/tmp
    app/webroot/js
    app/webroot/img
    app/webroot/css
    app/webroot/files
    app/vendors/shells/cron.sh
    core/cake/console/cake
    core/vendors/securimage

* Change following item in app/config/config.php

		$config['site']['domain'] = 'justspotted'; // change to your domain name (only name like "yourdomain"). also you need to set this only when site routing url is set as subdomain

### Updating site logo

There are few places where site logo are located. To change those logo, you need to replace your logo with exact name and resolution in the following mentioned directories.

* Site Logo

		app/webroot/img/logo.png                      - 318 x 58

* Favicon

    	app/webroot/favicon.ico                        - 16 x 16

### Configure Your Database

The sql file 'justspotted_with_empty_data.sql' is also attached, which is located in 'app/config/sql'. import the database through phpmyadmin or any other tool.

After importing the sql database, do not truncate any data directly from the database. All the data in the imported database are required. Removing unwanted cities can be done through administrator end which will be explained later in the following steps.

	In app/config/database.php, we need to change host, login, password, database. Update that in 4 places (For setting up master/slave setup, get professional help and it's not thoroughly tested)

	(
  		'host' => 'localhost',
  		'login' => 'dbuser',
  		'password' => 'dbpassword',
  		'database' => 'justspotted'
	)

### Configure Apache

* If you can reset 'DocumentRoot'

Reset your Apache DocumentRoot to /public_html/app/webroot/ by following means:

    If you're on dedicated host, reset DocumentRoot in httpd.conf with /public_html/app/webroot/
    If you're on shared host, reset your virtual directory to point to /public_html/app/webroot/

Note: This requirement is not mandatory, but highly preferred to skip the following tweaks in htaccess files.

* If you cannot reset 'DocumentRoot'

Installing site directly in the root e.g., http://yourdomain.com/

Again, no need to tweak 'htaccess' files.
Installing site as a sub-folder e.g., http://yourdomain.com/myfolder

    app/.htaccess ensure the RewriteBase as below:

RewriteBase    /myfolder/app/

    app/webroot/.htaccess ensure the RewriteBase as below:

RewriteBase	/myfolder/

### Verify Your Configuration

* Run Diagnostic tool

    Run the diagnostic tool http://yourdomain.com/diagnose.php and verify all permission has been set properly and all other requirements get met before running the site.

* Running site for the first time

Now run the site with http://yourdomain.com/ or http://yourdomain.com/myfolder
After successful running of the site, login as admin using the below details in login form.

      username: admin
      password: agriya

* To change administrator profile details, click 'My Account' in the top menu, then edit the profile information.
* To change administrator password, click 'Change Password' in the top menu, then change the password.

