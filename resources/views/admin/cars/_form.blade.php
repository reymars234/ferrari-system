{{-- resources/views/admin/cars/_form.blade.php --}}

@push('styles')
<style>
.car-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.car-form-grid .span-2 { grid-column: 1 / -1; }
.car-form-grid .form-group { margin-bottom: 0; }

/* Rarity */
.rarity-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 7px;
}
.rarity-radio { display: none; }
.rarity-tile {
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    padding: 9px 4px; border-radius: 7px;
    border: 2px solid #1e1e1e; background: #111;
    text-align: center; cursor: pointer;
    transition: border-color .2s, background .2s, transform .18s, box-shadow .2s;
    user-select: none;
}
.rarity-tile i     { font-size: 16px; }
.rarity-tile span  { font-size: 9px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
.rarity-tile small { font-size: 8px; color: #444; line-height: 1.3; }
.rarity-radio:checked + .rarity-tile {
    transform: translateY(-2px);
    box-shadow: 0 5px 16px rgba(0,0,0,.45);
}

/* Toggle */
.featured-wrap {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 13px; border-radius: 7px;
    border: 1px solid #1e1e1e; background: #111;
    cursor: pointer; transition: border-color .2s, background .2s;
}
.featured-wrap input[type="checkbox"] { display: none; }
.toggle-track {
    width: 36px; height: 20px; border-radius: 10px;
    background: #2a2a2a; position: relative; flex-shrink: 0;
    transition: background .2s;
}
.toggle-thumb {
    position: absolute; top: 2px; left: 2px;
    width: 16px; height: 16px; border-radius: 50%;
    background: #555; transition: transform .2s, background .2s;
}

/* Image preview */
.img-preview-box {
    border: 1px dashed #252525; border-radius: 7px;
    background: #0d0d0d; min-height: 100px;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.img-preview-box img { max-height: 100px; object-fit: contain; display: block; }
.img-ph { color: #2a2a2a; font-size: 11px; text-align: center; padding: 12px; }
.img-ph i { font-size: 26px; display: block; margin-bottom: 5px; }
</style>
@endpush

<div class="car-form-grid">

    {{-- Name --}}
    <div class="form-group">
        <label>Car Name *</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $car->name ?? '') }}" required>
        <div class="form-error">@error('name'){{ $message }}@enderror</div>
    </div>

    {{-- Price --}}
    <div class="form-group">
        <label>Price (₱) *</label>
        <input type="number" name="price" class="form-control" step="0.01" min="1"
               value="{{ old('price', $car->price ?? '') }}" required>
        <div class="form-error">@error('price'){{ $message }}@enderror</div>
    </div>

    {{-- Stock --}}
    <div class="form-group">
        <label>Stock *</label>
        <input type="number" name="stock" class="form-control" min="0"
               value="{{ old('stock', $car->stock ?? 1) }}" required>
        <div class="form-error">@error('stock'){{ $message }}@enderror</div>
    </div>

    {{-- Available + Featured stacked --}}
    <div style="display:flex;flex-direction:column;gap:8px">
        <label style="display:flex;align-items:center;gap:9px;padding:10px 13px;
                      border-radius:7px;border:1px solid #1e1e1e;background:#111;
                      cursor:pointer;margin-bottom:0">
            <input type="checkbox" name="is_available" id="is_available"
                   style="accent-color:#dc0000;width:15px;height:15px;flex-shrink:0"
                   {{ old('is_available', $car->is_available ?? true) ? 'checked' : '' }}>
            <span style="font-size:13px;color:var(--light);font-weight:400;
                         text-transform:none;letter-spacing:0">
                Available for purchase
            </span>
        </label>

        <input type="hidden" name="is_featured" value="0">
        <label class="featured-wrap" id="featuredLabel" style="margin-bottom:0">
            <input type="checkbox" name="is_featured" value="1"
                   id="featuredCheck"
                   {{ old('is_featured', $car->is_featured ?? false) ? 'checked' : '' }}>
            <div class="toggle-track" id="toggleTrack">
                <div class="toggle-thumb" id="toggleThumb"></div>
            </div>
            <span style="font-size:13px;color:var(--light);font-weight:400;
                         text-transform:none;letter-spacing:0">
                Featured on shop
            </span>
        </label>
    </div>

    {{-- Description --}}
    <div class="form-group span-2">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="2"
        >{{ old('description', $car->description ?? '') }}</textarea>
        <div class="form-error">@error('description'){{ $message }}@enderror</div>
    </div>

    {{-- Rarity --}}
    <div class="form-group span-2">
        <label style="display:flex;align-items:center;gap:7px;margin-bottom:9px">
            <i class="fas fa-crown" style="color:#dc0000"></i>
            Car Rarity
            <span style="font-size:10px;color:var(--gray);font-weight:400;
                         letter-spacing:1px;text-transform:none">
                — controls shop filter
            </span>
        </label>
        <div class="rarity-grid">
            @foreach(\App\Models\Car::RARITIES as $key => $r)
            <label style="margin-bottom:0">
                <input type="radio" name="rarity" value="{{ $key }}"
                       class="rarity-radio"
                       {{ old('rarity', $car->rarity ?? 'common') === $key ? 'checked' : '' }}>
                <div class="rarity-tile" data-color="{{ $r['color'] }}">
                    <i class="fas {{ $r['icon'] }}" style="color:{{ $r['color'] }}"></i>
                    <span style="color:{{ $r['color'] }}">{{ $r['label'] }}</span>
                    <small>{{ $r['desc'] }}</small>
                </div>
            </label>
            @endforeach
        </div>
        <div class="form-error">@error('rarity'){{ $message }}@enderror</div>
    </div>

    {{-- Image --}}
    <div class="form-group span-2">
        <label>Car Image (JPG, PNG, WEBP — max 4MB)</label>
        <div style="display:grid;grid-template-columns:1fr auto;gap:12px;align-items:start">
            <div>
                <input type="file" name="image" id="imageInput" class="form-control"
                       accept="image/jpeg,image/jpg,image/png,image/webp"
                       onchange="previewCarImage(this)">
                <div class="form-error">@error('image'){{ $message }}@enderror</div>
                @isset($car)
                    @if($car->image)
                    <p style="color:#444;font-size:10px;margin-top:5px;word-break:break-all">
                        {{ $car->image }}
                    </p>
                    @endif
                @endisset
            </div>

            <div class="img-preview-box" id="previewBox" style="width:130px">
                @isset($car)
                    @if($car->image && file_exists(storage_path('app/public/cars/'.$car->image)))
                        <img src="{{ asset('storage/cars/'.$car->image) }}" alt="{{ $car->name }}" id="previewImg">
                    @else
                        <div class="img-ph"><i class="fas fa-image"></i>No image</div>
                    @endif
                @else
                    <div class="img-ph"><i class="fas fa-image"></i>No image</div>
                @endisset
            </div>
        </div>
    </div>

</div>{{-- /.car-form-grid --}}

@push('scripts')
<script>
function previewCarImage(input) {
    const box = document.getElementById('previewBox');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            box.innerHTML = `<img src="${e.target.result}" style="max-height:100px;object-fit:contain;display:block">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Rarity highlight
document.querySelectorAll('.rarity-radio').forEach(radio => {
    const tile  = radio.nextElementSibling;
    const color = tile.dataset.color;
    function activate() {
        document.querySelectorAll('.rarity-tile').forEach(t => {
            t.style.borderColor = '#1e1e1e';
            t.style.background  = '#111';
        });
        tile.style.borderColor = color;
        tile.style.background  = color + '1a';
    }
    radio.addEventListener('change', activate);
    if (radio.checked) activate();
});

// Featured toggle
const featuredCheck = document.getElementById('featuredCheck');
const toggleTrack   = document.getElementById('toggleTrack');
const toggleThumb   = document.getElementById('toggleThumb');
const featuredLabel = document.getElementById('featuredLabel');

function syncFeatured() {
    const on = featuredCheck.checked;
    toggleTrack.style.background    = on ? '#dc0000' : '#2a2a2a';
    toggleThumb.style.transform     = on ? 'translateX(18px)' : 'translateX(0)';
    toggleThumb.style.background    = on ? '#fff' : '#555';
    featuredLabel.style.borderColor = on ? 'rgba(220,0,0,.4)' : '#1e1e1e';
    featuredLabel.style.background  = on ? 'rgba(220,0,0,.04)' : '#111';
}
featuredLabel.addEventListener('click', () => {
    featuredCheck.checked = !featuredCheck.checked;
    syncFeatured();
});
syncFeatured();
</script>
@endpush    