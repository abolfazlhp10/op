<x-mail::message>

    <h1 style="text-align: center;">Op Team</h1>

<div dir="rtl">
سلام {{$user->name}} براي عوض كردن رمز عبور خود بر روي لينك زير كليك كنيد
<br>
<a href="{{'https://op-team.ir/forgetpassword/'.$user->email.'/'.$token}}">فراموشي رمز عبور</a>
</div>


با احترام,<br>
تيم اوپي<br>
</x-mail::message>
