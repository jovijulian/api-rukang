@section('title', 'MEET UP BOXER')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container-xxl {
        background-color: #000000;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #000000;
    }

    h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #fff !important;
    }

    p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        max-width: 80%;
        text-align: center;
        color: #fff !important;
    }

    .btn {
        padding: 1rem 2rem !important;
        background-color: #0C6696 !important;
        color: #fff !important;
        text-decoration: none !important;
        border-radius: 5px !important;
        font-size: 1.2rem !important;
        transition: background-color 0.2s !important;
    }
</style>
@section('content')
    <div class="container-xxl">

        <div class="container">
            @php
                $user = \App\Models\User::find(request('id'));
            @endphp
            <h1>Hallo, {{ $user->name }}</h1>
            <p>Verifikasi akun anda berhasil, silahkan login dengan cara klik tombol dibawah.</p>
            <a href="{{ url('/users/login') }}" class="btn">Login</a>
        </div>
    </div>
@endsection
