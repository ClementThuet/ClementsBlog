# ClementsBlog
A PHP blog made from scratch during my formation with OpenClassrooms in august - september 2019 .<br>
The goals are to introduce myself, enable anyone to register, manage blog posts and comments.  <br>
You can also contact me using the contact form. 

## Getting Started

### Requirements
To install the project you will need :
* An Apache server >=2.4
* PHP >= 7.1
* MySQL or another database of your choice<br> 

I recommend to use WampServer as I did.

### Installing
You can get the project by using git clone (If you don't know how to do it, more info here : https://git-scm.com/book/it/v2/Git-Basics-Getting-a-Git-Repository)
```
$ git clone https://github.com/ClementThuet/ClementsBlog.git
```
Then you need to execute `composer install` into the project folder to install the dependencies.<br>
If you don't have composer you can get it here https://getcomposer.org/doc/00-intro.md

Make sure your application point to /clementsblog/public/index.php as defined in /clementsblog/public/htaccesss.
```
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```
### Database configuration
Configure your database according to your personal configuration in clementsblog/app/database.php

```
//Paramètrage de la connextion à la BDD
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'charset'  => 'utf8',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'clementsblog',
);
```
### Sending email with contact form
I use fake sendmail to send email in local, you need to install it (explanation here : https://www.glob.com.au/sendmail/) and configure the sendmail.ini, to do so :
Define your SMTP server, I used Gmail
``` smtp_server=smtp.gmail.com ```
It needs access to your mailbox so define your credentials<br>
``` 
auth_username=clementxxxgmail.com
auth_password=xxxxxx
```

That's all, you can now access to ClementsBlog and sign in to create your first blog post.

## Author
**Clément Thuet**
* https://www.linkedin.com/in/cl%C3%A9ment-thuet/
* https://github.com/ClementThuet/

## Acknowledgments
Thanks to my mentor **Soma Bini** (https://www.linkedin.com/in/soma-bini-08173680) for his advices, support and his ability to wake up early 
