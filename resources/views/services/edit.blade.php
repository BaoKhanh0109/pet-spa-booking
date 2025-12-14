<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật Dịch vụ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Chỉnh sửa Dịch Vụ: <span class="text-primary">{{ $service->serviceName }}</span></h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('services.update', $service->serviceID) }}" method="POST">
        @csrf
        @method('PUT') <div class="mb-3">
            <label class="form-label">Tên dịch vụ:</label>
            <input type="text" name="serviceName" class="form-control" 
                   value="{{ old('serviceName', $service->serviceName) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả:</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá tiền (VNĐ):</label>
            <input type="number" name="price" class="form-control" 
                   value="{{ old('price', $service->price) }}" required>
        </div>

        <button type="submit" class="btn btn-warning">Cập nhật thay đổi</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
</div>
</body>
</html>