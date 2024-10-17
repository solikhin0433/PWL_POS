@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row">
        <!-- Bagian Kiri: Profil Foto -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Placeholder Gambar Profil -->
                    <div class="profile-picture mb-3">
                        <img 
                            src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('foto.png') }}" 
                            alt="User Avatar" 
                            class="img-circle rounded-circle" 
                            width="100"
                            height="100" 
                            style="object-fit: cover;">
                    </div>

                    <!-- Nama Pengguna -->
                    <h4>{{ auth()->user()->nama ?? 'user' }}</h4>

                    <!-- Form untuk Mengganti Foto Profil -->
                    <form action="{{ url('profile/update_profile') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <div class="form-group text-center">
                            <!-- Input file dengan margin -->
                            <input type="file" name="avatar" class="form-control-file mb-2" accept="image/*">
                            <!-- Tombol Ganti Foto Profil -->
                            <button type="submit" class="btn btn-success">Ganti Foto Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Edit Profil -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <!-- Tab-style Navigation -->
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#editProfile" data-toggle="tab">Edit Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#editPassword" data-toggle="tab">Edit Password</a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body tab-content">
                    <!-- Tab Content for Edit Profile -->
                    <div class="tab-pane active" id="editProfile">
                        <form action="{{ url('profile/update_profile') }}" method="POST">
                            @csrf
                            <!-- Username -->
                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label">{{ __('Username') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="username" value="{{ auth()->user()->username }}" readonly>
                                </div>
                            </div>

                            <!-- Nama -->
                            <div class="form-group row">
                                <label for="nama" class="col-md-4 col-form-label">{{ __('Nama') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', auth()->user()->nama) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                              <label for="level_nama" class="col-md-4 col-form-label">{{ __('Level') }}</label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="level_nama" value="{{ $level_nama }}" readonly>
                              </div>
                          </div>

                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Content for Edit Password -->
                    <div class="tab-pane" id="editPassword">
                        <!-- Form for updating password -->
                        <form action="{{ url('profile/update_password') }}" method="POST">
                            @csrf
                            <!-- Password -->
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label">{{ __('Password Baru') }}</label>
                                <div class="col-md-8">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label">{{ __('Konfirmasi Password') }}</label>
                                <div class="col-md-8">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
