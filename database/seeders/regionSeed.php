<?php

namespace Database\Seeders;

use App\Models\TurkeyRegion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class regionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = ['İstanbul',' Ankara',' İzmir',' Adana',' Adıyaman',' Afyonkarahisar',' Ağrı',' Aksaray',' Amasya',' Antalya',' Ardahan',' Artvin',' Aydın',' Balıkesir',' Bartın',' Batman',' Bayburt',' Bilecik',' Bingöl',' Bitlis',' Bolu',' Burdur',' Bursa',' Çanakkale',' Çankırı',' Çorum',' Denizli',' Diyarbakır',' Düzce',' Edirne',' Elazığ',' Erzincan',' Erzurum',' Eskişehir',' Gaziantep',' Giresun',' Gümüşhane',' Hakkari',' Hatay',' Iğdır',' Isparta',' Kahramanmaraş',' Karabük',' Karaman',' Kars',' Kastamonu',' Kayseri',' Kırklareli',' Kırşehir',' Kilis',' Kocaeli',' Konya',' Kütahya',' Malatya',' Manisa',' Mardin',' Mersin',' Muğla',' Muş',' Nevşehir',' Niğde',' Ordu',' Osmaniye',' Rize',' Sakarya',' Samsun',' Siirt',' Sinop',' Sivas',' Şanlıurfa',' Şırnak',' Tekirdağ',' Tokat',' Trabzon',' Tunceli',' Uşak',' Van',' Yalova',' Yozgat',' Zonguldak
'];

        foreach ($regions as $region){
            TurkeyRegion::firstOrCreate(
                [
                    'name' => $region,
                ]
            );
        }

    }
}
