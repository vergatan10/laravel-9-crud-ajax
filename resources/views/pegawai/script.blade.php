<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverside: true,
            ajax: "{{ url('pegawaiAjax') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false

            }, {
                data: 'nama',
                name: 'Nama'
            }, {
                data: 'email',
                name: 'Email'
            }, {
                data: 'aksi',
                name: 'Aksi'
            }]
        });
    });
    //Global Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    // 02 PROCESS SIMPAN
    $('body').on('click', '.tombol-tambah', function(e) {
        e.preventDefault();

        $('#exampleModal').modal('show');

        $('.tombol-simpan').click(function() {
            simpan();
        });
    });

    // 03 PROCESS EDIT
    $('body').on('click', '.tombol-edit', function(e) {
        var id = $(this).data('id');
        // alert(id);
        $.ajax({
            url: 'pegawaiAjax/' + id + '/edit',
            type: 'GET',
            success: function(response) {
                $('#exampleModal').modal('show');
                $('#nama').val(response.result.nama);
                $('#email').val(response.result.email);
                console.log(response.result);
                $('.tombol-simpan').click(function() {
                    simpan(id);
                })
            }
        })
    });

    // 04 PROSES DELETE
    $('body').on('click', '.tombol-del', function(e) {
        if (confirm('Apakah anda yakin ingin menghapus data ini?') == true) {
            var id = $(this).data('id');
            $.ajax({
                url: 'pegawaiAjax/' + id,
                type: 'DELETE',
            });
            $('#myTable').DataTable().ajax.reload();
        }
    });

    // FUNGSI SIMPAN DAN UPDATE
    function simpan(id = '') {
        if (id == '') {
            var var_url = 'pegawaiAjax';
            var var_type = 'POST';
        } else {
            var var_url = 'pegawaiAjax/' + id;
            var var_type = 'PUT';
        }

        // var nama = $('#nama').val();
        // var email = $('#email').val();

        $.ajax({
            url: var_url,
            type: var_type,
            data: {
                nama: $('#nama').val(),
                email: $('#email').val()
            },
            success: function(response) {
                if (response.errors) {
                    console.log(response.errors);
                    $('.alert-danger').removeClass('d-none');
                    $('.alert-danger').append("<ul>");
                    $.each(response.errors, function(key, value) {
                        $('.alert-danger').find('ul').append("<li>" + value +
                            "</li>");
                    })
                    $('.alert-danger').append("</ul>");
                } else {
                    $('.alert-success').removeClass('d-none');
                    $('.alert-success').html(response.success);
                }
                $('#myTable').DataTable().ajax.reload();
            }

        })
    }


    $('#exampleModal').on('hidden.bs.modal', function() {
        $('#nama').val('');
        $('#email').val('');

        $('.alert-danger').addClass('d-none');
        $('.alert-danger').html('');

        $('.alert-success').addClass('d-none');
        $('.alert-success').html('');
    })
</script>
