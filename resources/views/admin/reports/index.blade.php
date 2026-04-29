@extends('layouts.admin')
@section('title','Reports')
@section('page-title','Reports & Analytics')
@section('content')

<style>
:root {
    --rpt-card:         #16152a;
    --rpt-card-border:  rgba(139,92,246,0.18);
    --rpt-card-glow:    0 0 40px rgba(139,92,246,0.07), 0 2px 16px rgba(0,0,0,0.5);
    --rpt-purple:       #a78bfa;
    --rpt-purple-dim:   #7c3aed;
    --rpt-text:         #e2e0f0;
    --rpt-text-muted:   #6b6888;
    --rpt-grid:         rgba(139,92,246,0.10);
    --rpt-tick:         #4b4870;
    --rpt-badge-bg:     rgba(167,139,250,0.12);
    --rpt-badge-color:  #c4b5fd;
    --rpt-badge-border: rgba(167,139,250,0.28);
    --rpt-select-bg:    #1e1c35;
    --rpt-divider:      rgba(139,92,246,0.18);
}

* { box-sizing: border-box; }

.rpt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.rpt-card {
    background: var(--rpt-card);
    border: 1px solid var(--rpt-card-border);
    border-radius: 20px;
    padding: 24px 24px 18px;
    box-shadow: var(--rpt-card-glow);
    position: relative;
    overflow: hidden;
}

.rpt-card::before {
    content: '';
    position: absolute;
    top: -50px; left: -50px;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(124,58,237,0.16) 0%, transparent 70%);
    pointer-events: none;
}

.rpt-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 4px;
}

.rpt-card-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--rpt-text);
    margin: 0 0 2px;
    letter-spacing: -0.2px;
}

.rpt-card-sub {
    font-size: 12px;
    color: var(--rpt-text-muted);
    margin: 0;
}

.rpt-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    background: var(--rpt-badge-bg);
    color: var(--rpt-badge-color);
    border: 1px solid var(--rpt-badge-border);
    white-space: nowrap;
    flex-shrink: 0;
}

.rpt-divider {
    height: 1px;
    background: linear-gradient(90deg, var(--rpt-divider) 0%, transparent 100%);
    margin: 14px 0;
}

.rpt-stat-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 18px;
}

.rpt-stat-value {
    font-size: 30px;
    font-weight: 800;
    color: var(--rpt-text);
    letter-spacing: -1px;
    line-height: 1;
}

.rpt-stat-label {
    font-size: 12px;
    color: var(--rpt-text-muted);
}

.rpt-canvas-wrap {
    position: relative;
    width: 100%;
}

.rpt-legend {
    display: flex;
    gap: 16px;
    margin-top: 14px;
    font-size: 11px;
    color: var(--rpt-text-muted);
}

.rpt-legend span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.rpt-legend .ldot {
    width: 8px; height: 8px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}

.rpt-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
    color: var(--rpt-text-muted);
    font-size: 13px;
    gap: 10px;
}

.rpt-empty svg { opacity: 0.22; }

@media (max-width: 768px) {
    .rpt-grid { grid-template-columns: 1fr; }
}
</style>

<div class="rpt-grid">

    {{-- ════ Monthly Revenue ════ --}}
    <div class="rpt-card">
        <div class="rpt-card-header">
            <div>
                <p class="rpt-card-title">Monthly Revenue</p>
                <p class="rpt-card-sub">Total earnings per month</p>
            </div>
            <span class="rpt-badge" id="best-month-badge">— best</span>
        </div>
        <div class="rpt-divider"></div>

        @if($salesByMonth->isEmpty())
            <div class="rpt-empty">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                No revenue data yet.
            </div>
        @else
            <div class="rpt-stat-row">
                <span class="rpt-stat-value" id="revenue-counter">₱0</span>
                <span class="rpt-stat-label">total this year</span>
            </div>
            <div class="rpt-canvas-wrap" style="height:230px;">
                <canvas id="revenueChart" role="img"
                    aria-label="Area line chart of monthly revenue">Monthly revenue data.</canvas>
            </div>
            <div class="rpt-legend">
                <span>
                    <i class="ldot" style="background:#a78bfa; box-shadow:0 0 6px #a78bfa88;"></i>
                    Revenue (₱)
                </span>
            </div>
        @endif
    </div>

    {{-- ════ Top Selling Cars ════ --}}
    <div class="rpt-card">
        <div class="rpt-card-header">
            <div>
                <p class="rpt-card-title">Top Selling Cars</p>
                <p class="rpt-card-sub">Best performers by revenue</p>
            </div>
            <span class="rpt-badge" id="top-car-badge">— orders</span>
        </div>
        <div class="rpt-divider"></div>

        @if($topCars->isEmpty())
            <div class="rpt-empty">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 4v16m8-8H4"/>
                </svg>
                No car sales data yet.
            </div>
        @else
            <div class="rpt-stat-row">
                <span class="rpt-stat-value" id="top-car-name" style="font-size:18px; letter-spacing:-0.5px;">—</span>
            </div>
            <div class="rpt-canvas-wrap"
                 style="height:{{ max(200, $topCars->count() * 46 + 56) }}px;">
                <canvas id="carsChart" role="img"
                    aria-label="Horizontal bar chart of top selling cars">Top cars by revenue.</canvas>
            </div>
            <div class="rpt-legend">
                <span>
                    <i class="ldot" style="background:#818cf8; box-shadow:0 0 6px #818cf888;"></i>
                    Revenue (₱)
                </span>
            </div>
        @endif
    </div>

