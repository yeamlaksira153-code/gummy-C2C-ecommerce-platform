LOCALHOST SETUP (Laragon / XAMPP / WAMP)
System Requirements
• PHP  
• MySQL Database  
• Apache Server  
• Laragon or XAMPP or WAMP  
• Notepad++ or VS Code  
Setup Instructions 
1. Import the database file into phpMyAdmin.  
2. Place the project folder inside the web server root directory.  
   Laragon: C:\laragon\www\  
   XAMPP: C:\xampp\htdocs\  
   WAMP: C:\wamp64\www\  
3. Configure database credentials and admin login credentials  in the env file and db.php using local values.  
   DB_HOST = localhost  
   DB_USER = root  
   DB_PASS =  
   DB_NAME = your_database name
4. Start Apache and MySQL services.  
5. Open the website using the localhost 	URL
LIVE HOSTING SETUP (InfinityFree or any hosting provider)
System Requirements
• Hosting account (InfinityFree or similar)  
• PHP support  
• MySQL database  
• File Manager or FTP access  
Setup Instructions 
1. Log in to your hosting control panel and open phpMyAdmin.  
2. Create a new database and import the .sql file.  
3. Upload the project folder to the hosting root directory.  
   InfinityFree: htdocs  
4. Configure database and admin login credentials in the env file and db.php using hosting values.  
   DB_HOST = your hosting database host  
   DB_USER = your hosting database username  
   DB_PASS = your hosting database password  
   DB_NAME = your hosting database name  
The admin and manager login details are hardcoded inside the adminlogin.php file 
5. Open the website using your live domain URL.
Hosting
The project is hosted using a free hosting provider, infinity free which is compatible with PHP and MySQL.
website url :
Main Website: https://gummy.gt.tc
Admin website: https://gummy.gt.tc/auth/adminlogin.php 

