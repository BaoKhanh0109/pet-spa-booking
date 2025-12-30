# Hướng Dẫn Cài Đặt Hệ Thống Đặt Lịch Theo Danh Mục

## Tổng Quan

Hệ thống đặt lịch đã được nâng cấp với 3 loại dịch vụ:

### 1. 💅 Dịch Vụ Làm Đẹp (Beauty)
- ✅ Chọn **nhiều dịch vụ** cùng lúc
- ✅ Chọn ngày giờ hẹn
- ✅ Hiển thị danh sách nhân viên rảnh
- ✅ Cho phép chọn nhân viên hoặc tự động

### 2. ⚕️ Dịch Vụ Y Tế (Medical)
- ✅ Chỉ chọn **1 dịch vụ**
- ✅ Hai phương thức đặt lịch:
  - **Đặt theo ngày**: Chọn ngày → Hệ thống tự chọn bác sĩ rảnh
  - **Đặt theo bác sĩ**: Chọn bác sĩ → Xem lịch rảnh → Chọn ngày phù hợp

### 3. 🏠 Dịch Vụ Trông Giữ (Pet Care)
- ✅ Chọn ngày gửi
- ✅ Chọn ngày đón
- ✅ Tính tổng số ngày và tổng tiền tự động

---

## Các Bước Cài Đặt

### Bước 1: Cập nhật Database

#### Nếu bạn **CÀI MỚI** (chưa có database):
```bash
# Chạy file pet.sql đã được cập nhật
mysql -u root -p < pet.sql
```

#### Nếu bạn **ĐÃ CÓ DATABASE** sẵn:
```bash
# Chạy file migration để cập nhật
mysql -u root -p pet_care_db < database/migrations/update_booking_categories.sql
```

### Bước 2: Clear Cache Laravel
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Bước 3: Kiểm tra Routes
```bash
php artisan route:list --name=booking
```

Bạn sẽ thấy các route mới:
- `booking.select-category` - Trang chọn loại dịch vụ
- `booking.beauty` - Trang đặt lịch làm đẹp
- `booking.medical` - Trang đặt lịch y tế
- `booking.pet-care` - Trang đặt lịch trông giữ
- `booking.available-staff` - API lấy nhân viên rảnh
- `booking.doctor-schedule` - API lấy lịch bác sĩ

---

## Cấu Trúc Files Mới

### Controllers
- `app/Http/Controllers/BookingController.php` - ✅ Đã cập nhật đầy đủ

### Models
- `app/Models/Service.php` - ✅ Thêm trường `category`
- `app/Models/Appointment.php` - ✅ Thêm trường `booking_type`, `endDate`, `prefer_doctor`
- `app/Models/Employee.php` - ✅ Đã có relationships

### Views (Blade Templates)
```
resources/views/bookings/
├── select-category.blade.php   [MỚI] - Chọn loại dịch vụ
├── beauty.blade.php            [MỚI] - Đặt lịch làm đẹp
├── medical.blade.php           [MỚI] - Đặt lịch y tế
├── pet-care.blade.php          [MỚI] - Đặt lịch trông giữ
└── history.blade.php           [ĐÃ CẬP NHẬT] - Xem lịch sử
```

### Routes
- `routes/web.php` - ✅ Đã thêm đầy đủ routes

---

## Hướng Dẫn Sử Dụng

### Cho Người Dùng:

1. **Đăng nhập** vào hệ thống
2. Vào trang **"Đặt Lịch Hẹn"** (route: `/dat-lich`)
3. **Chọn thú cưng** của bạn
4. **Chọn loại dịch vụ**:
   - 💅 **Làm Đẹp**: Chọn nhiều dịch vụ → Chọn ngày/giờ → Chọn nhân viên
   - ⚕️ **Y Tế**: Chọn 1 dịch vụ → Chọn đặt theo ngày hoặc bác sĩ
   - 🏠 **Trông Giữ**: Chọn ngày gửi và ngày đón

5. Xác nhận đặt lịch

### URLs Quan Trọng:
- Chọn danh mục: `/dat-lich/chon-danh-muc`
- Làm đẹp: `/dat-lich/lam-dep?petID=1`
- Y tế: `/dat-lich/y-te?petID=1`
- Trông giữ: `/dat-lich/trong-giu?petID=1`
- Lịch sử: `/lich-su-dat`

---

## Cấu Trúc Database

