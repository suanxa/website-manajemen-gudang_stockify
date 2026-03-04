<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getUserListData()
    {
        return $this->userRepo->getAll();
    }

    public function createUser(array $data)
    {
        // Enkripsi password sebelum disimpan ke database
        $data['password'] = Hash::make($data['password']);
        return $this->userRepo->store($data);
    }

    public function updateUser($id, array $data)
    {
        // Jika password diisi, enkripsi password baru. Jika kosong, hapus dari array agar tidak terupdate
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userRepo->delete($id);
    }
}