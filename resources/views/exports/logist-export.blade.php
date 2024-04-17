<table>
    <tbody>
        <tr>
            <td width="200px">Направление</td>
            <td width="230px">1</td>
            <td width="200px"></td>
            <td width="500px"></td>
            <td width="140px">Номер договора</td>
            <td width="160px">10004943-2023-170441</td>
            <td width="200px"></td>
            <td width="170px"></td>
            <td width="170px"></td>
            <td width="170px"></td>
            <td width="170px"></td>
            <td width="170px"></td>
        </tr>
        <tr>
            <td>Вид РПО</td>
            <td>4</td>
            <td></td>
            <td></td>
            <td>Дата договора</td>
            <td>10.04.2023</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Категория РПО</td>
            <td>2</td>
            <td></td>
            <td></td>
            <td>Контрагент</td>
            <td>999999.007678</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Отправитель</td>
            <td>«Global Contract Service Sanal mağazacılık» <br> Limited Şirketi</td>
            <td></td>
            <td></td>
            <td>БИН</td>
            <td>000000000000</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Регион назначения</td>
            <td>{{ substr($items[0]->postcode, 0, 2) }}</td>
            <td>Сотовый номер №1 отпр.</td>
            <td>+7 777 271 25 27</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Индекс ОПС места приема</td>
            <td><b>220081</b></td>
            <td>Сотовый номер №2 отпр.</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Всего РПО</td>
            <td>{{ count($items) }}</td>
            <td>email отпр.</td>
            <td>admin@turkiyemart.com</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th><b>№ п.п.</b></th>
            <th><b>Ф.И.О</b></th>
            <th><b>Индекс ОПС места назн.</b></th>
            <th><b>Адрес места назначения</b></th>
            <th><b>ШПИ</b></th>
            <th><b>Вес (кг.)</b></th>
            <th><b>Сумма объявленной ценности</b></th>
            <th><b>Сумма нал. Платежа</b></th>
            <th><b>Особые отметки</b></th>
            <th><b>Сотовый номер №1</b></th>
            <th><b>Сотовый номер №2</b></th>
            <th><b>E-mail</b></th>
        </tr>
        @foreach($items as $i => $item)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->surname.' '.$item->name.' '.$item->middle_name }}</td>
                <td>{{ strval($item->postcode) }}</td>
                <td>{{ $item->country_name.', '.$item->region_name.', '.$item->area_name.', '.$item->city_name.', '.$item->street.', '.$item->house_number.', '.$item->room }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->real_weight / 1000 }}</td>
                <td>{{ $item->price }} тг.</td>
                <td></td>
                <td></td>
                <td>+{{ $item->phone }}</td>
                <td></td>
                <td>{{ $item->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
