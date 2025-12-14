<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Dịch vụ mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Thêm Dịch Vụ Mới</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('services.store') }}" method="POST">
        @csrf <div class="mb-3">
            <label class="form-label">Tên dịch vụ:</label>
            <input type="text" name="serviceName" class="form-control" placeholder="Ví dụ: Cắt tỉa lông" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả:</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá tiền (VNĐ):</label>
            <input type="number" name="price" class="form-control" placeholder="Ví dụ: 200000" required>
        </div>

        <button type="submit" class="btn btn-primary">Lưu lại</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>