@php
    $daysSinceOrderCompletion = now()->diffInDays($order->updated_at);
    $editWindowOpen = $order->status == 'completed' || $order->product->payment_type == "FE";
    $review = $order->review;
    $disableFields = $editWindowOpen && $daysSinceOrderCompletion > 5 ? 'disabled' : '';
@endphp


@if ($editWindowOpen)
    <p style="text-align: center; text-decoration: underline; color: red;">Leave a review for this product below. You can
        edit it within 5 days!</p>
    @php
        $review = $order->review;
    @endphp
    <form action="" method="post" style="text-align: center">
        @csrf
        <div style="margin-bottom:1em;"><strong>Review Type: </strong>
            <label for="positive">Positive</label>
            <input type="radio" name="review_type" id="positive" value="positive"
                {{ !$review || $review->feedback == 'positive' ? 'checked' : '' }} {{ $disableFields }}>
            <label for="neutral">Neutral</label>
            <input type="radio" name="review_type" id="neutral" value="neutral"
                {{ $review && $review->feedback == 'neutral' ? 'checked' : '' }}  {{ $disableFields }}>
            <label for="negative">Negative</label>
            <input type="radio" name="review_type" id="negative" value="negative"
                {{ $review && $review->feedback == 'negative' ? 'checked' : '' }}  {{ $disableFields }}>
        </div>

        <textarea name="comment" class="support-msg" id="dispute" cols="30" rows="10"
            placeholder="Leave your review comment here... (max characters: 500)" style="width: 100%;" required  {{ $disableFields }}>
    
    @if ($review && $review->comment)
{{ $review->comment }}
@endif
</textarea>
        <br><br>

        <div style="display:flex; gap:1em;">
            <select name="communication_rating" id="communication_rating" required {{ $disableFields }}>
                <option value="" selected>---Communication Rating---</option>
                <option value="5" {{ ($review && $review->communication_rating == '5') ? 'selected' : '' }}>
                    Outstanding</option>
                <option value="4" {{ ($review && $review->communication_rating == '4') ? 'selected' : '' }}>Excellent
                </option>
                <option value="3" {{ ($review && $review->communication_rating == '3') ? 'selected' : '' }}>Good
                </option>
                <option value="2" {{ ($review && $review->communication_rating == '2') ? 'selected' : '' }}>Fair
                </option>
                <option value="1" {{ ($review && $review->communication_rating == '1') ? 'selected' : '' }}>Poor
                </option>
            </select>
        
            <select name="product_rating" id="product_rating" required {{ $disableFields }}>
                <option value="" selected>---Product Rating---</option>
                <option value="5" {{ ($review && $review->product_rating == '5') ? 'selected' : '' }}>Outstanding
                </option>
                <option value="4" {{ ($review && $review->product_rating == '4') ? 'selected' : '' }}>Excellent
                </option>
                <option value="3" {{ ($review && $review->product_rating == '3') ? 'selected' : '' }}>Good</option>
                <option value="2" {{ ($review && $review->product_rating == '2') ? 'selected' : '' }}>Fair</option>
                <option value="1" {{ ($review && $review->product_rating == '1') ? 'selected' : '' }}>Poor</option>
            </select>
        
            <select name="shipping_speed_rating" id="shipping_speed_rating" required {{ $disableFields }}>
                <option value="" selected>---Shipping Speed Rating---</option>
                <option value="5" {{ ($review && $review->shipping_speed_rating == '5') ? 'selected' : '' }}>
                    Outstanding</option>
                <option value="4" {{ ($review && $review->shipping_speed_rating == '4') ? 'selected' : '' }}>
                    Excellent
                </option>
                <option value="3" {{ ($review && $review->shipping_speed_rating == '3') ? 'selected' : '' }}>Good
                </option>
                <option value="2" {{ ($review && $review->shipping_speed_rating == '2') ? 'selected' : '' }}>Fair
                </option>
                <option value="1" {{ ($review && $review->shipping_speed_rating == '1') ? 'selected' : '' }}>Poor
                </option>
            </select>
        
            <select name="price_rating" id="price_rating" required {{ $disableFields }}>
                <option value="" selected>---Price Rating---</option>
                <option value="5" {{ ($review && $review->price_rating == '5') ? 'selected' : '' }}>Outstanding
                </option>
                <option value="4" {{ ($review && $review->price_rating == '4') ? 'selected' : '' }}>Excellent
                </option>
                <option value="3" {{ ($review && $review->price_rating == '3') ? 'selected' : '' }}>Good</option>
                <option value="2" {{ ($review && $review->price_rating == '2') ? 'selected' : '' }}>Fair</option>
                <option value="1" {{ ($review && $review->price_rating == '1') ? 'selected' : '' }}>Poor</option>
            </select>
        </div>        

        <input type="submit" class="submit-nxt" name="review_form" value="Save"  {{ $disableFields }}>
    </form>
@endif
