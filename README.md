Cài đặt Web Root:

-> Điều chỉnh Path to /public folder
```
<VirtualHost *:80>  
    DocumentRoot "C:/xampp/htdocs" 
    ServerName localhost
</VirtualHost>

<VirtualHost *:80>  
    DocumentRoot "<Link to yourz project directory>\high-school-management\public" 
    ServerName highschool.localhost
    # Set access permission 
    <Directory "<Link to your project directory>\high-school-management\public"> 
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Require all granted

  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . index.php [L]
    </Directory>
</VirtualHost>
```