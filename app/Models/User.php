<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Verificar se o usuário logado é administrador da assinatura vai ter acesso ao users/manage
    public function isAdmin()
    {
        $emailAssinatura = EmailAssinatura::whereHas('assinatura', function ($query) {
            $query->where('user_id', $this->id);
        })->first();
        if ($emailAssinatura && $emailAssinatura->is_administrador == 1) {
            return true;
        }
        return false;
    }

    public function isDesenvolvedor()
    {
        $emailsPermitidos = [
            'richardjonhshm@gmail.com',
            // ...
        ];

        return in_array($this->email, $emailsPermitidos);
    }

    public function isFolder()
    {
        $assinaturaId = \App\Models\EmailAssinatura::where('email', $this->email)->first()?->assinatura_id;

        if (!$assinaturaId) {
            return false;
        }

        $folder = \App\Models\Assinatura::find($assinaturaId)?->folder;

        return $folder ?: false;
    }



}
