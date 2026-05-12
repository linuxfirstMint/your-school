<?php

namespace App\Services;

use App\Models\AccommodationPlan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AccommodationPlanService
{
    /**
     * @param UploadedFile[] $images
     * @param array<int, int> $prices  [room_type_id => price]
     */
    public function store(string $name, ?string $description, array $images, array $prices): AccommodationPlan
    {
        $plan = AccommodationPlan::create([
            'name'        => $name,
            'description' => $description,
        ]);

        $this->saveImages($plan, $images);
        $this->syncPrices($plan, $prices);

        return $plan->load('planImages');
    }

    /**
     * @param UploadedFile[] $images
     * @param array<int, int> $prices
     * @param int[] $deleteImageIds
     */
    public function update(
        AccommodationPlan $plan,
        string $name,
        ?string $description,
        array $images,
        array $prices,
        array $deleteImageIds = []
    ): void {
        $plan->update([
            'name'        => $name,
            'description' => $description,
        ]);

        if (!empty($deleteImageIds)) {
            $this->deleteImagesByIds($plan, $deleteImageIds);
        }

        if (!empty($images)) {
            $this->saveImages($plan, $images);
        }

        $this->syncPrices($plan, $prices);
    }

    public function destroy(AccommodationPlan $plan): void
    {
        $this->deleteImages($plan);
        $plan->delete();
    }

    /** @param UploadedFile[] $images */
    private function saveImages(AccommodationPlan $plan, array $images): void
    {
        foreach ($images as $image) {
            $path = $image->store('plan-images', 'public');
            $plan->planImages()->create([
                'name'       => basename((string) $path),
                'image_path' => $path,
            ]);
        }
    }

    private function deleteImages(AccommodationPlan $plan): void
    {
        foreach ($plan->planImages()->get() as $planImage) {
            Storage::disk('public')->delete($planImage->image_path);
        }
        $plan->planImages()->delete();
    }

    /** @param int[] $ids */
    private function deleteImagesByIds(AccommodationPlan $plan, array $ids): void
    {
        $images = $plan->planImages()->whereIn('id', $ids)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $plan->planImages()->whereIn('id', $ids)->delete();
    }

    /** @param array<int, int> $prices */
    private function syncPrices(AccommodationPlan $plan, array $prices): void
    {
        foreach ($prices as $roomTypeId => $price) {
            $plan->planRoomPrices()->updateOrCreate(
                ['room_type_id' => $roomTypeId],
                ['price'        => $price]
            );
        }
    }
}
