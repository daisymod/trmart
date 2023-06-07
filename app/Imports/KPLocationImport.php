<?php

namespace App\Imports;

// Maatwebsite
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
// Illuminate
use Illuminate\Support\Collection;
// App
use App\Models\KPLocation;
use App\Models\KPPostCode;

class KPLocationImport implements ToCollection, WithHeadingRow
{

    private function setLocation ($main, $parentId)
    {
        $query = KPLocation::query()->where(['name' => $main, 'parent_id' => $parentId])->value('id');
        if (!isset($query)) {
            $model            = new KPLocation();
            $model->parent_id = $parentId;
            $model->name      = $main;
            $model->save();
            return $model->id;
        } else {
            return $query;
        }
    }

    private function setPostIndex ($localityId, $item, $postCode, $newPostCode): void
    {
        $find = KPPostCode::query()
            ->where([
                'postcode'        => $postCode,
                'kp_locations_id' => $localityId
            ])
            ->value('id');
        if (!isset($find)) {
            $model = new KPPostCode();
            $model->kp_locations_id = $localityId;
            $model->new_postcode    = $newPostCode;
            $model->postcode        = $postCode;
            $model->address         = $item[10].', '.$item[11];
            $model->title           = $postCode.', '.$item[1];
            $model->name            = $item[1];
            $model->type            = $item[2];
            $model->save();
        } else {
            KPPostCode::query()
                ->where([
                    'postcode'        => $postCode,
                    'kp_locations_id' => $localityId
                ])
                ->update(array('title' => $postCode.', '.$item[10].', '.$item[11]));
        }
    }

    /**
     * @param Collection $collection
     * @return bool
     */
    public function collection(Collection $collection): bool
    {
        $types = array('Супермаркет', 'СОПС', 'ГОПС', 'ПОПС', 'РУПС', 'ЦОУ');
        foreach ($collection as $item) {
            $postCode    = $item['12_iuridiceskii_adres'];
            $newPostCode = $item[6];
            $region      = $item[7];
            $area        = $item[8];
            $locality    = $item[9];
            $postCodeCheck    = isset($postCode);
            $regionCheck      = isset($region);
            $areaCheck        = isset($area);
            $localityCheck    = isset($locality);
            if (isset($item['13_rezim_raboty_celoe']) && $postCodeCheck) {
                if (in_array($item[2], $types)) {
                    // Если есть Область, Район и Населенный пункт
                    if ($regionCheck && $areaCheck && $localityCheck) {
                        $regionID   = $this->setLocation($region, 0);
                        $areaID     = $this->setLocation($area, $regionID);
                        $localityID = $this->setLocation($locality, $areaID);
                        $this->setPostIndex($localityID, $item, $postCode, $newPostCode);
                    }
                    // Если нет Район, но есть Область и Населенный пункт
                    if ($regionCheck && !$areaCheck && $localityCheck) {
                        $regionID   = $this->setLocation($region, 0);
                        $areaID     = $this->setLocation($locality, $regionID);
                        $localityID = $this->setLocation($locality, $areaID);
                        $this->setPostIndex($localityID, $item, $postCode, $newPostCode);
                    }
                }
            }
        }
        return true;
    }
}
