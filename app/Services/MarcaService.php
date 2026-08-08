<?php

namespace App\Services;

use App\Repositories\MarcaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarcaService extends BaseService implements MarcaServiceInterface
{
    public function __construct(MarcaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getAll()
    {
        return $this->repository->getAllOrdered();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $marca = $this->repository->create([
                'nombre' => $data['nombre'],
                'slug' => Str::slug($data['nombre']),
                'descripcion' => $data['descripcion'] ?? null,
                'logo' => $this->storeImage($data['logo'] ?? null),
                'banner' => $this->storeImage($data['banner'] ?? null, 1600),
                'sitio_web' => $data['sitio_web'] ?? null,
                'orden' => $data['orden'] ?? 0,
                'estado' => 1,
            ]);

            DB::commit();

            return $marca;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        $marca = $this->repository->findOrFail($id);

        DB::beginTransaction();

        try {
            $logo = $marca->logo;
            $banner = $marca->banner;

            if ($this->isImage($data['logo'] ?? null)) {
                $this->deleteImage($marca->logo);
                $logo = $this->storeImage($data['logo']);
            }

            if ($this->isImage($data['banner'] ?? null)) {
                $this->deleteImage($marca->banner);
                $banner = $this->storeImage($data['banner'], 1600);
            }

            $this->repository->update($marca, [
                'nombre' => $data['nombre'],
                'slug' => Str::slug($data['nombre']),
                'descripcion' => $data['descripcion'] ?? null,
                'logo' => $logo,
                'banner' => $banner,
                'sitio_web' => $data['sitio_web'] ?? null,
                'orden' => $data['orden'] ?? 0,
                'estado' => $data['estado'] ?? 1,
            ]);

            DB::commit();

            return $marca;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $marca = $this->repository->findOrFail($id);

        $this->deleteImage($marca->logo);
        $this->deleteImage($marca->banner);

        return $this->repository->delete($marca);
    }

    protected function isImage($file)
    {
        return $file instanceof UploadedFile && $file->isValid();
    }

    protected function storeImage($file, $width = 800)
    {
        if (!$this->isImage($file)) {
            return null;
        }

        return uploadImageOptimized($file, 'marcas', $width);
    }

    protected function deleteImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
