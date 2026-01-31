<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Pet Spa Booking API Documentation",
 *     description="RESTful API cho hệ thống đặt lịch Pet Spa. Base URL: /api",
 *     @OA\Contact(
 *         email="admin@petspa.com",
 *         name="Pet Spa Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="Pet Spa Booking API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer {token}"
 * )
 * 
 * @OA\Tag(name="Authentication", description="API đăng nhập, đăng ký, đăng xuất")
 * @OA\Tag(name="Google Auth", description="API xác thực với Google")
 * @OA\Tag(name="Home", description="API trang chủ và tìm kiếm công khai")
 * @OA\Tag(name="Services", description="API quản lý dịch vụ (public)")
 * @OA\Tag(name="Profile", description="API quản lý thông tin cá nhân")
 * @OA\Tag(name="Pets", description="API quản lý thú cưng")
 * @OA\Tag(name="Bookings", description="API quản lý lịch hẹn")
 * @OA\Tag(name="Admin Dashboard", description="API thống kê dashboard (Admin)")
 * @OA\Tag(name="Admin Services", description="API quản lý dịch vụ (Admin)")
 * @OA\Tag(name="Admin Employees", description="API quản lý nhân viên (Admin)")
 * @OA\Tag(name="Admin Appointments", description="API quản lý lịch hẹn (Admin)")
 * @OA\Tag(name="Admin Users", description="API quản lý người dùng (Admin)")
 * @OA\Tag(name="Admin Roles", description="API quản lý chức vụ nhân viên (Admin)")
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="userID", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Nguyễn Văn A"),
 *     @OA\Property(property="email", type="string", example="user@example.com"),
 *     @OA\Property(property="phone", type="string", example="0123456789"),
 *     @OA\Property(property="address", type="string", example="123 Đường ABC"),
 *     @OA\Property(property="role", type="string", example="user"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Pet",
 *     type="object",
 *     @OA\Property(property="petID", type="integer", example=1),
 *     @OA\Property(property="userID", type="integer", example=1),
 *     @OA\Property(property="petName", type="string", example="Lucky"),
 *     @OA\Property(property="species", type="string", example="Chó"),
 *     @OA\Property(property="breed", type="string", example="Golden Retriever"),
 *     @OA\Property(property="weight", type="number", format="float", example=15.5),
 *     @OA\Property(property="backLength", type="number", format="float", example=50),
 *     @OA\Property(property="birthDate", type="string", format="date", example="2022-01-15"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female"}, example="male"),
 *     @OA\Property(property="petImage", type="string", example="pets/image.jpg")
 * )
 * 
 * @OA\Schema(
 *     schema="Service",
 *     type="object",
 *     @OA\Property(property="serviceID", type="integer", example=1),
 *     @OA\Property(property="serviceName", type="string", example="Tắm và sấy khô"),
 *     @OA\Property(property="description", type="string", example="Dịch vụ tắm spa cao cấp"),
 *     @OA\Property(property="price", type="number", format="float", example=150000),
 *     @OA\Property(property="duration", type="integer", example=60),
 *     @OA\Property(property="categoryID", type="integer", example=1),
 *     @OA\Property(property="image", type="string", example="services/image.jpg")
 * )
 * 
 * @OA\Schema(
 *     schema="ServiceCategory",
 *     type="object",
 *     @OA\Property(property="categoryID", type="integer", example=1),
 *     @OA\Property(property="categoryName", type="string", example="Làm đẹp"),
 *     @OA\Property(property="description", type="string", example="Các dịch vụ làm đẹp cho thú cưng")
 * )
 * 
 * @OA\Schema(
 *     schema="Appointment",
 *     type="object",
 *     @OA\Property(property="appointmentID", type="integer", example=1),
 *     @OA\Property(property="userID", type="integer", example=1),
 *     @OA\Property(property="petID", type="integer", example=1),
 *     @OA\Property(property="employeeID", type="integer", example=1),
 *     @OA\Property(property="appointmentDate", type="string", format="date-time"),
 *     @OA\Property(property="endDate", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="note", type="string", example="Ghi chú"),
 *     @OA\Property(property="status", type="string", enum={"Pending", "Confirmed", "Completed", "Cancelled"})
 * )
 * 
 * @OA\Schema(
 *     schema="Employee",
 *     type="object",
 *     @OA\Property(property="employeeID", type="integer", example=1),
 *     @OA\Property(property="employeeName", type="string", example="Trần Văn B"),
 *     @OA\Property(property="email", type="string", example="employee@petspa.com"),
 *     @OA\Property(property="phone", type="string", example="0987654321"),
 *     @OA\Property(property="roleID", type="integer", example=1)
 * )
 * 
 * @OA\Schema(
 *     schema="EmployeeRole",
 *     type="object",
 *     @OA\Property(property="roleID", type="integer", example=1),
 *     @OA\Property(property="roleName", type="string", example="Bác sĩ thú y"),
 *     @OA\Property(property="description", type="string", example="Nhân viên khám chữa bệnh cho thú cưng")
 * )
 * 
 * @OA\Schema(
 *     schema="WorkSchedule",
 *     type="object",
 *     @OA\Property(property="scheduleID", type="integer", example=1),
 *     @OA\Property(property="employeeID", type="integer", example=1),
 *     @OA\Property(property="dayOfWeek", type="string", example="Monday"),
 *     @OA\Property(property="startTime", type="string", format="time", example="08:00:00"),
 *     @OA\Property(property="endTime", type="string", format="time", example="17:00:00")
 * )
 * 
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Thành công!"),
 *     @OA\Property(property="data", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Lỗi xảy ra!")
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 */
