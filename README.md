To get the blog running on your Pop!_OS machine with XAMPP, please follow these steps:

Download Files: Place all the project files into a new folder (e.g., my-blog) inside your XAMPP htdocs directory. The htdocs folder is typically located at /opt/lampp/htdocs/.

Install Composer: Composer is a dependency manager for PHP. If you don't have it installed in your project folder yet, open a terminal, navigate into your new my-blog folder, and run these commands:

/usr/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
/usr/bin/php composer-setup.php
/usr/bin/php -r "unlink('composer-setup.php');"
This will download a composer.phar file into your project directory.

Install Project Dependencies: Once Composer is ready, run the following command in the same terminal window to install the necessary libraries (like the Markdown parser):

/usr/bin/php composer.phar install
Start XAMPP and View Your Blog:

Start the Apache server from your XAMPP control panel.
To see the public blog: Open your web browser and go to http://localhost/my-blog/public/
To access the admin panel: Navigate to http://localhost/my-blog/admin/
The default password for the admin panel is password, which you can change in the config.php file.
