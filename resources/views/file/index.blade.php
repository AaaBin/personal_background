@extends('layouts/app')

@section('css')
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">

<style>
    html {
        scroll-behavior: smooth;
    }

    textarea {
        min-height: 300px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h2>upload json</h2>
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
            <textarea type="description"
                      class="form-control"
                      id="description"
                      name="description"></textarea>
        </div>
        <button type="submit"
                class="btn btn-primary">Submit</button>
    </form>

    <hr>

    @foreach ($file_data as $item)
    <div class="collapse my-5"
         id="edit_area{{$item->id}}">
        <h2>edit</h2>
        <div class="card card-body">
            <form method="POST"
                  action="/file/{{ $item->id }}"
                  enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <input class="d-none"
                       value="{{$item->id}}"
                       name="id">
                <div class="form-group">
                    <label for="title{{$item->id}}">title</label>
                    <input type="title"
                           class="form-control"
                           id="title{{$item->id}}"
                           aria-describedby="titleHelp"
                           name="title"
                           required
                           value="{{$item->title}}">
                    <small id="titleHelp{{$item->id}}"
                           class="form-text text-muted">file title in here.</small>
                </div>
                <div class="form-group">
                    <label for="json{{$item->id}}">json</label>
                    <textarea class="form-control json_edit_area"
                              id="json{{$item->id}}"
                              name="json"></textarea>
                </div>
                <div class="form-group">
                    <label for="description{{$item->id}}">description</label>
                    <textarea type="description"
                              class="form-control"
                              id="description{{$item->id}}"
                              name="description">{{$item->description}}</textarea>
                </div>
                <button type="submit"
                        class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    @endforeach


    <h2>json list</h2>
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
                    <a href="{{ $item->file_url }}"
                       id="get_json_{{ $item->id}}"
                       class="btn btn-info">read</a>
                    <a class="btn btn-warning edit_btn"
                       data-toggle="collapse"
                       href="#edit_area{{$item->id}}"
                       data-id="{{ $item->id }}"
                       role="button">edit</a>
                    <a href="#"
                       id="delete_btn_{{ $item->id}}"
                       class="btn btn-danger"
                       onclick="delete_file({{ $item->id}})">delete</a>
                    <a id="move_to_edit{{$item->id}}"
                       class="d-none"
                       href="#edit_area{{$item->id}}"></a>
                </td>
            </tr>



            @endforeach

        </tbody>
    </table>
    <hr>



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
<script>
    // 將data傳入js
    let data = {!! json_encode($file_data) !!};
</script>
<script>
    // 展開摺疊區塊，移動到編輯區域
    $('.edit_btn').on("click",function () {
        let id = $(this).attr('data-id')
        setTimeout(function () {
            $(`#move_to_edit${id}`)[0].click();
        },500)
    })
</script>
<script>
    // 將檔案內容寫入編輯區塊
    data.forEach(function (val,key) {
            let id = val.id;

        $.ajax({
            url: `http://ec2-3-17-155-235.us-east-2.compute.amazonaws.com/api/file/get/${id}`,
            }).done(function (result) {

            $(`#json${id}`).text(result);

            });

        });



</script>
<script>
    // 摺疊區塊不同時多開
    $('.collapse').on('show.bs.collapse', function () {
        $('*').collapse('hide');
    })
</script>
@endsection
