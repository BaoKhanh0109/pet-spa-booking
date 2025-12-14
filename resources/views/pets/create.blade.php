<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Thú Cưng Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">🐶 Thêm Thú Cưng Mới</h4>
                    </div>
                    <div class="card-body">
                        {{-- Hiển thị thông báo thành công --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Hiển thị lỗi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form bắt đầu --}}
                        <form action="{{ url('/pets') }}" method="POST" enctype="multipart/form-data">
                            @csrf <div class="mb-3">
                                <label class="form-label">Mã Khách Hàng (Customer ID)</label>
                                <input type="number" name="customerID" class="form-control" required placeholder="Nhập ID chủ nuôi">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tên Thú Cưng</label>
                                    <input type="text" name="petName" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Loài (Chó, Mèo...)</label>
                                    <select name="species" class="form-select">
                                        <option value="Chó">Chó</option>
                                        <option value="Mèo">Mèo</option>
                                        <option value="Khác">Khác</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Giống (Breed)</label>
                                    <input type="text" name="breed" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tuổi</label>
                                    <input type="number" name="age" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cân nặng (kg)</label>
                                    <input type="number" step="0.1" name="weight" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="petImage" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tiền sử bệnh án</label>
                                <textarea name="medicalHistory" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Lưu Thú Cưng</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>