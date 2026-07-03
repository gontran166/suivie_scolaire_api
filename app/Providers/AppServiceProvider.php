<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\ClasseRepository;
use App\Repositories\EleveRepository;
use App\Repositories\MatiereRepository;
use App\Repositories\NoteRepository;
use App\Repositories\PaiementRepository;
use App\Repositories\AbsenceRepository;
use App\Repositories\AnnonceRepository;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ClasseRepositoryInterface;
use App\Repositories\Contracts\EleveRepositoryInterface;
use App\Repositories\Contracts\MatiereRepositoryInterface;
use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Contracts\PaiementRepositoryInterface;
use App\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Repositories\Contracts\AnnonceRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $repositories = [
            UserRepositoryInterface::class => UserRepository::class,
            ClasseRepositoryInterface::class => ClasseRepository::class,
            EleveRepositoryInterface::class => EleveRepository::class,
            MatiereRepositoryInterface::class => MatiereRepository::class,
            NoteRepositoryInterface::class => NoteRepository::class,
            PaiementRepositoryInterface::class => PaiementRepository::class,
            AbsenceRepositoryInterface::class => AbsenceRepository::class,
            AnnonceRepositoryInterface::class => AnnonceRepository::class,
        ];

        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