</div>

{{-- PHP → JS --}}
@if(!$salesByMonth->isEmpty())
<script>
const revenueLabels = @json(
    $salesByMonth->map(fn($r) =>
        DateTime::createFromFormat('!m', $r->month)->format('M')
    )
);
const revenueValues = @json($salesByMonth->pluck('total'));
const revenueOrders = @json($salesByMonth->pluck('count'));
const revenueTotal  = @json($salesByMonth->sum('total'));
</script>
@endif

@if(!$topCars->isEmpty())
<script>
const carLabels    = @json($topCars->map(fn($r) => $r->car->name));
const carRevenue   = @json($topCars->pluck('revenue'));
const carOrders    = @json($topCars->pluck('orders_count'));
const topCarName   = @json($topCars->first()->car->name);
const topCarOrders = @json($topCars->first()->orders_count);
</script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── helpers ── */
    function peso(v) {
        if (v >= 1000000) return '₱' + (v / 1000000).toFixed(1) + 'M';
        if (v >= 1000)    return '₱' + Math.round(v / 1000) + 'K';
        return '₱' + Math.round(v).toLocaleString();
    }

    /* ── draw-on line animation ── */
    function animateLine(chart) {
        const obj = { p: 0 };
        gsap.to(obj, {
            p: 1, duration: 1.5, ease: 'power2.inOut', delay: 0.15,
            onUpdate() {
                const w = chart.chartArea ? chart.chartArea.right : 0;
                chart.data.datasets[0].clip = { left: 0, right: w * obj.p, top: -10, bottom: 0 };
                chart.draw();
            }
        });
    }

    /* ── GSAP horizontal bar grow ── */
    function animateBarsH(chart) {
        chart.getDatasetMeta(0).data.forEach((el, i) => {
            const tx = el.x, tw = el.width;
            el.x = el.base; el.width = 0;
            gsap.to(el, {
                x: tx, width: tw, duration: 0.72,
                delay: 0.35 + i * 0.10, ease: 'power3.out',
                onUpdate: () => chart.draw()
            });
        });
    }

    /* ── shared dark theme ── */
    const PURPLE  = '#a78bfa';
    const INDIGO  = '#818cf8';
    const GRID    = 'rgba(139,92,246,0.10)';
    const TICK    = '#4b4870';
    const TICK_Y  = '#6b6888';

    Chart.defaults.color                             = TICK;
    Chart.defaults.plugins.tooltip.backgroundColor  = '#1e1c35';
    Chart.defaults.plugins.tooltip.borderColor      = 'rgba(167,139,250,0.35)';
    Chart.defaults.plugins.tooltip.borderWidth      = 1;
    Chart.defaults.plugins.tooltip.titleColor       = '#c4b5fd';
    Chart.defaults.plugins.tooltip.bodyColor        = '#9d9bbf';
    Chart.defaults.plugins.tooltip.padding          = 12;
    Chart.defaults.plugins.tooltip.cornerRadius     = 10;
    Chart.defaults.plugins.tooltip.displayColors    = false;

    /* ════════════════════════════════
       REVENUE — area line chart
    ════════════════════════════════ */
    @if(!$salesByMonth->isEmpty())

    /* counter */
    const cObj = { v: 0 };
    gsap.to(cObj, {
        v: revenueTotal, duration: 1.6, ease: 'power2.out', delay: 0.25,
        onUpdate() {
            document.getElementById('revenue-counter').textContent =
                '₱' + Math.round(cObj.v).toLocaleString();
        }
    });

    /* best month */
    const bestIdx = revenueValues.indexOf(Math.max(...revenueValues));
    document.getElementById('best-month-badge').textContent = '↑ ' + revenueLabels[bestIdx];
    gsap.from('#best-month-badge', { opacity: 0, x: 6, duration: 0.5, delay: 0.6 });

    /* gradient fill */
    function makeGrad(ctx, chartArea) {
        const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        g.addColorStop(0,    'rgba(167,139,250,0.38)');
        g.addColorStop(0.55, 'rgba(124,58,237,0.10)');
        g.addColorStop(1,    'rgba(124,58,237,0.00)');
        return g;
    }

    /* ── custom smooth hover tooltip (HTML overlay) ── */
    const revWrap   = document.getElementById('revenueChart').parentElement;
    const hoverTip  = document.createElement('div');
    hoverTip.id     = 'rev-tip';
    hoverTip.style.cssText = `
        position:absolute; pointer-events:none; z-index:10;
        background:#1e1c35; border:1px solid rgba(167,139,250,0.35);
        border-radius:12px; padding:10px 14px; min-width:130px;
        opacity:0; transform:translateY(6px);
        transition:opacity 0.18s ease, transform 0.18s ease;
        font-size:12px; line-height:1.8; color:#9d9bbf;
        box-shadow:0 8px 32px rgba(0,0,0,0.5);
    `;
    revWrap.style.position = 'relative';
    revWrap.appendChild(hoverTip);

    /* vertical crosshair line */
    const crosshair = document.createElement('div');
    crosshair.style.cssText = `
        position:absolute; top:0; width:1px; bottom:0; pointer-events:none; z-index:5;
        background:linear-gradient(to bottom, rgba(167,139,250,0.0) 0%, rgba(167,139,250,0.5) 30%, rgba(167,139,250,0.5) 70%, rgba(167,139,250,0.0) 100%);
        opacity:0; transition:opacity 0.18s ease;
    `;
    revWrap.appendChild(crosshair);

    /* animated hover dot */
    const hoverDot = document.createElement('div');
    hoverDot.style.cssText = `
        position:absolute; width:14px; height:14px; border-radius:50%;
        background:#fff; border:2px solid #a78bfa;
        box-shadow:0 0 12px rgba(167,139,250,0.7);
        pointer-events:none; z-index:6;
        opacity:0; transform:translate(-50%,-50%) scale(0.5);
        transition:opacity 0.18s ease, transform 0.18s ease;
    `;
    revWrap.appendChild(hoverDot);

    let tipVisible = false;

    const revChart = new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue',
                data: revenueValues,
                borderColor: PURPLE,
                borderWidth: 2.5,
                pointBackgroundColor: PURPLE,
                pointBorderColor: '#16152a',
                pointBorderWidth: 2,
                pointRadius: 3.5,
                pointHoverRadius: 0,   /* we draw our own dot */
                tension: 0.42,
                fill: true,
                backgroundColor(ctx) {
                    const c = ctx.chart;
                    if (!c.chartArea) return 'transparent';
                    return makeGrad(c.ctx, c.chartArea);
                },
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend:  { display: false },
                tooltip: { enabled: false },   /* native tooltip off */
            },
            scales: {
                x: {
                    grid: { color: GRID, lineWidth: 1, borderDash: [5, 5] },
                    border: { dash: [5, 5], color: 'transparent' },
                    ticks: { color: TICK, font: { size: 11 }, autoSkip: false, maxRotation: 0 }
                },
                y: {
                    grid: { color: GRID, lineWidth: 1, borderDash: [5, 5] },
                    border: { dash: [5, 5], color: 'transparent' },
                    ticks: { color: TICK, font: { size: 10 }, callback: v => peso(v) },
                    beginAtZero: false,
                }
            },
            onHover(e, elements) {
                if (!elements.length) {
                    /* hide everything */
                    hoverTip.style.opacity    = '0';
                    hoverTip.style.transform  = 'translateY(6px)';
                    crosshair.style.opacity   = '0';
                    hoverDot.style.opacity    = '0';
                    hoverDot.style.transform  = 'translate(-50%,-50%) scale(0.5)';
                    tipVisible = false;
                    return;
                }

                const el    = elements[0];
                const idx   = el.index;
                const meta  = revChart.getDatasetMeta(0);
                const pt    = meta.data[idx];
                const ca    = revChart.chartArea;
                const rect  = revChart.canvas.getBoundingClientRect();
                const wRect = revWrap.getBoundingClientRect();

                /* dot position */
                const dotX = pt.x;
                const dotY = pt.y;
                hoverDot.style.left    = dotX + 'px';
                hoverDot.style.top     = dotY + 'px';
                hoverDot.style.opacity = '1';
                hoverDot.style.transform = 'translate(-50%,-50%) scale(1)';

                /* crosshair */
                crosshair.style.left    = (dotX - 0.5) + 'px';
                crosshair.style.top     = ca.top + 'px';
                crosshair.style.height  = (ca.bottom - ca.top) + 'px';
                crosshair.style.opacity = '1';

                /* tooltip content */
                hoverTip.innerHTML = `
                    <div style="color:#c4b5fd;font-weight:700;font-size:13px;margin-bottom:4px;">${revenueLabels[idx]}</div>
                    <div style="display:flex;justify-content:space-between;gap:16px;">
                        <span>Revenue</span>
                        <span style="color:#e2e0f0;font-weight:600;">${peso(revenueValues[idx])}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;gap:16px;">
                        <span>Orders</span>
                        <span style="color:#e2e0f0;font-weight:600;">${revenueOrders[idx]}</span>
                    </div>
                `;

                /* tooltip position — keep in bounds */
                const tipW   = 150;
                const canvasW = revChart.canvas.offsetWidth;
                let tipLeft   = dotX + 14;
                if (tipLeft + tipW > canvasW) tipLeft = dotX - tipW - 14;
                hoverTip.style.left      = tipLeft + 'px';
                hoverTip.style.top       = (dotY - 20) + 'px';
                hoverTip.style.opacity   = '1';
                hoverTip.style.transform = 'translateY(0)';
                tipVisible = true;
            }
        }
    });

    revChart.update();
    requestAnimationFrame(() => animateLine(revChart));

    @endif

    /* ════════════════════════════════
       TOP CARS — horizontal bars
    ════════════════════════════════ */
    @if(!$topCars->isEmpty())

    document.getElementById('top-car-name').textContent = topCarName;
    document.getElementById('top-car-badge').textContent = topCarOrders + ' orders';
    gsap.from('#top-car-name', { opacity: 0, y: 5, duration: 0.5, delay: 0.35 });

    /* purple → indigo gradient per bar */
    function barBg(i, total) {
        if (i === 0) return PURPLE;
        const t = total <= 1 ? 1 : i / (total - 1);
        const r = Math.round(167 + (129 - 167) * t);
        const g = Math.round(139 + (140 - 139) * t);
        const b = Math.round(250 + (248 - 250) * t);
        return `rgba(${r},${g},${b},${0.85 - t * 0.3})`;
    }

    const carsChart = new Chart(document.getElementById('carsChart'), {
        type: 'bar',
        indexAxis: 'y',
        data: {
            labels: carLabels,
            datasets: [{
                label: 'Revenue',
                data: carRevenue,
                backgroundColor: carRevenue.map((_, i) => barBg(i, carRevenue.length)),
                hoverBackgroundColor: carRevenue.map((_, i) => {
                    /* brighter on hover */
                    if (i === 0) return '#c4b5fd';
                    const t = carRevenue.length <= 1 ? 1 : i / (carRevenue.length - 1);
                    const r = Math.round(196 + (165 - 196) * t);
                    const g = Math.round(181 + (180 - 181) * t);
                    const b = Math.round(253 + (248 - 253) * t);
                    return `rgba(${r},${g},${b},0.95)`;
                }),
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            transitions: {
                active: { animation: { duration: 200 } }  /* smooth color transition on hover */
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#1e1c35',
                    borderColor: 'rgba(167,139,250,0.35)',
                    borderWidth: 1,
                    titleColor: '#c4b5fd',
                    bodyColor: '#9d9bbf',
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        title: ctx => ctx[0].label,
                        label: ctx => [
                            'Revenue   ' + peso(ctx.raw),
                            'Orders    ' + carOrders[ctx.dataIndex]
                        ]
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: GRID, lineWidth: 1, borderDash: [5, 5] },
                    border: { dash: [5, 5], color: 'transparent' },
                    ticks: { color: TICK, font: { size: 10 }, callback: v => peso(v) }
                },
                y: {
                    grid: { color: 'transparent' },
                    border: { color: 'transparent' },
                    ticks: { color: TICK_Y, font: { size: 11, weight: '500' } }
                }
            }
        }
    });

    requestAnimationFrame(() => animateBarsH(carsChart));

    @endif

}); // DOMContentLoaded
</script>

@endsection