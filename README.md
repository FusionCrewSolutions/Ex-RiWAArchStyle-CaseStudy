# Ex-RiWAArchStyle-CaseStudy
Case study implementation of Ex-RiWAArch style

![Alt text](Ex-RiWAArch%20style%20demo%20prototype.png?raw=true "Technologies for the DC connecotrs in Ex-RiWAArch style demo prototype")
<br>
<br>
<br>
<br>
## Install ZMQ library – To install the ZMQ library, following steps can be used
<pre>
  •	Download the ZMQ library, which match the PHP version and architecture (PHP version 5.6.38 in 64bit architecture for the developed prototype).  
  •	Copy file php_zmq.dll to <XAMPP installation folder>\php\ext  
  •	Copy file libzmq.dll to <XAMPP installation folder>\php  
  •	Add the following line to php.ini in <XAMPP installation folder>\php  
          extension=php_zmq.dll  
  •	Add the following line to httpd.conf in <XAMPP installation folder>\apache\conf  
          LoadFile "<XAMPP installation folder>\php\libzmq.dll"  
  •	Make sure to restart theApacheserver after these configurations.
</pre>



## Directory Structure of the project
<pre>
[Root Directory]
  [js] – autobahn.js, bootstrap.js, jquery-3.2.1.js, chatroom.js, articles.js, notifications.js
  [vendor]
      [ExRiWAArch] – Articles.php, Commons.php, User.php
      [……..]
      [……..]
  [View] – bootstrap.min.js.
  .htaccess
  composer.json
  DCPullBus.php
  DCPushSender.php
  DCPushServer.php
  Index.php
  viewArticles.php
  viewChatRoom.php
  viewTemplateHeader.php
  chatUsers.txt
</pre>




## PHP dependancies 
<pre>
 {    
    "require": {
        "slim/slim": "^3.1",
        "slim/php-view": "^2.0",
        "monolog/monolog": "^1.17",
        "guzzlehttp/psr7": "^1.0",
        "ratchet/rfc6455": "^0.3",
        "react/socket": "^1.0 || ^0.8 || ^0.7 || ^0.6 || ^0.5",
        "symfony/http-foundation": "^2.6|^3.0|^4.0|^5.0",
        "symfony/routing": "^2.6|^3.0|^4.0|^5.0",
        "cboden/ratchet": "0.4.*",
        "react/zmq": "0.2.*|0.3.*"
    },
    "autoload": {
        "classmap": ["vendor/"]
    }
}
</pre>
