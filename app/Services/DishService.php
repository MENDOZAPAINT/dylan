<?php

namespace App\Services;

use App\Models\Dish;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DishService
{
    public function create(array $data): Dish
    {
        $data = $this->storeImage($data);

        return Dish::create($data);
    }

    public function update(Dish $dish, array $data): Dish
    {
        $data = $this->storeImage($data, $dish);

        $dish->update($data);

        return $dish->refresh();
    }

    public function delete(Dish $dish): void
    {
        $this->deleteImage($dish);

        $dish->delete($dish->id);
    }

    private function storeImage(array $data, ?Dish $dish = null): array
    {
        if (! isset($data['image']) || ! $data['image'] instanceof UploadedFile) {
            unset($data['image']);

            return $data;
        }

        if ($dish !== null) {
            $this->deleteImage($dish);
        }

        $data['image'] = $data['image']->store('dishes', 'public');

        return $data;
    }

    private function deleteImage(Dish $dish): void
    {
        if ($dish->image) {
            Storage::disk('public')->delete($dish->image);
        }
    }
}
