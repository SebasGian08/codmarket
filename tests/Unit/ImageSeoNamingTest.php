<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageSeoNamingTest extends TestCase
{
    public function testGeneraUnNombreSeoDesdeUnNombreDescriptivo(): void
    {
        $this->assertSame(
            'infusion-de-te-verde',
            generarNombreBaseImagenSeo('Infusión de Té Verde')
        );
    }

    public function testSubeImagenConNombreSeoYEvitaColisiones(): void
    {
        $folder = 'seo-test-' . uniqid();
        $directory = base_path('uploads/' . $folder);

        try {
            $firstPath = uploadImageWithSeoName(
                UploadedFile::fake()->create('imagen.png', 10, 'image/png'),
                $folder,
                'Infusión de Té Verde'
            );
            $secondPath = uploadImageWithSeoName(
                UploadedFile::fake()->create('otra-imagen.png', 10, 'image/png'),
                $folder,
                'Infusión de Té Verde'
            );

            $this->assertSame('uploads/' . $folder . '/infusion-de-te-verde.png', $firstPath);
            $this->assertSame('uploads/' . $folder . '/infusion-de-te-verde-1.png', $secondPath);
            $this->assertFileExists(base_path($firstPath));
            $this->assertFileExists(base_path($secondPath));
        } finally {
            foreach (glob($directory . '/*') as $file) {
                unlink($file);
            }

            if (is_dir($directory)) {
                rmdir($directory);
            }
        }
    }
}