### Bảng `services`
```sql
- serviceID (PK)
- serviceName
- description
- price
- category (ENUM: 'beauty', 'medical', 'pet_care') [MỚI]
```

### Bảng `appointments`
```sql
- appointmentID (PK)
- userID (FK)
- petID (FK)
- employeeID (FK) [nullable]
- serviceID (FK) [nullable - cho beauty services]
- appointmentDate
- endDate [MỚI] - Cho trông giữ
- note
- status
- booking_type (ENUM: 'beauty', 'medical', 'pet_care') [MỚI]
- prefer_doctor (TINYINT) [MỚI] - 0 hoặc 1
```

### Bảng `appointment_services` (Đã có)
```sql
- appointment_servicesId (PK)
- appointmentID (FK)
- serviceID (FK)
```
*Bảng này dùng cho dịch vụ làm đẹp khi chọn nhiều dịch vụ*

---

## API Endpoints

### 1. Lấy nhân viên rảnh
```
GET /api/available-staff?service_ids[]=1&service_ids[]=2&appointment_date=2025-12-30T14:00
```

**Response:**
```json
[
  {
    "employeeID": 3,
    "employeeName": "Trần Văn Hùng",
    "role": "Chuyên viên Grooming"
  }
]
```

### 2. Lấy lịch bác sĩ
```
GET /api/doctor-schedule?employee_id=1&month=2025-12
```

**Response:**
```json
{
  "schedules": [
    {
      "dayOfWeek": "Monday",
      "startTime": "08:00:00",
      "endTime": "17:00:00"
    }
  ],
  "appointments": [
    {
      "appointmentDate": "2025-12-30 09:00:00"
    }
  ]
}
```

---

## Kiểm Tra & Testing

### 1. Kiểm tra database đã cập nhật:
```sql
-- Kiểm tra services có category
SELECT * FROM services;

-- Kiểm tra appointments có booking_type
SELECT * FROM appointments;
```

### 2. Test từng luồng:

#### Test Làm Đẹp:
1. Vào `/dat-lich/chon-danh-muc`
2. Chọn thú cưng
3. Click "Làm Đẹp"
4. Chọn 2-3 dịch vụ
5. Chọn ngày giờ
6. Xem danh sách nhân viên
7. Đặt lịch

#### Test Y Tế:
1. Chọn "Y Tế"
2. Chọn 1 dịch vụ
3. Chọn "Đặt theo ngày" → Chọn ngày → Submit
4. Hoặc chọn "Đặt theo bác sĩ" → Chọn bác sĩ → Xem lịch → Chọn ngày → Submit

#### Test Trông Giữ:
1. Chọn "Trông Giữ"
2. Chọn ngày gửi
3. Chọn ngày đón
4. Xem tổng tiền tự động tính
5. Submit

---

## Troubleshooting

### Lỗi: "Column 'category' not found"
```bash
# Chạy lại migration
mysql -u root -p pet_care_db < database/migrations/update_booking_categories.sql
```

### Lỗi: Routes không hoạt động
```bash
php artisan route:clear
php artisan config:clear
```

### Lỗi: View không tìm thấy
```bash
php artisan view:clear
# Kiểm tra file có tồn tại trong resources/views/bookings/
```

### Lỗi: "Call to undefined relationship 'services'"
```bash
# Kiểm tra Appointment model đã có relationship services()
# Kiểm tra bảng appointment_services đã tồn tại
```

---

## Tính Năng Nổi Bật

✨ **Smart Staff Assignment**: Tự động chọn nhân viên rảnh dựa trên:
  - Dịch vụ nhân viên có thể làm
  - Lịch làm việc của nhân viên
  - Các lịch hẹn đã đặt

✨ **Doctor Schedule**: Hiển thị lịch làm việc và lịch đã đặt của bác sĩ

✨ **Multi-Service Booking**: Đặt nhiều dịch vụ cùng lúc cho dịch vụ làm đẹp

✨ **Date Range for Pet Care**: Chọn khoảng thời gian trông giữ với tính toán tự động

✨ **Beautiful UI**: Giao diện hiện đại với Tailwind CSS

---

## Liên Hệ & Hỗ Trợ

Nếu có vấn đề gì, vui lòng kiểm tra:
1. Database đã được cập nhật chưa
2. Cache Laravel đã được clear chưa
3. Routes đã được đăng ký chưa
4. Models đã có relationships chưa

**Happy Coding! 🚀**
