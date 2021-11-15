@extends('profile.layout')

@section('title', 'Registration')

@section('body')
    @parent

    <div class="card">
        <div class="card-body">
            <form id="profile" action="{{ route('profile.update') }}" method="post">
                @method('PUT')
                @csrf
                <div class="alert alert-success d-none"><ul></ul></div>
                <div class="alert alert-danger d-none"><ul></ul></div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input id="name" name="name" class="form-control" value="{{ $user->name }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input name="email" class="form-control" value="{{ $user->email }}">
                    </div>
                </div>

                <?php /*
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" value="(239) 816-9029">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Mobile</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" value="(320) 380-4539">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" value="Bay Area, San Francisco, CA">
                                </div>
                            </div>
                            */ ?>
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <input type="submit" class="btn btn-primary px-4" value="Save Changes">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">Project Status</h5>
                    <p>Web Design</p>
                    <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p>Website Markup</p>
                    <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p>One Page</p>
                    <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p>Mobile Template</p>
                    <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p>Backend API</p>
                    <div class="progress" style="height: 5px">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 99%" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#avatar').click(function() {
                $('#input-avatar').click();
            });

            $('#input-avatar').change(function() {
                $(this).closest('form').submit();
            });

            $('#profile').submit(function($e) {
                $e.preventDefault();

                let $form = $(this);
                console.log($form.attr('action'), $form.serialize());

                $.ajax({
                    data: $form.serialize(),
                    dataType: "json",
                    "method": "PUT",
                    url: $form.attr('action'),
                    beforeSend: function() {
                        $form.find('input').prop('disabled', true);
                    },
                    complete: function() {
                        setTimeout(function() {
                            $form.find('input').prop('disabled', false);
                        }, 650)
                    },
                    resetAlert: function() {
                        $form.find('.alert').each(function() {
                            $(this).addClass('d-none');
                            $(this).find('ul').html('');
                        })
                    },
                    error: function(data) {
                        this.resetAlert()
                        if (data.status === 422) {
                            let $alert = $form.find('.alert-danger');
                            $alert.removeClass('d-none');

                            $.each(data.responseJSON.errors, function(key, value) {
                                $alert.find('ul').append($('<li>').text(value));
                            });
                        }
                    },
                    success: function(data) {
                        // TODO homework
                    }
                });
            });
        });
    </script>
@endsection
