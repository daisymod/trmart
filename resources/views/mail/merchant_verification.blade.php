@extends("mail.body")
@section("content")
    <p>Мерчант отправил данные на проверку</p>
    <p>
        Посмотреть профиль: <a href="{{ route("merchant.edit", $id) }}">{{ route("merchant.edit", $id) }}</a>
    </p>
@endsection
