<div class="dash-grid">
    <div class="total-sales" style="background-color: #3498db; color: #ffffff;">
        <a href="/store/sales">Solds</a>
        <p> {{ $store->width_sales }} </p>
    </div>
    <div class="top-listings" style="background-color: #2ecc71; color: #ffffff;">
        <a href="/store/products">Listings</a>
        <p> {{ $store->products->count() }} </p>
    </div>
    <div class="top-earning" style="background-color: #f39c12; color: #ffffff;">
        <a href="/store/earnings">Referrals</a>
        <p>0</p>
    </div>
    <div class="total-sales" style="background-color: #e74c3c; color: #ffffff;">
        <a href="/store/sales">Favorited</a>
        <p> {{ $store->StoreFavorited->count() }} </p>
    </div>
    <div class="total-sales" style="background-color: #95a5a6; color: #ffffff;">
        <a href="/store/sales">Blocked</a>
        <p> {{ $store->Storeblocked->count() }} </p>
    </div>
    <div class="total-orders" style="background-color: #e67e22; color: #ffffff;">
        <a href="/store/orders">Orders</a>
        <p>{{ $store->orders->count() }}</p>
    </div>
    <div class="total-orders" style="background-color: #27ae60; color: #ffffff;">
        <a href="/store/orders">Dispute Won</a>
        <p>{{ $store->disputes_won }}</p>
    </div>
    <div class="total-orders" style="background-color: #c0392b; color: #ffffff;">
        <a href="/store/orders">Dispute Lose</a>
        <p>{{ $store->disputes_lost }}</p>
    </div>
    <div class="total-returns" style="background-color: #3498db; color: #ffffff;">
        <a href="/store/returns">Store Reports</a>
        <p> {{ $store->storeReports->count() }} </p>
    </div>
    <div class="all-reviews" style="background-color: #8e44ad; color: #ffffff;">
        <a href="/store/reviews">Reviews</a>
        <p> {{ $store->reviews->count() }} </p>
    </div>
</div>
