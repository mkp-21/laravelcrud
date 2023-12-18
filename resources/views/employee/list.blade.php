<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 5 Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.min') }} ">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<body>
<div class="bg-dark py-3">
    <div class="container">
        <div class="h4 text-white">simple crud</div>
</div>
</div>
<div class="container">
    <div class="d-flex justify-content-between py-3">
        <div class="h5">Employee</div>
        <div>
    <a href="{{route('employee.create')}}" class="btn btn-primary">Create</a>
</div>
</div>
<div class="card border-0 shadow-lg">
    <div class="card-body">
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>IMAGE</th>
                <th>EMAIL</th>
                <th>ADDRESS</th>
                <th>ACTION</th>
</tr>

@if($employees->isNotEmpty())
    @foreach($employees as $employee)
<tr>   
<td>{{$employee->id}}</td>

<td>@if($employee->image != '' && file_exists(public_path().'/uploads/employee/'.$employee->image))
                            <img src="{{ url('uploads/employee/'.$employee->image) }}" alt="" width="40" height="40" class="rounded-circle">
                            @else
                            <img src="{{ url('asset/images/no-image.png') }}" alt="" width="40" height="40" class="rounded-circle">
                            @endif</td>
<td>{{ $employee->name }}</td>
<td>{{ $employee->email }}</td>
<td>{{ $employee->address }}</td>
<td><a href="{{ route('employee.edit',$employee->id) }}" class="btn btn-primary btn-sm">edit</a>
<a href="#" onclick="deleteEmployee({{ $employee->id }})" class="btn btn-danger btn-sm">Delete</a>

                            <form id="employee-edit-action-{{ $employee->id }}" action="{{ route('employee.destroy',$employee->id) }}" method="post">
                                @csrf
                                @method('delete')
                            </form>



</tr>
@endforeach
@endif
</table>
</div>
</div> 
<div class="mt-3">
            {{ $employees->links() }}
        </div>  
</div>

</body>

</html>
<script>
    function deleteEmployee(id) {
        if (confirm("Are you sure you want to delete?")) {
            document.getElementById('employee-edit-action-'+id).submit();
        }
    }
    </script>