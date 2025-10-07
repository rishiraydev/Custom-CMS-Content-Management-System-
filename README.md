# Custom-CMS-Content-Management-System-
A mini web-based CMS where an admin can log in, create, edit, and delete posts or pages using a WYSIWYG editor. It also includes role-based access (Admin &amp; Editor), SEO fields, and a public front-end to display posts.

# PHP + MySQL Custom CMS

A fully-featured Content Management System built with PHP 8+ and MySQL, featuring role-based access control, SEO optimization, and a responsive design.

## Features

### 🔐 Authentication & Security
- Secure login system with session management
- Role-based access (Admin & Editor)
- Password hashing using `password_hash()`
- SQL injection protection with prepared statements

### 📝 Content Management
- Full CRUD operations for posts
- WYSIWYG editor (TinyMCE)
- Featured image upload
- SEO-friendly URLs with auto-generated slugs
- Meta title and description for SEO
- Published/Unpublished toggle

### 👥 User Management
- Admin can manage all users
- Editors can only manage their own posts
- Secure role-based permissions

### 🌐 Public Frontend
- Responsive Bootstrap design
- SEO-optimized post pages
- Search functionality
- Pagination
- Post views counter

## Installation

1. **Requirements**
   - PHP 8.0+
   - MySQL 5.7+
   - Apache/Nginx with mod_rewrite

2. **Database Setup**
   ```sql
   CREATE DATABASE cms;
   USE cms;
   -- Run the SQL schema from database.sql
