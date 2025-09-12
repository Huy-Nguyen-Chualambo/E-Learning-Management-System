# Kế Hoạch Thực Hiện Dự Án Laravel 9 - E-Learning Management System

## Mục Lục
- [Tổng Quan](#tổng-quan)
- [Mục Tiêu và Phạm Vi](#mục-tiêu-và-phạm-vi)
- [Thiết Lập và Công Cụ Bắt Buộc](#thiết-lập-và-công-cụ-bắt-buộc)
- [Yêu Cầu Kỹ Thuật Cốt Lõi](#yêu-cầu-kỹ-thuật-cốt-lõi)
- [Yêu Cầu Chức Năng Bắt Buộc](#yêu-cầu-chức-năng-bắt-buộc)
- [Lưu Ý Đặc Biệt](#lưu-ý-đặc-biệt)
- [Kế Hoạch Thực Hiện 5 Ngày](#kế-hoạch-thực-hiện-5-ngày)
- [Tài Liệu Tham Khảo](#tài-liệu-tham-khảo)

## Tổng Quan

Dự án này yêu cầu xây dựng một ứng dụng CRUD hoàn chỉnh bằng Laravel 9, áp dụng đầy đủ workflow, kiểm thử, phân quyền tự viết, Ajax cho CRUD, và các best practices trong PHP/Laravel/Javascript. Ứng dụng E-Learning Management System sẽ bao gồm các module quản trị người dùng, vai trò, danh mục, sản phẩm (khóa học), tìm kiếm nâng cao, xử lý ảnh, và hệ thống phân quyền.

## Mục Tiêu và Phạm Vi

- **Mục tiêu:** 
  - Tạo ứng dụng CRUD đầy đủ chức năng
  - Nắm vững cấu trúc và luồng hoạt động Laravel
  - Quản lý công việc hiệu quả theo quy trình

- **Phạm vi:** 
  - Xây dựng mới một project Laravel trong môi trường Laradock
  - Triển khai đầy đủ các chức năng CRUD, search, authorize, validation
  - Xử lý middleware, accessor và ảnh

## Thiết Lập và Công Cụ Bắt Buộc

- **Môi trường:** Laravel 9 chạy trên Laradock
- **Quản lý công việc:** Trello (hoặc phương pháp quản lý công việc thay thế)
- **Quy trình Git:** Gitflow/Workflow với feature branches và pull requests
- **Kiểm thử:** 100% feature test cho các chức năng đã làm

## Yêu Cầu Kỹ Thuật Cốt Lõi

- **Kiến trúc:** MVC kết hợp Repository + Service (5 lớp)
- **Quan hệ dữ liệu:** 
  - Bắt buộc có quan hệ n-n cho Users–Roles, Roles–Permissions, Products–Categories
  - Category có quan hệ tự tham chiếu 1-n (menu tối đa 2 cấp)
- **Validation:** Dùng Form Request; tạo ít nhất 1 Custom Validation Rule
- **Middleware:** Viết middleware mới (khác auth) và áp dụng ở route phù hợp
- **Search:** Viết local scope để xây dựng query tìm kiếm
- **Chuẩn mã nguồn:** 
  - Tuân thủ PSR-2
  - Sử dụng PHP CodeSniffer để kiểm tra
  - Áp dụng Best Practices cho Ajax, jQuery, và Laravel
  - Áp dụng Event Handling
  - Hiểu thuộc tính trong Factory và Test

## Yêu Cầu Chức Năng Bắt Buộc

### 1. Giao Diện
- **UI:** Có thể sử dụng template admin miễn phí

### 2. Danh Mục Chức Năng

| STT | Chức năng | Mô tả chi tiết |
|-----|-----------|----------------|
| 1 | **Quản lý Role & Permission** | - CRUD Role<br>- Seed dữ liệu Permission<br>- Tìm kiếm role<br>- Quan hệ n-n roles–permissions<br>- Không dùng Spatie |
| 2 | **Authenticate** | - Đăng nhập, đăng ký, đăng xuất |
| 3 | **Quản lý User** | - CRUD User<br>- Quan hệ n-n với role |
| 4 | **Quản lý Category** | - CRUD Category<br>- Quan hệ tự tham chiếu 1-n (tối đa 2 cấp) |
| 5 | **Quản lý Product** | - CRUD bằng Ajax + Bootstrap Modal<br>- Xử lý ảnh bằng Laravel Image Intervention<br>- Quan hệ n-n với Category<br>- Trait HandleImage<br>- Module JS |
| 6 | **Phân quyền** | - Blade @if cho element<br>- Middleware cho route<br>- Gate/policy cho role super-admin<br>- Không dùng Spatie |
| 7 | **Tìm kiếm User** | - Theo tên, email, role<br>- Dùng local scope |
| 8 | **Custom Rule** | - Rule: chỉ cho phép email @deha-soft.com khi tạo user |
| 9 | **Tìm kiếm Product** | - Theo tên category, tên/giá product<br>- Ajax + debounce<br>- whereHas + scope |
| 10 | **Accessor ảnh** | - Accessor sinh đường dẫn ảnh từ tên file và thư mục |

## Lưu Ý Đặc Biệt

- **Super-admin:** Phải có 1 tài khoản super-admin có full quyền mà không cần assign permission
- **Quan hệ dữ liệu:** Products–Categories (n-n), Roles–Users (n-n), Roles–Permissions (n-n), Category–Category (1-n, tối đa 2 cấp)
- **Xử lý ảnh:** 
  - DB chỉ lưu tên file (ví dụ )
  - File lưu trong thư mục (ví dụ )
  - Accessor để tạo đường dẫn đầy đủ ()

## Kế Hoạch Thực Hiện 5 Ngày

### Ngày 1: Thiết lập và Cơ sở dữ liệu
- **Sáng:**
  - Cài đặt Laradock và thiết lập môi trường
  - Khởi tạo dự án Laravel 9 mới
  - Thiết lập cấu trúc Repository + Service
  - Cài đặt PHP CodeSniffer và các package cần thiết

- **Chiều:**
  - Thiết kế và tạo cơ sở dữ liệu (migration)
  - Tạo model và định nghĩa các quan hệ giữa các model
  - Tạo các seeder và factory cơ bản
  - Thiết lập hệ thống xác thực (authentication)

### Ngày 2: Phân quyền và Quản lý người dùng
- **Sáng:**
  - Xây dựng hệ thống Role & Permission
  - Tạo các seed dữ liệu cho Permission
  - Viết các Repository và Service cho User, Role, Permission
  - Tạo Custom Validation Rule cho email

- **Chiều:**
  - Xây dựng controller và view cho quản lý User
  - Xây dựng controller và view cho quản lý Role
  - Viết unit test và feature test cho các chức năng đã làm
  - Thiết lập middleware phân quyền

### Ngày 3: Quản lý Category và thiết lập giao diện
- **Sáng:**
  - Tích hợp template admin và thiết lập layout
  - Xây dựng hệ thống menu đa cấp cho Category
  - Viết Repository và Service cho Category
  - Hoàn thiện CRUD Category

- **Chiều:**
  - Viết test cho Category
  - Tạo middleware tùy chỉnh
  - Xây dựng tính năng tìm kiếm User với local scope
  - Đảm bảo gate/policy cho super-admin hoạt động đúng

### Ngày 4: Quản lý Product và xử lý ảnh
- **Sáng:**
  - Xây dựng Repository và Service cho Product
  - Tạo Trait HandleImage để xử lý ảnh
  - Tích hợp Laravel Image Intervention
  - Viết accessor cho ảnh sản phẩm

- **Chiều:**
  - Xây dựng CRUD Product bằng Ajax và Bootstrap Modal
  - Tổ chức JavaScript theo module
  - Viết test cho Product
  - Hoàn thiện quan hệ n-n giữa Product và Category

### Ngày 5: Tìm kiếm nâng cao và hoàn thiện
- **Sáng:**
  - Xây dựng tính năng tìm kiếm Product nâng cao
  - Triển khai Ajax với debounce cho tìm kiếm
  - Xây dựng các query tìm kiếm sử dụng whereHas và scope
  - Kiểm tra và cải thiện UX/UI

- **Chiều:**
  - Hoàn thiện tất cả các test còn thiếu
  - Kiểm tra code với PHP CodeSniffer
  - Sửa lỗi và tối ưu code
  - Kiểm tra toàn diện và hoàn thiện dự án

## Tài Liệu Tham Khảo

- **Coding conventions:** 
  - PSR-2 PHP Standard
  - Laravel Best Practices
  - Clean Code PHP/Javascript

- **ORM và Repository:**
  - Mẫu BaseRepository 
  - Quan hệ Eloquent trong Laravel

- **JavaScript Module:**
  - Convention tổ chức module trong Javascript

- **Các Template Admin miễn phí:**
  - AdminLTE
  - Tabler
  - CoreUI
  - SB Admin 2
