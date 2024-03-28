Cài đặt Web Root:

-> Điều chỉnh Path to /public folder

<VirtualHost *:80>	

  DocumentRoot "C:/xampp/htdocs" 
  
  ServerName localhost
  
</VirtualHost>

<VirtualHost *:80>	
DocumentRoot "Path to /public folder" 
ServerName school.localhost    

#Set access permission 

<Directory "Path to /public folder"> 

Options Indexes FollowSymLinks Includes ExecCGI

AllowOverride All

Require all granted

</Directory>

</VirtualHost>
