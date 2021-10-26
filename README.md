# Simple MVC Framework

This is a very small framework based on Brad Traversy's [Udemy course](https://www.udemy.com/share/101Zcs3@glIxZUWZNdpB_oEkbhKFBV0iCzgCXKdE92_ch-T4V9gNxQtNKDGq1_mkAXpoAvq2/)

and creating it mainly for studiyng purpose. It is based on MVC pattern and it is suitable for fast creating small projects, trying different ideas, conceptions etc..

I had updated slightly this version from the original of the Brad from his course. I added also some stuffs like

composer, facebook and google login buttons, different helper functions in helpers folder, user's settings page where you can change your profile photo, reset the password, change the password or to delete permamently your account. Refactored a little the registration and login logic. Updated the main views adding more icons.

![](assets/20211026_112436_Screenshot_281.png)

![](assets/20211026_112846_Screenshot_281.png)

![](assets/20211026_112931_image.png)

# What are you need to run this locally?

1.Working server + PHP + MySQL /you can install packages separately or use xampp, wampp etc.../ and composer installed to install dependencies in composer.json

2.Database and tables. The settings are in config file. You must create database about this project /using phpmyadmin, heidiSQL etc.../

and then you must have a users table. I uploaded this table in sql executable file in db tables folder.

/There are some example or testing data and you can delete it when you want/

3.You must your own settings here or to use the default one. For sending emails, Facebook login and
Google login there are no default settings available. If you want to use these features you must create an app in facebook or google firstly. You can follow their documentation.
Other example settings are given below on the image.

![](assets/20211026_114951_image.png)

4. You must change .htaccess in public directory adding your project folder name.

![](assets/20211026_203027_image.png)

5. Install all dependencies with composer - you can turn on terminal in the current project directory ant to run
   composer install in the command line. Then the installation must begin.

Before installing the dependencies you can check them in composer.json file

![](assets/20211026_223333_image.png)

and if you don't need you just can delete some of them. If you do it, then you must execute composer update.
