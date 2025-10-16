<?php

namespace App\Console\Commands;

use App\Models\MapBarangay;
use App\Models\MapCity;
use App\Models\MapState;
use Google\Cloud\Core\Exception\ServiceException;
use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class UploadMapStatesToFirebase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:upload-map-states';
    protected $description = 'Upload all MapState records to Firebase Firestore';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->migrateBarangays();
        // $this->migrateStates();
        // $this->migrateCities();
    }

    private function migrateBarangays()
    {
        try {
            // Use the existing bound Firestore instance
            $firestoreDB = app('firebase.firestore')->database();

            $barangays = MapBarangay::all();
            $this->info("Uploading {$barangays->count()} Mapbarangays records...");

            foreach ($barangays as $item) {
                $firestoreDB->collection('map-barangays')
                    ->document((string)$item->id)
                    ->set([
                        'name' => $item->name,
                        'map_city_id' => $item->map_city_id,
                        'gid_3' => $item->gid_3,
                        'geometry' => json_encode($item->geometry),
                        'created_at' => optional($item->created_at)->toDateTimeString(),
                        'updated_at' => optional($item->updated_at)->toDateTimeString(),
                    ]);
            }

            $this->info('✅ All MapState data uploaded successfully to Firestore!');

        } catch (ServiceException $e) {
            $this->error('❌ Firestore error: '.$e->getMessage());
        } catch (\Throwable $e) {
            $this->error('❌ General error: '.$e->getMessage());
        }
    }

    private function migrateCities()
    {
        try {
            // Use the existing bound Firestore instance
            $firestoreDB = app('firebase.firestore')->database();

            $cities = MapCity::all();
            $this->info("Uploading {$cities->count()} MapCities records...");

            foreach ($cities as $item) {
                $firestoreDB->collection('map-cities')
                    ->document((string)$item->id)
                    ->set([
                        'name' => $item->name,
                        'map_state_id' => $item->map_state_id,
                        'gid_2' => $item->gid_2,
                        'merge_on' => json_encode($item->merge_on),
                        'geometry' => json_encode($item->geometry),
                        'created_at' => optional($item->created_at)->toDateTimeString(),
                        'updated_at' => optional($item->updated_at)->toDateTimeString(),
                    ]);
            }

            $this->info('✅ All MapState data uploaded successfully to Firestore!');

        } catch (ServiceException $e) {
            $this->error('❌ Firestore error: '.$e->getMessage());
        } catch (\Throwable $e) {
            $this->error('❌ General error: '.$e->getMessage());
        }
    }

    private function migrateStates()
    {
        try {
            // Use the existing bound Firestore instance
            $firestoreDB = app('firebase.firestore')->database();

            $states = MapState::all();
            $this->info("Uploading {$states->count()} MapState records...");

            foreach ($states as $state) {
                $firestoreDB->collection('map-states')
                    ->document((string)$state->id)
                    ->set([
                        'gid_1' => $state->gid_1,
                        'name' => $state->name,
                        'geometry' => json_encode($state->geometry),
                        'created_at' => optional($state->created_at)->toDateTimeString(),
                        'updated_at' => optional($state->updated_at)->toDateTimeString(),
                    ]);
            }

            $this->info('✅ All MapState data uploaded successfully to Firestore!');

        } catch (ServiceException $e) {
            $this->error('❌ Firestore error: '.$e->getMessage());
        } catch (\Throwable $e) {
            $this->error('❌ General error: '.$e->getMessage());
        }
    }
}
