<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class DeletePDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-PDF';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminacion de archivos PDF despues de 24 horas del directorio storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basePath = 'pdf';

        $this->info("Eliminando archivos PDF en el directorio" . Storage::path($basePath));
        if(!Storage::disk('public')->exists($basePath)) {
            $this->error("El directorio {$basePath} no existe.");
            return;
        }
        
        // Obtener los archivos
        $files = Storage::disk('public')->files($basePath);
        $this->info("Archivos encontrados:" . count($files));

        foreach ($files as $file) {
        $this->line("- " . Storage::disk('public')->path($file));
        }
        
        $cutoffDate = Carbon::now()->subHours(24);
        $deleted = 0;
       
        foreach ($files as $file) {
            // si es pdf
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (strtolower($extension) !== 'pdf') {
                continue;
            }
            
            // Verificar si es más antiguo que 24 horas
            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));
            if ($lastModified->lt($cutoffDate)) {
                // Eliminar archivo
                if (Storage::disk('public')->delete($file)) {
                    $deleted++;
                    $this->info("Eliminado: " . Storage::disk('public')->path($file));
                }
            }
        }
        
        $this->info("Se eliminaron {$deleted} archivos PDF.");
    }
}
