{{-- @extends('layout.app')

@section('content')
    
@endsection --}}

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clothing Shop</title>

    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    {{-- Bootstrap --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" /> --}}
</head>

<body id="page-top">
    <section class="login">
        <div class="container py-5 ">
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col col-xl-10">
                    <div class="card shadow mt-5 " style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="assets/img/login.jpg" alt="login form" class="img-fluid"
                                    style="border-radius: 1rem 0 0 1rem; height:600px" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="post" id="loginForm" action="{{ route('login') }}">
                                        @csrf
                                        <div class="d-flex text-center mb-3 pb-1 ">
                                            <!-- <i class="fa-solid fa-fan fs-3 pe-2 "></i> -->
                                            <i class="fa-brands fa-wizards-of-the-coast fs-1 me-3 text-primary"></i>
                                            <span class="h3 fw-bold mb-0 login-heading text-primary">Clothing
                                                Shop</span>
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your
                                            account</h5>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="email">Email address</label>
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-lg" />
                                            <div class="invalid-feedback">Please enter a valid email</div>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" id="password" name="password"
                                                class="form-control form-control-lg" />
                                            <div class="invalid-feedback">Password must be at least 6 characters</div>
                                        </div>

                                        <a class="small " href="#!">Forgot password?</a>

                                        <div class="pt-4 mb-4">
                                            <button class="btn  btn-block  btn btn-primary" name="login"
                                                id="login" type="submit">Login</button>
                                        </div>


                                        <div class="">
                                            <a href={{route('google.login')}} class="btn btn-block  btn btn-danger" name="google"
                                                id="google" type="submit"><i class="fa-brands fa-google"></i> Login With google</a>
                                        </div>

                                        <a href="#!" class="small text-muted">Terms of use.</a>
                                        <a href="#!" class="small text-muted text-end">Privacy policy</a>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap JavaScript Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <script>
        $(document).ready(function() {
            $("#loginForm").on("submit", function(e) {
                e.preventDefault();

                    let email = $("#email").val();
                    let password = $("#password").val();
                let isValid = true;

                $(".form-control").removeClass("is-invalid");

                const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
                if (email === "" || !emailPattern.test(email)) {
                    $("#email").addClass("is-invalid");
                    isValid = false;
                }

                if (password === "" || password.length < 6) {
                    $("#password").addClass("is-invalid");
                    isValid = false;
                }

                if (!isValid) return;

                $.ajax({
                    url: '/login',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            text: 'Redirecting...',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Redirect after short delay
                        setTimeout(() => {
                            window.location.href = "/tables";
                        }, 2000);
                    },
                    error: function(xhr) {
                        let message = "Something went wrong!";
                        if (xhr.status === 422 || xhr.status === 400) {
                            message = xhr.responseJSON?.message || "Invalid email or password.";
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: message,
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
