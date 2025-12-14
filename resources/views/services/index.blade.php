<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Dịch vụ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Quản lý Dịch vụ Thú Cưng</h1>

    <div class="mb-3 text-end">
        <a href="{{ route('services.create') }}" class="btn btn-success">
            Thêm dịch vụ mới
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên Dịch vụ</th>
                        <th>Mô tả</th>
                        <th>Giá tiền</th>
                        <th style="width: 150px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                    <tr>
                        <td>{{ $service->serviceID }}</td>
                        <td><strong>{{ $service->serviceName }}</strong></td>
                        <td>{{ $service->description }}</td>
                        <td class="text-danger fw-bold">
                            {{ number_format($service->price, 0, ',', '.') }} VNĐ
                        </td>
                        <td>
                            <a href="{{ route('services.edit', $service->serviceID) }}" class="btn btn-sm btn-warning">Sửa</a>
                            
                            <form action="{{ route('services.destroy', $service->serviceID) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>