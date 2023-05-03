# Simple MVC Framework

This is a very small framework based on Brad Traversy's [Udemy course](https://www.udemy.com/share/101Zcs3@glIxZUWZNdpB_oEkbhKFBV0iCzgCXKdE92_ch-T4V9gNxQtNKDGq1_mkAXpoAvq2/)

and creating it mainly for studiyng purpose. It is based on MVC pattern and it is suitable for fast creating small projects, trying different ideas, conceptions etc..

I had updated slightly this version from the original of the Brad from his course. I added also some stuffs like

composer, different helper functions in helpers folder, user's settings page where you can change your profile photo, reset the password, change the password or to delete permamently your account. Refactored a little the registration and login logic. Updated the main views adding more icons.

![](assets/20211026_112436_Screenshot_281.png)

![](assets/20211026_112846_Screenshot_281.png)

![](assets/20211026_112931_image.png)

# What are you need to run this locally?

1.Working server + PHP + MySQL /you can install packages separately or use xampp, wampp etc.../ and composer installed to install dependencies in composer.json

2.Database and tables. The settings are in config file. You must create database about this project /using phpmyadmin, heidiSQL etc.../

and then you must have a users table. I uploaded this table in sql executable file in db tables folder.

/There are some example or testing data and you can delete it when you want. All testing users are with passwords 1234567/

3.You must your own settings here or to use the default one. For sending emails, Facebook login and
Google login there are no default settings available. If you want to use these features you must create an app in facebook or google firstly. You can follow their documentation.
Other example settings are given below on the image. 

Important change - I moved the db settings from the image to .env file

![](assets/20211026_114951_image.png)

4. You must change .htaccess in public directory adding your project folder name.

![](assets/20211026_203027_image.png)

5. Install all dependencies with composer - you can turn on terminal in the current project directory ant to run
   composer install in the command line. Then the installation must begin.

Before installing the dependencies you can check them in composer.json file

Important change - I removed facebook sdk package because of the incompability with the php 8

![](assets/20211026_223333_image.png)

and if you don't need you just can delete some of them. If you like to use any of them you must firstly execute
composer install in the console/command prompt in the current directory. Where the composer.json file is located.

6.Next final step is to trying to run project. Try to open http://localhost/mvcframework in your browser. If everything is ok you must see like the following image

![](assets/20211027_133409_image.png)

From the db tables folder , if you executed the .sql file, you must have default table users with some testing users.

If you want to explore more you can use one of the three testing accounts:

* owner_test@gmail.com
* admin_test@gmail.com
* user_test@gmail.com

All users default password is 1234567.

**assets** folder is not a part from the project. It just contains images from the readme file used by the markdown editor to show them. You can delete it after download the project together with the Readme.md

# About Project Structure

1. Everything start from the index.php in the main directory. There the main Core class is called from the bootstrap.php

   ![](assets/20211027_140140_image.png)

   In bootstrap.php the main core classes, configuration file and the helper functions are loaded

   ![](assets/20211027_140332_image.png)

   The helpers are just a small functions to help you with the logic. They can be used in models, controllers and in the views. In the following image I am showing example of the content_helpers.php file

   ![](assets/20211027_142350_image.png)

   You can see that there are two functions to help me with the content - the first one is for adding bootstrap cards
   And the second one to help me with the pagination.

   Of course you can freely remove or adding yours. If you don't want to use some of them you just can delete firstly their reference in bootstrap.php and then delete the helper file you don't need. And contrary... if you want to use your own firstly you must add the file in helpers folder and then to add reference in bootstrap.php like the image on bootstrap.php file.

2.The Core class handle actions about loading the necessairy controller, calling the necessairy method from the selected controller and to pass it data from the url. Firstly, the url is splitting by '/' and the first data in array is the name of the controller and the second one the name of the method of this controller. So, if the method is not found from the controller then the default one index will be loaded. If the default index method not exists then the error will be thrown.

3.About Controllers - The Base controller class is located in folder library. Every newly created controller extend it to use the main important PROTECTED(only classes which extend Core class have access to the method) methods about loading the necessairy model and view. I created two additional interfaces about controller - one is called MainFunctionalities and it contains the main default method for all controllers - index. The second one is called Crud which contains main crud functions - created, read, update, delete. So if you want to use them you just have to implements them like on the following image

![](assets/20211027_153348_image.png)

You can see and follow like example the current controllers

4.Models - They are located in models folder and then there are nothing special to do. When create your own you can make an instance of db class in the constructor just like the User model currently is. Just follow the User model like example.

5.Views - views are located in views folder. They are files served to show some information /extracted from db for example/. They can contains html, css etc.. In this folder there is one another called inc. This is folder containing templated for header, footer and navbar templates. The idea here is simple, because every single page need from header, footer and also often from navigation these three parts are created separately and ready to be used, inclucing them in other view files. See some of the other views files located out of inc folder for example. In footer and header parts are included bootstrap and jquery. Feel free to change them whatever you want.


# Suggested workflow

This is just an example. Probably you have a better way but anyway.. So, for example if you have some simple project about listing some posts or something like that may be the following steps would be useful about the workflow

1. Design and creating DB and DB tables for the current project - what information to keep, what fields, relationship between them etc..

   ![](assets/20211027_172904_image.png)


2.After the step of the planning of the DB stuffs and downloaded this small mvc project and after clean unnecessary stuffs like composer packages, assets folder about documentation, deleting some helper files(don't forget to remove their reference in bootstrap.php) etc.. you can create the appropriete controller, for this example Posts controller and extends the Base controller class and implements the MainFunctionality interface and if you want to list, delete, edit, create post in this controller then you must implement and Crud interface too.


![](assets/20211027_173008_image.png)

3.After the controller is created, you need some way to put and to get information about posts , so it is time to create a model Post, where to put methods about extracting information from the db, saving post there etc..
Here, don't forget to make an instance of the Database clase in the constructor. Also, don't forget to get newly created model in your controller like in the previous image. It will use it for querying the db. You can see User model for example.


![](assets/20211027_174758_image.png)


4.After creating or may be getting information about post in controller via the model, now it is time to render this information in view. In views folder you can make folder posts and then to make a new view list.php for example where you will put your html and will render the information extracted from db via model and then passed by the view from the controller. You can see examples in views views folder.


![](assets/20211027_174916_image.png)
