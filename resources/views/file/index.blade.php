@extends('layouts/app')

@section('css')
{{-- swal --}}

<link rel="stylesheet"
      href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="container">
    <h2>upload file</h2>
    <form method="POST"
          action="/file"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">title</label>
            <input type="title"
                   class="form-control"
                   id="title"
                   aria-describedby="titleHelp"
                   name="title"
                   required>
            <small id="titleHelp"
                   class="form-text text-muted">file title in here.</small>
        </div>
        <div class="form-group">
            <label for="file">file</label>
            <input type="file"
                   class="form-control"
                   id="file"
                   name="file">
        </div>
        <div class="form-group">
            <label for="description">description</label>
            <input type="description"
                   class="form-control"
                   id="description"
                   name="description">
        </div>
        <button type="submit"
                class="btn btn-primary">Submit</button>
    </form>

    <hr>
    <h2>file list</h2>

    <table id="example"
           class="table table-striped table-bordered"
           style="width:100%">
        <thead>

            <tr>
                <th>title</th>
                <th>upload_date</th>
                <th>description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($file_data as $item)
            <tr>
                <td>{{ $item->title}}</td>
                <td>{{ $item->created_at}}</td>
                <td>{{ $item->description}}</td>
                <td>
                    <a href="file/{{$item->id}}"
                       class="btn btn-primary">download</a>
                    <a href="api/file/get/{{ $item->id}}"
                       id="get_json_{{ $item->id}}"
                       class="btn btn-info">read</a>
                    <a href="#"
                       id="delete_btn_{{ $item->id}}"
                       class="btn btn-danger"
                       onclick="delete_file({{ $item->id}})">delete</a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>


</div>



@endsection



@section('js')
{{-- swal --}}
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@9.10.12/dist/sweetalert2.all.min.js'></script>
{{-- datatable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>

<script>
    let delete_file = function(id){
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.value) {
            let delete_btn = document.querySelector(`#delete_btn_${id}`);
            axios({
                url:`/file/${id}`,
                method:'delete'
            })
            .then(function (rst) {
                location.reload();
            })
        }
        })
    }
</script>
@endsection
