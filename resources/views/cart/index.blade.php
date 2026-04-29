{{-- FILE: resources/views/cart/index.blade.php --}}
@extends('layouts.app')
@section('title','My Cart')
@push('styles')
<style>
.cart-page{padding:60px 0 80px}
.cart-title{font-family:'Bebas Neue',sans-serif;font-size:38px;letter-spacing:4px;margin-bottom:8px}
.cart-grid{display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;margin-top:32px}
.cart-card{background:var(--dark2);border:1px solid #1e1e1e;border-radius:10px;overflow:hidden}
.cart-item{display:grid;grid-template-columns:110px 1fr auto;gap:18px;align-items:center;padding:18px 22px;border-bottom:1px solid #1a1a1a;transition:background .2s}
.cart-item:last-child{border-bottom:none}
.cart-item:hover{background:rgba(220,0,0,0.02)}
.item-img{width:110px;height:72px;border-radius:6px;overflow:hidden;background:var(--dark3);border:1px solid #222;flex-shrink:0}
.item-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}
.cart-item:hover .item-img img{transform:scale(1.06)}
.item-img-ph{width:100%;height:100%;display:flex;align-items:center;justify-content:center}
.item-img-ph i{font-size:24px;color:#2a2a2a}
.item-name{font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:1.5px;margin-bottom:3px}
.item-price{color:var(--ferrari-red);font-weight:700;font-size:15px}
.item-actions{display:flex;align-items:center;gap:10px}
.qty-wrap{display:flex;align-items:center}
.qty-btn{width:30px;height:30px;background:var(--dark3);border:1px solid #333;color:var(--light);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all .2s}
.qty-btn:hover{background:var(--ferrari-red);border-color:var(--ferrari-red)}
.qty-btn:first-child{border-radius:4px 0 0 4px}
.qty-btn:last-child{border-radius:0 4px 4px 0}
.qty-num{width:36px;height:30px;background:var(--dark3);border:1px solid #333;border-left:none;border-right:none;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px}
.btn-rm{background:none;border:1px solid #2a2a2a;color:#555;width:30px;height:30px;border-radius:4px;cursor:pointer;font-size:11px;transition:all .2s;display:flex;align-items:center;justify-content:center}
.btn-rm:hover{border-color:#ff4444;color:#ff4444;background:rgba(255,68,68,0.06)}
.cart-empty{text-align:center;padding:64px 24px;color:var(--gray)}
.cart-empty i{font-size:52px;color:#222;margin-bottom:16px;display:block}
.summary-card{background:var(--dark2);border:1px solid #1e1e1e;border-radius:10px;padding:24px;position:sticky;top:88px}
.summary-title{font-family:'Bebas Neue',sans-serif;font-size:22px;letter-spacing:2px;margin-bottom:20px}
.sum-row{display:flex;justify-content:space-between;margin-bottom:11px;font-size:14px}
.sum-row.total{font-weight:700;font-size:18px;color:var(--ferrari-red);border-top:1px solid #1e1e1e;padding-top:12px;margin-top:4px}
.btn-checkout{width:100%;padding:14px;background:var(--ferrari-red);color:#fff;border:none;border-radius:4px;font-weight:700;font-size:13px;letter-spacing:2px;text-transform:uppercase;cursor:pointer;font-family:'Barlow',sans-serif;margin-top:16px;transition:background .25s,transform .2s,box-shadow .25s;position:relative;overflow:hidden}
.btn-checkout::after{content:'';position:absolute;inset:0;background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);transform:translateX(-100%);transition:transform .45s ease}
.btn-checkout:hover::after{transform:translateX(100%)}
.btn-checkout:hover{background:#b00000;transform:translateY(-2px);box-shadow:0 8px 24px rgba(220,0,0,0.3)}
@media(max-width:768px){.cart-grid{grid-template-columns:1fr}.cart-item{grid-template-columns:80px 1fr;gap:12px}.item-actions{grid-column:1/-1}}
</style>
@endpush
@section('content')
<div class="container cart-page">
    <div class="cart-title">My <span style="color:var(--ferrari-red)">Cart</span></div>
    <div class="section-divider"></div>

    @if($items->isEmpty())
    <div class="cart-card" style="margin-top:32px">
        <div class="cart-empty">
            <i class="fas fa-shopping-cart"></i>
            <p style="font-size:18px;font-family:'Bebas Neue',sans-serif;letter-spacing:2px;margin-bottom:8px">YOUR CART IS EMPTY</p>
            <p style="font-size:13px;margin-bottom:24px">Browse our Ferrari collection and add cars to your cart.</p>
            <a href="{{ route('shop') }}" class="btn btn-red">Explore Cars</a>
        </div>
    </div>
    @else
    <div class="cart-grid">
        <div class="cart-card">
            @foreach($items as $item)
            <div class="cart-item" id="ci-{{ $item->id }}">
                <div class="item-img">
                    @if($item->car->image && file_exists(storage_path('app/public/cars/'.$item->car->image)))
                        <img src="{{ asset('storage/cars/'.$item->car->image) }}" alt="{{ $item->car->name }}">
                    @else
                        <div class="item-img-ph"><i class="fas fa-car"></i></div>
                    @endif
                </div>
                <div>
                    <div class="item-name">{{ $item->car->name }}</div>
                    <div class="item-price">₱{{ number_format($item->car->price, 2) }}</div>
                </div>
                <div class="item-actions">
                    <form method="POST" action="{{ route('cart.update', $item) }}" id="upd-{{ $item->id }}">
                        @csrf @method('PATCH')
                        <div class="qty-wrap">
                            <button type="button" class="qty-btn" onclick="chQty({{ $item->id }}, -1)">−</button>
                            <div class="qty-num" id="qn-{{ $item->id }}">{{ $item->quantity }}</div>
                            <button type="button" class="qty-btn" onclick="chQty({{ $item->id }}, 1)">+</button>
                        </div>
                        <input type="hidden" name="quantity" id="qi-{{ $item->id }}" value="{{ $item->quantity }}">
                    </form>
                    <form method="POST" action="{{ route('cart.remove', $item) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-rm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="summary-card">
            <div class="summary-title">Order Summary</div>
            @foreach($items as $item)
            <div class="sum-row">
                <span style="color:var(--gray)">{{ Str::limit($item->car->name,22) }}</span>
                <span>₱{{ number_format($item->car->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
            <div class="sum-row total"><span>Total</span><span>₱{{ number_format($total, 2) }}</span></div>
            <a href="{{ route('cart.checkout') }}" class="btn-checkout" style="display:block;text-align:center;text-decoration:none">
                Proceed to Checkout
            </a>
            <a href="{{ route('shop') }}" class="btn btn-outline btn-sm" style="width:100%;text-align:center;margin-top:10px;display:block">Continue Shopping</a>
        </div>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function chQty(id, d) {
    const disp  = document.getElementById('qn-'+id);
    const input = document.getElementById('qi-'+id);
    let v = parseInt(disp.textContent) + d;
    if(v < 1) v = 1; if(v > 10) v = 10;
    disp.textContent = v; input.value = v;
    document.getElementById('upd-'+id).submit();
}
</script>
@endpush