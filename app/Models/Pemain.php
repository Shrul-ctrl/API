<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemain extends Model
{
    use HasFactory;

    public $fillable = ['nama_pemain', 'posisi','foto', 'tgl_lahir', 'harga_pasar','id_klub','negara'];

    public function klub()
    {
        return $this->BelongsTo(Klub::class, 'id_klub');
    }
}
