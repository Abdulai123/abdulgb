@if ($action != null)
    @switch($action)
        @case('pgp')
            @include('User.2fa')
        @break

        @case('storeKey')
            @include('User.storeKey')
        @break

        @case('changePassword')
            @include('User.changePassword')
        @break

        @case('referral')
            @include('User.referral')
        @break

        @case('stats')
            @include('User.stats')
        @break

        @case('fiat_currency')
            @include('User.fiats')
        @break

        @case('mirror')
            @include('User.mirror')
        @break

        @case('my setup')
            @include('User.setup')
        @break

        @case('my block list')
            @include('User.blockedStore')
        @break

        @case('my likes')
            @include('User.favoriteListing')
        @break

        @case('deposit')
            @include('User.deposit')
        @break

        @case('my wallet')
            @include('User.withdraw')
        @break

        @case('my orders')
            @include('User.orders')
        @break

        @case('my reviews')
            @include('User.myRiviews')
        @break

        @case('become a vendor')
            @include('User.storeKey')
        @break

        @default
            @include('User.main')
    @endswitch
@else
    @include('User.main')
@endif
