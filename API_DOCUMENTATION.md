# Pet Spa Booking - RESTful API Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
API sử dụng Laravel Sanctum cho xác thực. Sau khi đăng nhập, bạn sẽ nhận được `access_token` để sử dụng cho các request tiếp theo.

### Headers
```
Authorization: Bearer {access_token}
Accept: application/json
Content-Type: application/json
```

---

## 🔐 Authentication APIs

### 1. Đăng ký
```
POST /api/v1/auth/register
```

**Body:**
```json
{
    "name": "Nguyễn Văn A",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "0123456789"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Đăng ký thành công!",
    "data": {
        "user": {...},
        "access_token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 2. Đăng nhập
```
POST /api/v1/auth/login
```

**Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

### 3. Đăng xuất
```
POST /api/v1/auth/logout
```
*Yêu cầu: Authentication*

### 4. Lấy thông tin user hiện tại
```
GET /api/v1/auth/user
```
*Yêu cầu: Authentication*

---

## 📋 Services APIs

### 1. Lấy danh sách dịch vụ
```
GET /api/v1/services
```

**Query Parameters:**
- `search` (optional): Tìm kiếm theo tên hoặc mô tả
- `category_id` (optional): Lọc theo danh mục

### 2. Lấy chi tiết dịch vụ
```
GET /api/v1/services/{id}
```

### 3. Lấy danh mục dịch vụ
```
GET /api/v1/service-categories
```

### 4. Tính giá dịch vụ theo thú cưng
```
POST /api/v1/services/calculate-price
```
*Yêu cầu: Authentication*

**Body:**
```json
{
    "service_id": 1,
    "pet_id": 1
}
```

---

## 🐾 Pets APIs

### 1. Lấy danh sách thú cưng
```
GET /api/v1/pets
```
*Yêu cầu: Authentication*

### 2. Thêm thú cưng mới
```
POST /api/v1/pets
```
*Yêu cầu: Authentication*

**Body (form-data):**
```json
{
    "petName": "Lucky",
    "species": "Chó",
    "breed": "Golden Retriever",
    "weight": 15.5,
    "backLength": 50,
    "birthDate": "2022-01-15",
    "gender": "male",
    "petImage": [file]
}
```

### 3. Lấy chi tiết thú cưng
```
GET /api/v1/pets/{id}
```
*Yêu cầu: Authentication*

### 4. Cập nhật thú cưng
```
PUT /api/v1/pets/{id}
```
*Yêu cầu: Authentication*

### 5. Xóa thú cưng
```
DELETE /api/v1/pets/{id}
```
*Yêu cầu: Authentication*

---

## 📅 Bookings APIs

### 1. Lấy danh sách lịch hẹn
```
GET /api/v1/bookings
```
*Yêu cầu: Authentication*

### 2. Tạo lịch hẹn mới
```
POST /api/v1/bookings
```
*Yêu cầu: Authentication*

**Body:**
```json
{
    "pet_id": 1,
    "service_ids": [1, 2],
    "appointment_date": "2026-02-01 10:00:00",
    "employee_id": null,
    "end_date": null,
    "note": "Ghi chú",
    "booking_type": "beauty"
}
```

**booking_type:**
- `beauty`: Làm đẹp
- `medical`: Y tế
- `pet_care`: Trông giữ

### 3. Lấy chi tiết lịch hẹn
```
GET /api/v1/bookings/{id}
```
*Yêu cầu: Authentication*

### 4. Cập nhật lịch hẹn
```
PUT /api/v1/bookings/{id}
```
*Yêu cầu: Authentication*

**Body:**
```json
{
    "appointment_date": "2026-02-01 14:00:00",
    "note": "Cập nhật ghi chú"
}
```

### 5. Hủy lịch hẹn
```
DELETE /api/v1/bookings/{id}
```
*Yêu cầu: Authentication*

### 6. Lấy nhân viên có sẵn
```
GET /api/v1/bookings-available-staff
```
*Yêu cầu: Authentication*

**Query Parameters:**
- `service_ids[]`: Mảng ID dịch vụ
- `appointment_date`: Ngày giờ hẹn

### 7. Lấy lịch làm việc bác sĩ
```
GET /api/v1/bookings-doctor-schedule
```
*Yêu cầu: Authentication*

**Query Parameters:**
- `employee_id`: ID nhân viên
- `month` (optional): Tháng (format: Y-m)

---

## 👨‍💼 Admin APIs

*Yêu cầu: Authentication + Admin Role*

### Services Management

```
GET    /api/v1/admin/services           # Danh sách dịch vụ
POST   /api/v1/admin/services           # Thêm dịch vụ
GET    /api/v1/admin/services/{id}      # Chi tiết dịch vụ
PUT    /api/v1/admin/services/{id}      # Cập nhật dịch vụ
DELETE /api/v1/admin/services/{id}      # Xóa dịch vụ
```

### Employees Management

```
GET    /api/v1/admin/employees                              # Danh sách nhân viên
POST   /api/v1/admin/employees                              # Thêm nhân viên
GET    /api/v1/admin/employees/{id}                         # Chi tiết nhân viên
PUT    /api/v1/admin/employees/{id}                         # Cập nhật nhân viên
DELETE /api/v1/admin/employees/{id}                         # Xóa nhân viên
POST   /api/v1/admin/employees/{id}/schedules               # Thêm lịch làm việc
PUT    /api/v1/admin/employees/{id}/schedules/{scheduleId}  # Cập nhật lịch
DELETE /api/v1/admin/employees/{id}/schedules/{scheduleId}  # Xóa lịch
```

### Appointments Management

```
GET   /api/v1/admin/appointments              # Danh sách tất cả lịch hẹn
GET   /api/v1/admin/appointments/{id}         # Chi tiết lịch hẹn
PATCH /api/v1/admin/appointments/{id}/status  # Cập nhật trạng thái
DELETE /api/v1/admin/appointments/{id}        # Xóa lịch hẹn
```

**Body cập nhật trạng thái:**
```json
{
    "status": "Confirmed"
}
```

**Status:**
- `Pending`: Chờ xác nhận
- `Confirmed`: Đã xác nhận
- `Completed`: Hoàn thành
- `Cancelled`: Đã hủy

### Users Management

```
GET    /api/v1/admin/users       # Danh sách users
GET    /api/v1/admin/users/{id}  # Chi tiết user
DELETE /api/v1/admin/users/{id}  # Xóa user
```

### Employee Roles Management

```
GET    /api/v1/admin/roles           # Danh sách chức vụ
POST   /api/v1/admin/roles           # Thêm chức vụ
GET    /api/v1/admin/roles/{id}      # Chi tiết chức vụ
PUT    /api/v1/admin/roles/{id}      # Cập nhật chức vụ
DELETE /api/v1/admin/roles/{id}      # Xóa chức vụ
```

**Body thêm/cập nhật chức vụ:**
```json
{
    "roleName": "Bác sĩ thú y",
    "description": "Nhân viên khám chữa bệnh cho thú cưng"
}
```

### Dashboard

```
GET /api/v1/admin/dashboard          # Thống kê tổng quan
GET /api/v1/admin/dashboard/revenue  # Thống kê doanh thu
```

**Query Parameters cho revenue:**
- `start_date` (optional): Ngày bắt đầu
- `end_date` (optional): Ngày kết thúc

---

## 👤 Profile APIs

*Yêu cầu: Authentication*

### 1. Lấy thông tin profile
```
GET /api/v1/profile
```

### 2. Cập nhật profile
```
PUT /api/v1/profile
```

**Body:**
```json
{
    "name": "Nguyễn Văn A",
    "email": "user@example.com",
    "phone": "0123456789",
    "address": "123 Đường ABC"
}
```

### 3. Đổi mật khẩu
```
PUT /api/v1/profile/password
```

**Body:**
```json
{
    "current_password": "old_password",
    "password": "new_password",
    "password_confirmation": "new_password"
}
```

### 4. Xóa tài khoản
```
DELETE /api/v1/profile
```

**Body:**
```json
{
    "password": "current_password"
}
```

---

## 🔐 Google Authentication APIs

### 1. Lấy URL đăng nhập Google
```
GET /api/v1/auth/google/url
```

**Response:**
```json
{
    "success": true,
    "data": {
        "url": "https://accounts.google.com/o/oauth2/..."
    }
}
```

### 2. Xử lý callback từ Google
```
POST /api/v1/auth/google/callback
```

**Body:**
```json
{
    "code": "authorization_code_from_google"
}
```

### 3. Xác thực ID Token (cho mobile app)
```
POST /api/v1/auth/google/verify-token
```

**Body:**
```json
{
    "id_token": "google_id_token"
}
```

---

## 🏠 Home / Public APIs

### 1. Lấy dữ liệu trang chủ
```
GET /api/v1/home
```

**Response:**
```json
{
    "success": true,
    "data": {
        "services": [...],
        "categories": [...],
        "featured_services": [...]
    }
}
```

### 2. Lấy dịch vụ theo danh mục
```
GET /api/v1/home/categories/{categoryId}/services
```

### 3. Tìm kiếm dịch vụ
```
GET /api/v1/home/search?q=keyword
```

---

## 📝 Response Format

### Success Response
```json
{
    "success": true,
    "message": "Thành công!",
    "data": {...}
}
```

### Error Response
```json
{
    "success": false,
    "message": "Lỗi xảy ra!"
}
```

### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

---

## 🔑 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200  | OK - Thành công |
| 201  | Created - Tạo thành công |
| 400  | Bad Request - Yêu cầu không hợp lệ |
| 401  | Unauthorized - Chưa đăng nhập |
| 403  | Forbidden - Không có quyền |
| 404  | Not Found - Không tìm thấy |
| 422  | Unprocessable Entity - Lỗi validation |
| 500  | Internal Server Error - Lỗi server |
