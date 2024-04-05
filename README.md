# **High School Management System**

## **Hướng dẫn cài đặt**

### **Cấu hình XAMPP**

- **Cài đặt Web Root:** Điều chỉnh Path to /public folder
- **Cài đặt Virtual Host**: 
    - Mở file `httpd-vhosts.conf` trong `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
    - Thêm đoạn code sau vào cuối file:
```
<VirtualHost *:80>  
    DocumentRoot "C:/xampp/htdocs" 
    ServerName localhost
</VirtualHost>

<VirtualHost *:80>  
    DocumentRoot "<Link to your project directory>\high-school-management\public" 
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
- **Cài đặt Composer**: 
    - Tải Composer tại [đây](https://getcomposer.org/download/)
    - Cài đặt Composer
- **Bật các extension trong php.ini**:
    - Bật extension `pdo_mysql` trong `php.ini` bằng cách xóa dấu `;` ở đầu dòng `;extension=pdo_mysql`
    - Bật extension `gd` trong `php.ini` bằng cách xóa dấu `;` ở đầu dòng `;extension=gd`
    - Bật extension `zip` trong `php.ini` bằng cách xóa dấu `;` ở đầu dòng `;extension=zip`
- **Chạy lệnh `composer install` trong thư mục gốc của project**

### **Cài đặt cơ sở dữ liệu**
- **Cài đặt Database**: Thực thi file 'sql_scripts/all.sql' bằng một phần mềm quản lý database như `MySQL Workbench`

## **Sử dụng**
- **Truy cập trang web:** Mở trình duyệt và truy cập vào `http://highschool.localhost`