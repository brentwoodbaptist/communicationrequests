Brentwood Baptist Comm Req System
====================

General Information
--------------------

This package contains the communications request system used at Brentwood Baptist Church as of October 2013. It was custom built using PHP, MySQL, and the LiquidPlanner API. It uses the open-source projects [JQuery](http://jquery.com/), [Uploadify](http://www.uploadify.com/), [TinyMCE](http://www.tinymce.com/), [php-liquidplanner](https://github.com/jonoxer/php-liquidplanner), and [Tablesorter](http://tablesorter.com/). Read below for information on set-up.

Licensing Information
--------------------

License information to be added at a later date.

Database Set-Up
--------------------

In the database folder, there is a .sql file containing the database structure necessary for the project. Manually add departments with their corresponding LiquidPlanner client ID and users to the database. Once you have created the database on your MySQL server, find the connection file in Connections/commreq.php and add your hostname, database name, username, and password.

File Set-Up
--------------------

There are a few files where you will need to enter your LiquidPlanner credentials. You must enter your LiquidPlanner workspace ID, email address, and password into files. Those files are commreq.php on line 130, reporting/completed.php on line 64, and reporting/report.php on line 64. Scattered throughout the project is the email address projectmanager@yourchurch.com. You may want to find and replace it with your project manager's email address across all files.

Other File Info
--------------------

Because of incremental feature additions and design changes to the system, the main CSS file is located in reporting/css/main.css. For the same reason, most of the logos are located in reporting/images/. It's not perfectly organized, but I think you're probably smart enough to figure it out.

We use the comm req system for more than just communications requests (i.e. chilcare requests, tech requests, etc.) For some of these, we use [wufoo](http://www.wufoo.com) forms that I have removed from this package. feel free to replace them with your own forms if you want. Here are the files that will not contain forms:

+ techreq/index.php
+ childcare/index.php
+ elearning/index.php

You may also wish to change the links in the footer. That code is located in reporting/includes/footer.php.

Contact Info
--------------------

While we at Brentwood Baptist do not provide technical support for this project, we would love to know if you're using it and if you have any questions. We may not be able to answer them, but if we can we will. If you decide to add any cool features yourself, please let us know because we may want to copy you. Email us at web@brentwoodbaptist.com.